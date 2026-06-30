<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Participant;
use App\Models\Payment;
use App\Jobs\SendEmailBlast;
use App\Mail\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $event = Event::with('categories')->latest()->first();

        if (!$event) {
            return view('admin.dashboard', ['event' => null, 'stats' => []]);
        }

        $cats = $event->categories()->withCount([
            'participants',
            'participants as paid_count' => function ($q) { $q->where('payment_status', 'paid'); }
        ])->get();

        $totalFamilyMembers = \App\Models\FamilyMember::whereHas('participant', fn($q) => $q->where('event_id', $event->id))->count();
        $paidFamilyMembers = \App\Models\FamilyMember::whereHas('participant', fn($q) => $q->where('event_id', $event->id)->where('payment_status', 'paid'))->count();

        $stats = [
            'total_participants' => Participant::where('event_id', $event->id)->count(),
            'paid_participants' => Participant::where('event_id', $event->id)->where('payment_status', 'paid')->count(),
            'pending_participants' => Participant::where('event_id', $event->id)->where('payment_status', 'pending')->count(),
            'total_people' => Participant::where('event_id', $event->id)->count() + $totalFamilyMembers,
            'total_paid_people' => Participant::where('event_id', $event->id)->where('payment_status', 'paid')->count() + $paidFamilyMembers,
            'total_revenue' => Payment::whereHas('participant', fn($q) => $q->where('event_id', $event->id))->where('status', 'paid')->sum('final_amount'),
            'categories' => $cats,
        ];

        // Prepare Chart Data
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->subDays(14)->startOfDay();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now()->endOfDay();

        $dailyStats = Participant::where('event_id', $event->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid'),
                DB::raw('SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as pending')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $categoryStats = Participant::where('event_id', $event->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                'category_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date', 'category_id')
            ->get();

        $dates = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        $chartData = [
            'labels' => array_map(fn($d) => Carbon::parse($d)->format('d M'), $dates),
            'datasets' => [
                [
                    'label' => __('messages.admin_total_reg'),
                    'data' => array_map(fn($date) => $dailyStats->get($date)->total ?? 0, $dates),
                    'borderColor' => '#334155',
                    'backgroundColor' => 'rgba(51, 65, 85, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ],
                [
                    'label' => __('messages.admin_paid'),
                    'data' => array_map(fn($date) => $dailyStats->get($date)->paid ?? 0, $dates),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.4,
                ],
                [
                    'label' => __('messages.admin_pending'),
                    'data' => array_map(fn($date) => $dailyStats->get($date)->pending ?? 0, $dates),
                    'borderColor' => '#eab308',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.4,
                ],
            ]
        ];

        foreach ($cats as $cat) {
            $catData = $categoryStats->where('category_id', $cat->id)->keyBy('date');
            $color = match(true) {
                str_contains($cat->slug, '21k') => '#ef4444', // Red
                str_contains($cat->slug, '15k') => '#8b5cf6', // Purple
                str_contains($cat->slug, '10k') => '#ec4899', // Pink
                str_contains($cat->slug, '5k') => '#06b6d4',  // Cyan
                default => '#94a3b8', // Slate focus
            };
            
            $chartData['datasets'][] = [
                'label' => __('messages.admin_category') . ' ' . $cat->name,
                'data' => array_map(fn($date) => $catData->get($date)->count ?? 0, $dates),
                'borderColor' => $color,
                'backgroundColor' => 'transparent',
                'borderDash' => [5, 5],
                'tension' => 0.4,
            ];
        }

        return view('admin.dashboard', compact('event', 'stats', 'chartData', 'startDate', 'endDate'));
    }

    public function participants(Request $request)
    {
        $event = Event::latest()->first();
        $categories = $event ? $event->categories : collect();

        $allColumns = DB::getSchemaBuilder()->getColumnListing('participants');
        
        // Define essential columns for relations, mobile view, and core UI logic
        $essentialCols = [
            'id', 'category_id', 'event_id', 'full_name', 
            'email', 'bib_number', 'payment_status', 'checked_in', 'created_at'
        ];
        
        $requestedCols = $request->input('cols', []);
        
        // If no columns requested, use the user's specified default set
        if (empty($requestedCols)) {
            $requestedCols = ['full_name', 'email', 'phone', 'age', 'shirt_size', 'payment_status', 'rpc', 'created_at'];
        }

        $finalCols = array_unique(array_merge($essentialCols, $requestedCols));
        // Filter out columns that don't exist in DB
        $finalCols = array_intersect($finalCols, $allColumns);

        $query = Participant::with(['category', 'event', 'familyMembers'])
            ->where('event_id', optional($event)->id)
            ->select($finalCols);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                // Only search in selected columns or core identification columns
                $q->where('full_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('bib_number', 'like', "%$s%")
                  ->orWhereHas('familyMembers', function($query) use ($s) {
                      $query->where('full_name', 'like', "%$s%")
                            ->orWhere('email', 'like', "%$s%")
                            ->orWhere('bib_number', 'like', "%$s%");
                  });
            });
        }
        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);

        // Date Range Filter (Default: Last 14 days based on latest participant's registration date)
        $latestParticipantDate = Participant::where('event_id', optional($event)->id)->latest('created_at')->value('created_at');
        $latestDate = $latestParticipantDate ? Carbon::parse($latestParticipantDate) : now();

        $startDate = $request->input('start_date', $latestDate->copy()->subDays(14)->format('Y-m-d\TH:i'));
        $endDate = $request->input('end_date', $latestDate->format('Y-m-d\TH:i'));

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfMinute());
        }

        $perPageInput = $request->input('per_page', 20);
        if ($perPageInput === 'all') {
            $participants = $query->latest()->get();
            // Create a fake length aware paginator or just pass the collection
            // but the view expects links(). We'll wrap in a custom paginator with one page.
            $participants = new \Illuminate\Pagination\LengthAwarePaginator(
                $participants, 
                $participants->count(), 
                max(1, $participants->count()), 
                1, 
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $participants = $query->latest()->paginate((int)$perPageInput)->withQueryString();
        }

        return view('admin.participants', compact('participants', 'categories', 'allColumns', 'requestedCols', 'startDate', 'endDate'));
    }

    public function exportParticipants(Request $request)
    {
        $event = Event::latest()->first();
        $query = Participant::with(['category', 'familyMembers'])->where('event_id', optional($event)->id);

        // Apply same filters as main table
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('bib_number', 'like', "%$s%");
            });
        }
        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);
        if ($request->filled('start_date')) $query->where('created_at', '>=', $request->start_date);
        if ($request->filled('end_date')) $query->where('created_at', '<=', $request->end_date);

        // Column selection
        $allColumns = DB::getSchemaBuilder()->getColumnListing('participants');
        $requestedCols = $request->input('cols', ['full_name', 'email', 'phone', 'age', 'shirt_size', 'payment_status', 'rpc', 'created_at']);
        
        // Add member_type column for clarity
        $finalHeader = array_merge(['member_type'], $requestedCols);
        $finalCols = array_intersect(array_unique(array_merge(['id'], $requestedCols)), $allColumns);

        $participants = $query->latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=participants_' . date('Y-m-d_H-i') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($participants, $requestedCols, $finalHeader) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $finalHeader);

            foreach ($participants as $p) {
                // 1. Participant Row
                $pRow = ['Primary'];
                foreach ($requestedCols as $col) {
                    if ($col == 'category_id') {
                        $pRow[] = $p->category->name ?? '-';
                    } elseif ($col == 'created_at') {
                        $pRow[] = $p->created_at->format('Y-m-d H:i:s');
                    } else {
                        $val = $p->{$col};
                        if (in_array($col, ['identity_number', 'phone', 'emergency_contact_phone']) && !empty($val)) {
                            $val = '="' . $val . '"';
                        }
                        $pRow[] = $val;
                    }
                }
                fputcsv($file, $pRow);

                // 2. Family Member Rows
                foreach ($p->familyMembers as $fm) {
                    $fmRow = ['Family'];
                    foreach ($requestedCols as $col) {
                        if (array_key_exists($col, $fm->getAttributes())) {
                            if ($col == 'created_at') {
                                $fmRow[] = $fm->created_at->format('Y-m-d H:i:s');
                            } else {
                                $val = $fm->{$col};
                                if (in_array($col, ['identity_number', 'phone', 'emergency_contact_phone']) && !empty($val)) {
                                    $val = '="' . $val . '"';
                                }
                                $fmRow[] = $val;
                            }
                        } else {
                            if ($col == 'category_id') {
                                $fmRow[] = $p->category->name ?? '-';
                            } elseif (in_array($col, ['event_id', 'payment_status'])) {
                                $fmRow[] = $p->{$col};
                            } elseif ($col == 'created_at') { // If fm doesn't have it, use parent's
                                $fmRow[] = $p->created_at->format('Y-m-d H:i:s');
                            } else {
                                $fmRow[] = '-';
                            }
                        }
                    }
                    fputcsv($file, $fmRow);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function payments(Request $request)
    {
        $event = Event::latest()->first();
        $categories = $event ? $event->categories : collect();

        $query = Payment::with(['participant.category']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('participant', function ($q) use ($s) {
                $q->where('full_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('bib_number', 'like', "%$s%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date Range Filter (Default: Last 14 days based on latest payment date)
        $latestPaymentDate = Payment::latest('created_at')->value('created_at');
        $latestDate = $latestPaymentDate ? Carbon::parse($latestPaymentDate) : now();

        $startDate = $request->input('start_date', $latestDate->copy()->subDays(14)->format('Y-m-d\TH:i'));
        $endDate = $request->input('end_date', $latestDate->format('Y-m-d\TH:i'));

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfMinute());
        }

        // Column selection logic
        $allColumns = DB::getSchemaBuilder()->getColumnListing('payments');
        $defaultCols = ['invoice_id', 'participant_id', 'amount', 'status', 'payment_method', 'created_at', 'paid_at'];
        $requestedCols = $request->input('cols', $defaultCols);
        
        // Ensure some critical data are always fetched if selected
        $finalCols = array_intersect(array_unique(array_merge(['id', 'participant_id'], $requestedCols)), $allColumns);

        $perPageInput = $request->input('per_page', 20);
        if ($perPageInput === 'all') {
            $paymentsResult = $query->latest()->get($finalCols);
            $payments = new \Illuminate\Pagination\LengthAwarePaginator($paymentsResult, $paymentsResult->count(), 1000000);
        } else {
            $payments = $query->latest()->paginate((int)$perPageInput, $finalCols)->withQueryString();
        }

        return view('admin.payments', compact('payments', 'categories', 'startDate', 'endDate', 'allColumns', 'requestedCols'));
    }

    public function exportPayments(Request $request)
    {
        $query = Payment::with(['participant.category']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('participant', function ($q) use ($s) {
                $q->where('full_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('bib_number', 'like', "%$s%");
            });
        }
        if ($request->filled('category')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);

        // Date Range Filter (Default: Last 14 days based on latest payment date)
        $latestPaymentDate = Payment::latest('created_at')->value('created_at');
        $latestDate = $latestPaymentDate ? Carbon::parse($latestPaymentDate) : now();

        $startDate = $request->input('start_date', $latestDate->copy()->subDays(14)->format('Y-m-d\TH:i'));
        $endDate = $request->input('end_date', $latestDate->format('Y-m-d\TH:i'));

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfMinute());
        }

        $payments = $query->latest()->get();

        // Column selection logic matching payments() method
        $allColumns = DB::getSchemaBuilder()->getColumnListing('payments');
        $defaultCols = ['invoice_id', 'participant_id', 'amount', 'status', 'payment_method', 'created_at', 'paid_at'];
        $requestedCols = $request->input('cols', $defaultCols);

        // Ensure we only process valid columns (with participant_id as the special case)
        $requestedCols = array_intersect($requestedCols, array_merge(['participant_id'], $allColumns));

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=payments_' . date('Y-m-d_H-i') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $headerMap = [
            'invoice_id' => ['ID Invoice'],
            'participant_id' => ['Nama Peserta', 'BIB', 'Kategori'],
            'amount' => ['Amount'],
            'status' => ['Status'],
            'payment_method' => ['Method'],
            'paid_at' => ['Tgl Bayar'],
            'created_at' => ['Tgl Daftar'],
            'discount_amount' => ['Diskon'],
            'fee_amount' => ['Biaya'],
            'final_amount' => ['Total'],
            'gateway_id' => ['Gateway ID'],
            'order_id' => ['Order ID'],
            'payment_link' => ['Link'],
        ];

        $csvHeader = [];
        foreach ($requestedCols as $col) {
            if (isset($headerMap[$col])) {
                foreach ($headerMap[$col] as $lbl) {
                    $csvHeader[] = $lbl;
                }
            } else {
                $csvHeader[] = ucwords(str_replace('_', ' ', $col));
            }
        }

        $callback = function() use ($payments, $requestedCols, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $csvHeader);

            foreach ($payments as $pay) {
                $row = [];
                foreach ($requestedCols as $col) {
                    if ($col === 'participant_id') {
                        $row[] = $pay->participant->full_name ?? '-';
                        $row[] = $pay->participant->bib_number ?? '-';
                        $row[] = $pay->participant->category->name ?? '-';
                    } elseif ($col === 'created_at') {
                        $row[] = $pay->created_at ? $pay->created_at->format('Y-m-d H:i:s') : '-';
                    } elseif ($col === 'paid_at') {
                        $row[] = $pay->paid_at ? $pay->paid_at->format('Y-m-d H:i:s') : '-';
                    } elseif ($col === 'invoice_id') {
                        $row[] = $pay->invoice_id ?? $pay->mayar_invoice_id ?? '-';
                    } elseif ($col === 'amount') {
                        $row[] = $pay->amount;
                    } elseif ($col === 'discount_amount') {
                        $row[] = $pay->discount_amount;
                    } elseif ($col === 'fee_amount') {
                        $row[] = $pay->fee_amount;
                    } elseif ($col === 'final_amount') {
                        $row[] = $pay->final_amount ?? $pay->amount;
                    } else {
                        $val = $pay->{$col};
                        if (is_array($val) || is_object($val)) {
                            $row[] = json_encode($val);
                        } else {
                            $row[] = $val ?? '-';
                        }
                    }
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,failed,expired,refunded',
            'paid_date' => 'nullable|date',
            'paid_time' => 'nullable'
        ]);

        $oldStatus = $payment->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($payment, $newStatus, $request, $oldStatus) {
            $paidAt = null;
            if ($newStatus === 'paid') {
                if ($request->filled('paid_date') && $request->filled('paid_time')) {
                    $paidAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->paid_date . ' ' . $request->paid_time);
                } else {
                    $paidAt = now();
                }
            }

            $payment->update([
                'status' => $newStatus,
                'paid_at' => $paidAt,
            ]);

            // Update participant status
            $payment->participant->update([
                'payment_status' => $newStatus === 'paid' ? 'paid' : ($newStatus === 'pending' ? 'pending' : 'failed'),
            ]);

            // Handle promotion quota
            if ($newStatus === 'paid' && $oldStatus !== 'paid') {
                if ($payment->promotion_id) {
                    $promo = $payment->promotion;
                    $promo->increment('used_count');
                    if ($promo->quota !== null && $promo->quota > 0) {
                        $promo->decrement('quota');
                    }
                }

                // Send Confirmation Email
                try {
                    $participant = $payment->participant->load(['event', 'category', 'familyMembers']);
                    Mail::to($participant->email)->queue(new PaymentConfirmation($participant));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Email sending failed in manual update', ['error' => $e->getMessage()]);
                }
            }
        });

        return back()->with('success', 'Payment status updated successfully.');
    }

    public function emailBlast()
    {
        $event = Event::latest()->first();
        $paidCount = $event ? Participant::where('event_id', $event->id)->where('payment_status', 'paid')->count() : 0;
        
        // List templates from app/Mail
        $mailPath = app_path('Mail');
        $templates = [];
        if (file_exists($mailPath)) {
            $files = scandir($mailPath);
            foreach ($files as $file) {
                if (str_ends_with($file, '.php')) {
                    $templates[] = str_replace('.php', '', $file);
                }
            }
        }

        return view('admin.email-blast', compact('paidCount', 'templates'));
    }

    public function previewEmail(Request $request)
    {
        $template = $request->template ?? 'EventBlast';
        $class = "App\\Mail\\" . $template;

        if (!class_exists($class)) {
            return "Email class $class not found.";
        }

        $participant = Participant::with(['category', 'event', 'latestPayment', 'familyMembers'])->latest()->first();
        
        if (!$participant) {
            return "No participant found to generate preview.";
        }

        try {
            if ($template == 'EventBlast') {
                $subject = $request->subject ?? 'Sample Subject';
                $body = $request->body ?? 'Sample Message Body';
                return new $class($subject, $body, $participant->full_name);
            }
            
            // For other templates that might need Participant $p
            return new $class($participant);
        } catch (\Exception $e) {
            return "Error generating preview: " . $e->getMessage();
        }
    }

    public function sendEmailBlast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255', 
            'body' => 'required|string',
            'template' => 'required|string'
        ]);

        $event = Event::latest()->first();
        if ($event) {
            \App\Jobs\SendEmailBlast::dispatch(
                $event, 
                $request->subject, 
                $request->body, 
                $request->template
            );
        }
        return redirect()->route('admin.email-blast')->with('success', 'Email blast has been queued for delivery.');
    }

    public function sendSingleEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'template' => 'required|string'
        ]);

        try {
            $participant = Participant::where('email', $request->email)->first();
            $template = $request->template;
            $class = "App\\Mail\\" . $template;

            if (!class_exists($class)) {
                throw new \Exception("Template $template not found.");
            }

            if ($template == 'EventBlast') {
                $name = $participant ? $participant->full_name : $request->email;
                Mail::to($request->email)->send(
                    new $class($request->subject, $request->body, $name)
                );
            } else {
                if (!$participant) {
                    throw new \Exception("Participant with email " . $request->email . " not found for this template.");
                }
                Mail::to($request->email)->send(new $class($participant));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        return redirect()->route('admin.email-blast')->with('success', 'Email sent to ' . $request->email);
    }

    public function generateBibs()
    {
        $event = Event::with('categories')->latest()->first();
        if (!$event) return back()->with('success', 'No event found.');

        DB::transaction(function () use ($event) {
            foreach ($event->categories as $category) {
                $prefix = strtoupper(substr($category->slug, 0, 1));
                $participants = Participant::where('category_id', $category->id)
                    ->where('payment_status', 'paid')
                    ->whereNull('bib_number')
                    ->orderBy('created_at')
                    ->get();

                $lastBib = Participant::where('category_id', $category->id)
                    ->whereNotNull('bib_number')
                    ->orderByDesc('bib_number')
                    ->value('bib_number');

                $counter = $lastBib ? (int) substr($lastBib, 1) + 1 : 1;

                foreach ($participants as $p) {
                    $p->update(['bib_number' => $prefix . str_pad($counter++, 4, '0', STR_PAD_LEFT)]);
                }
            }
        });

        return back()->with('success', 'BIB numbers generated successfully.');
    }

    public function exportCsv()
    {
        $event = Event::latest()->first();
        $participants = Participant::with(['category', 'event', 'province', 'city', 'country', 'latestPayment', 'familyMembers.province', 'familyMembers.city', 'familyMembers.country'])
            ->where('event_id', optional($event)->id)
            ->orderBy('created_at')
            ->get();

        $pCols = DB::getSchemaBuilder()->getColumnListing('participants');
        $payCols = DB::getSchemaBuilder()->getColumnListing('payments');
        
        // Add relation names and member type for convenience
        $extraCols = ['member_type', 'parent_name', 'category_name', 'event_name', 'province_name', 'city_name', 'country_name'];
        $header = array_merge($pCols, $extraCols, array_map(fn($c) => 'payment_' . $c, $payCols));

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=participants_complete.csv'];
        $callback = function () use ($participants, $pCols, $payCols, $header) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $header);
            
            foreach ($participants as $p) {
                // 1. Participant Row
                $pRow = [];
                foreach ($pCols as $col) {
                    $val = $p->{$col};
                    if (in_array($col, ['identity_number', 'phone', 'emergency_contact_phone']) && !empty($val)) {
                        $val = '="' . $val . '"';
                    }
                    $pRow[] = $val;
                }
                
                $pRow[] = 'Primary'; // member_type
                $pRow[] = '-'; // parent_name
                $pRow[] = $p->category->name ?? '';
                $pRow[] = $p->event->name ?? '';
                $pRow[] = $p->province->name ?? '';
                $pRow[] = $p->city->name ?? '';
                $pRow[] = $p->country->name ?? '';
                
                $payment = $p->latestPayment;
                foreach ($payCols as $col) {
                    $val = $payment ? $payment->{$col} : '';
                    $pRow[] = is_array($val) || is_object($val) ? json_encode($val) : $val;
                }
                fputcsv($file, $pRow);

                // 2. Family Member Rows
                foreach ($p->familyMembers as $fm) {
                    $fmRow = [];
                    foreach ($pCols as $col) {
                        if (array_key_exists($col, $fm->getAttributes())) {
                            $val = $fm->{$col};
                            if (in_array($col, ['identity_number', 'phone', 'emergency_contact_phone']) && !empty($val)) {
                                $val = '="' . $val . '"';
                            }
                            $fmRow[] = $val;
                        } else {
                            // Inherit some fields from parent if they don't exist in fm
                            if (in_array($col, ['event_id', 'category_id', 'payment_status'])) {
                                $fmRow[] = $p->{$col};
                            } else {
                                $fmRow[] = '';
                            }
                        }
                    }

                    $fmRow[] = 'Family'; // member_type
                    $fmRow[] = $p->full_name; // parent_name
                    $fmRow[] = $p->category->name ?? '';
                    $fmRow[] = $p->event->name ?? '';
                    $fmRow[] = $fm->province->name ?? '';
                    $fmRow[] = $fm->city->name ?? '';
                    $fmRow[] = $fm->country->name ?? '';

                    // Payment columns from parent
                    foreach ($payCols as $col) {
                        $val = $payment ? $payment->{$col} : '';
                        $fmRow[] = is_array($val) || is_object($val) ? json_encode($val) : $val;
                    }
                    fputcsv($file, $fmRow);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function checkin()
    {
        return view('admin.checkin');
    }

    public function processCheckin(Request $request)
    {
        $request->validate(['participant_id' => 'required|string']);
        $participant = Participant::with('category')->find($request->participant_id);

        if (!$participant) return response()->json(['success' => false, 'message' => 'Participant not found.'], 404);
        if ($participant->payment_status !== 'paid') return response()->json(['success' => false, 'message' => 'Payment not confirmed yet.'], 422);
        if ($participant->checked_in) return response()->json(['success' => false, 'message' => 'Already checked in at ' . $participant->checked_in_at->format('H:i')], 422);

        $participant->update(['checked_in' => true, 'checked_in_at' => now()]);

        return response()->json(['success' => true, 'participant' => $participant->load('category')]);
    }

    public function syncBib(Request $request)
    {
        $request->validate([
            'bib_csvs' => 'required|array',
            'bib_csvs.*' => 'required|file|mimes:csv,txt'
        ]);

        $event = Event::latest()->first();
        if (!$event) {
            return back()->with('error', 'No active event found.');
        }

        $syncedCount = 0;
        $createdCount = 0;
        $processedParticipants = [];

        foreach ($request->file('bib_csvs') as $file) {
            if (!$file->isValid()) {
                continue;
            }
            $filePath = $file->getPathname();
            $rows = $this->parseCsv($filePath);

            $fileName = strtolower($file->getClientOriginalName() ?: $file->getFilename());
            $fileCategory = null;
            if (str_contains($fileName, '15k')) {
                $fileCategory = Category::where('slug', 'like', '%15k%')->first();
            } elseif (str_contains($fileName, '10k')) {
                $fileCategory = Category::where('slug', 'like', '%10k%')->first();
            } elseif (str_contains($fileName, '5k')) {
                $fileCategory = Category::where('slug', 'like', '%5k%')->first();
            }

            foreach ($rows as $row) {
                $rawName = isset($row['full_name']) ? trim($row['full_name']) : '';
                if (empty($rawName) || strtolower($rawName) === 'bod') {
                    continue; // Skip empty names or "BOD" placeholders
                }

                // Format the name as Title Case (Capital Camel)
                $fullName = ucwords(strtolower($rawName));

                // Extract fields
                $csvCategory = isset($row['category_id']) ? trim($row['category_id']) : '';
                $email = isset($row['email']) && trim($row['email']) !== '' ? trim($row['email']) : null;
                $jerseySize = isset($row['jersey_size']) ? trim($row['jersey_size']) : null;
                $bibNumber = isset($row['bib_number']) ? trim($row['bib_number']) : null;
                $checklist = isset($row['checklist']) ? trim($row['checklist']) : '0';
                $keterangan = isset($row['keterangan']) ? trim($row['keterangan']) : '';

                // Find category
                $category = $fileCategory;
                if (!$category && $csvCategory) {
                    if (strtolower($csvCategory) === '5k internal') {
                        $category = Category::where('slug', 'like', '%5k%')->first();
                    } else {
                        $category = Category::where('name', 'like', "%{$csvCategory}%")
                            ->orWhere('slug', 'like', "%" . \Illuminate\Support\Str::slug($csvCategory) . "%")
                            ->first();
                    }

                    if (!$category) {
                        $slug = \Illuminate\Support\Str::slug($csvCategory);
                        if (str_contains($slug, '5k') || str_contains($slug, 'internal')) {
                            $category = Category::where('slug', 'like', '%5k%')->first();
                        } elseif (str_contains($slug, '10k')) {
                            $category = Category::where('slug', 'like', '%10k%')->first();
                        } elseif (str_contains($slug, '15k')) {
                            $category = Category::where('slug', 'like', '%15k%')->first();
                        }
                    }
                }

                if (!$category) {
                    // Fallback to first available category
                    $category = Category::first();
                }

                if (!$category) {
                    continue; // No category found, skip
                }

                $is5k = str_contains(strtolower($category->slug), '5k');
                $isInternal = str_contains(strtolower($csvCategory), 'internal');
                $familyMember = null;
                $participant = null;
                $createNewFamilyMember = false;
                $familyMemberLeaderId = null;

                if ($is5k) {
                    if ($isInternal) {
                        // Search participant first
                        if ($email) {
                            $participant = Participant::where('email', $email)->first();
                        }
                        if (!$participant && $jerseySize) {
                            $participant = Participant::where('full_name', $fullName)
                                ->where('jersey_size', $jerseySize)
                                ->where('category_id', $category->id)
                                ->first();
                        }
                        if (!$participant) {
                            $participant = Participant::where('full_name', $fullName)
                                ->where('category_id', $category->id)
                                ->first();
                        }

                        // If participant is found, check if it's already processed in this import run
                        if ($participant && isset($processedParticipants[$participant->id])) {
                            // This participant is already processed, so this row is a double name!
                            // We must treat it as a family member.
                            $leaderId = $participant->id;
                            $participant = null; // unset participant to trigger family member logic

                            // Try to find existing family member under this leader
                            $familyMember = \App\Models\FamilyMember::where('participant_id', $leaderId)
                                ->where('full_name', $fullName)
                                ->first();
                            
                            if (!$familyMember && $jerseySize) {
                                $familyMember = \App\Models\FamilyMember::where('full_name', $fullName)
                                    ->where('jersey_size', $jerseySize)
                                    ->first();
                            }
                            if (!$familyMember) {
                                $familyMember = \App\Models\FamilyMember::where('full_name', $fullName)->first();
                            }
                            
                            // If no family member found, we will create a new family member under this leader
                            if (!$familyMember) {
                                $createNewFamilyMember = true;
                                $familyMemberLeaderId = $leaderId;
                            }
                        }
                    } else {
                        // For other categories, search family_members first
                        if ($email) {
                            $familyMember = \App\Models\FamilyMember::where('email', $email)->first();
                        }
                        if (!$familyMember && $jerseySize) {
                            $familyMember = \App\Models\FamilyMember::where('full_name', $fullName)
                                ->where('jersey_size', $jerseySize)
                                ->first();
                        }
                        if (!$familyMember) {
                            $familyMember = \App\Models\FamilyMember::where('full_name', $fullName)->first();
                        }

                        if (!$familyMember) {
                            if ($email) {
                                $participant = Participant::where('email', $email)->first();
                            }
                            if (!$participant && $jerseySize) {
                                $participant = Participant::where('full_name', $fullName)
                                    ->where('jersey_size', $jerseySize)
                                    ->where('category_id', $category->id)
                                    ->first();
                            }
                            if (!$participant) {
                                $participant = Participant::where('full_name', $fullName)
                                    ->where('category_id', $category->id)
                                    ->first();
                            }
                        }
                    }
                } else {
                    // For non-5k categories (10k, 15k), do NOT check family_members at all!
                    // Only search participants
                    if ($email) {
                        $participant = Participant::where('email', $email)->first();
                    }
                    if (!$participant && $jerseySize) {
                        $participant = Participant::where('full_name', $fullName)
                            ->where('jersey_size', $jerseySize)
                            ->where('category_id', $category->id)
                            ->first();
                    }
                    if (!$participant) {
                        $participant = Participant::where('full_name', $fullName)
                            ->where('category_id', $category->id)
                            ->first();
                    }

                    // If participant is found and already processed, this is a double name in a non-5k category.
                    // We treat it as a new participant to be created.
                    if ($participant && isset($processedParticipants[$participant->id])) {
                        $participant = null; // Unset to force new participant creation
                    }
                }

                $rpc = ($checklist === '1');

                if ($participant) {
                    // Sync existing participant only if paid
                    if ($participant->payment_status === 'paid') {
                        $updateData = [];
                        if ($bibNumber) {
                            $updateData['bib_number'] = $bibNumber;
                        }
                        if ($jerseySize && $participant->jersey_size !== $jerseySize) {
                            $updateData['jersey_size'] = $jerseySize;
                        }
                        if ($rpc !== $participant->rpc) {
                            $updateData['rpc'] = $rpc;
                        }
                        if ($rpc !== $participant->checked_in) {
                            $updateData['checked_in'] = $rpc;
                            $updateData['checked_in_at'] = $rpc ? now() : null;
                        }

                        if (!empty($updateData)) {
                            $participant->update($updateData);
                        }
                        $syncedCount++;

                        // Mark as processed
                        $processedParticipants[$participant->id] = true;
                    }
                } elseif ($familyMember) {
                    // Sync existing family member only if parent is paid
                    $parentPaid = $familyMember->participant && $familyMember->participant->payment_status === 'paid';
                    if ($parentPaid) {
                        $updateData = [];
                        if ($bibNumber) {
                            $updateData['bib_number'] = $bibNumber;
                        }
                        if ($jerseySize && $familyMember->jersey_size !== $jerseySize) {
                            $updateData['jersey_size'] = $jerseySize;
                        }
                        if ($rpc !== $familyMember->rpc) {
                            $updateData['rpc'] = $rpc;
                        }
                        if ($rpc !== $familyMember->checked_in) {
                            $updateData['checked_in'] = $rpc;
                            $updateData['checked_in_at'] = $rpc ? now() : null;
                        }

                        if (!empty($updateData)) {
                            $familyMember->update($updateData);
                        }
                        $syncedCount++;
                    }
                } elseif ($createNewFamilyMember) {
                    // Create new family member under the leader (for 5K Internal double names)
                    $keteranganLower = strtolower($keterangan);
                    
                    // Determine gender: putra -> male (M), putri -> female (F)
                    $gender = 'male'; // default
                    if (
                        str_contains($keteranganLower, 'putri') || 
                        str_contains($keteranganLower, 'female') || 
                        str_contains($keteranganLower, 'women') || 
                        preg_match('/\b(f|female)\b/', $keteranganLower)
                    ) {
                        $gender = 'female';
                    } elseif (
                        str_contains($keteranganLower, 'putra') || 
                        str_contains($keteranganLower, 'male') || 
                        str_contains($keteranganLower, 'men') || 
                        preg_match('/\b(m|male)\b/', $keteranganLower)
                    ) {
                        $gender = 'male';
                    }

                    // Determine age & date_of_birth: Open < 40, Master >= 40
                    $age = null;
                    if (str_contains($keteranganLower, 'open')) {
                        $age = rand(18, 39); // Open: < 40
                    } elseif (str_contains($keteranganLower, 'master')) {
                        $age = rand(40, 65); // Master: >= 40
                    } else {
                        $age = rand(20, 35); // Default
                    }
                    $dateOfBirth = now()->subYears($age)->startOfYear()->format('Y-m-d');

                    \App\Models\FamilyMember::create([
                        'participant_id' => $familyMemberLeaderId,
                        'role' => 'saudara',
                        'full_name' => $fullName,
                        'email' => $email ?: null,
                        'phone' => null, // empty / null since no phone column in CSV
                        'gender' => $gender,
                        'date_of_birth' => $dateOfBirth,
                        'age' => $age,
                        'jersey_size' => $jerseySize,
                        'bib_number' => $bibNumber,
                        'checked_in' => $rpc,
                        'checked_in_at' => $rpc ? now() : null,
                        'rpc' => $rpc,
                        'nationality' => 'Indonesia',
                    ]);
                    $createdCount++;
                } else {
                    // Create new participant with "data seadanya"
                    $keteranganLower = strtolower($keterangan);
                    
                    // Determine gender: putra -> male (M), putri -> female (F)
                    $gender = 'male'; // default
                    if (
                        str_contains($keteranganLower, 'putri') || 
                        str_contains($keteranganLower, 'female') || 
                        str_contains($keteranganLower, 'women') || 
                        preg_match('/\b(f|female)\b/', $keteranganLower)
                    ) {
                        $gender = 'female';
                    } elseif (
                        str_contains($keteranganLower, 'putra') || 
                        str_contains($keteranganLower, 'male') || 
                        str_contains($keteranganLower, 'men') || 
                        preg_match('/\b(m|male)\b/', $keteranganLower)
                    ) {
                        $gender = 'male';
                    }

                    // Determine age & date_of_birth: Open < 40, Master >= 40
                    $age = null;
                    if (str_contains($keteranganLower, 'open')) {
                        $age = rand(18, 39); // Open: < 40
                    } elseif (str_contains($keteranganLower, 'master')) {
                        $age = rand(40, 65); // Master: >= 40
                    } else {
                        $age = rand(20, 35); // Default
                    }
                    $dateOfBirth = now()->subYears($age)->startOfYear()->format('Y-m-d');

                    // Generate dummy email if not present
                    if (!$email) {
                        $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $fullName));
                        $email = $cleanName . '_' . ($bibNumber ?: rand(1000, 9999)) . '@example.com';
                    }

                    $newParticipant = Participant::create([
                        'event_id' => $event->id,
                        'category_id' => $category->id,
                        'full_name' => $fullName,
                        'email' => $email,
                        'phone' => '', // empty string since phone is NOT NULL but not in CSV
                        'gender' => $gender,
                        'date_of_birth' => $dateOfBirth,
                        'age' => $age,
                        'jersey_size' => $jerseySize,
                        'bib_number' => $bibNumber,
                        'payment_status' => 'paid',
                        'checked_in' => $rpc,
                        'checked_in_at' => $rpc ? now() : null,
                        'rpc' => $rpc,
                        'nationality' => 'Indonesia',
                    ]);
                    $createdCount++;

                    // Mark as processed
                    $processedParticipants[$newParticipant->id] = true;
                }
            }
        }

        // Clear public race results cache to update display
        \Illuminate\Support\Facades\Cache::forget('race_results_public_data');

        return back()->with('success', "Berhasil sinkronisasi BIB: {$syncedCount} peserta diperbarui, {$createdCount} peserta baru ditambahkan.");
    }

    private function parseCsv($filePath)
    {
        $rows = [];
        if (empty($filePath) || !file_exists($filePath)) {
            return $rows;
        }
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            if ($header) {
                $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
                $header = array_map('trim', $header);

                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($header) === count($data)) {
                        $rows[] = array_combine($header, $data);
                    } else {
                        $temp = [];
                        foreach ($header as $i => $colName) {
                            $temp[$colName] = isset($data[$i]) ? $data[$i] : null;
                        }
                        $rows[] = $temp;
                    }
                }
            }
            fclose($handle);
        }
        return $rows;
    }
}