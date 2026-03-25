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

        $stats = [
            'total_participants' => Participant::where('event_id', $event->id)->count(),
            'paid_participants' => Participant::where('event_id', $event->id)->where('payment_status', 'paid')->count(),
            'pending_participants' => Participant::where('event_id', $event->id)->where('payment_status', 'pending')->count(),
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
            $requestedCols = ['full_name', 'email', 'phone', 'age', 'shirt_size', 'payment_status', 'created_at'];
        }

        $finalCols = array_unique(array_merge($essentialCols, $requestedCols));
        // Filter out columns that don't exist in DB
        $finalCols = array_intersect($finalCols, $allColumns);

        $query = Participant::with(['category', 'event'])
            ->where('event_id', optional($event)->id)
            ->select($finalCols);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                // Only search in selected columns or core identification columns
                $q->where('full_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('bib_number', 'like', "%$s%");
            });
        }
        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);

        // Date Range Filter (Default: Last 14 days)
        $now = now();
        $startDate = $request->input('start_date', $now->copy()->subDays(14)->format('Y-m-d\TH:i'));
        $endDate = $request->input('end_date', $now->format('Y-m-d\TH:i'));

        $query->where('created_at', '>=', $startDate);
        $query->where('created_at', '<=', $endDate);

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
        $query = Participant::with(['category'])->where('event_id', optional($event)->id);

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
        $requestedCols = $request->input('cols', ['full_name', 'email', 'phone', 'age', 'shirt_size', 'payment_status', 'created_at']);
        $finalCols = array_intersect(array_unique(array_merge(['id'], $requestedCols)), $allColumns);

        $participants = $query->latest()->get($finalCols);

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=participants_' . date('Y-m-d_H-i') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($participants, $requestedCols) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, $requestedCols);

            foreach ($participants as $p) {
                $row = [];
                foreach ($requestedCols as $col) {
                    if ($col == 'category_id') {
                        $row[] = $p->category->name ?? '-';
                    } elseif ($col == 'created_at') {
                        $row[] = $p->created_at->format('Y-m-d H:i:s');
                    } else {
                        $row[] = $p->{$col};
                    }
                }
                fputcsv($file, $row);
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

        $startDate = $request->input('start_date', now()->subDays(14)->startOfDay()->toDateTimeString());
        $endDate = $request->input('end_date', now()->endOfDay()->toDateTimeString());

        if ($request->filled('start_date')) $query->where('created_at', '>=', $request->start_date);
        else $query->where('created_at', '>=', $startDate);

        if ($request->filled('end_date')) $query->where('created_at', '<=', $request->end_date);
        else $query->where('created_at', '<=', $endDate);

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
        if ($request->filled('start_date')) $query->where('created_at', '>=', $request->start_date);
        if ($request->filled('end_date')) $query->where('created_at', '<=', $request->end_date);

        $payments = $query->latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=payments_' . date('Y-m-d_H-i') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID Invoice', 'Nama Peserta', 'BIB', 'Kategori', 'Amount', 'Status', 'Method', 'Tgl Daftar', 'Tgl Bayar']);

            foreach ($payments as $pay) {
                fputcsv($file, [
                    $pay->invoice_id ?? $pay->mayar_invoice_id ?? '-',
                    $pay->participant->full_name ?? '-',
                    $pay->participant->bib_number ?? '-',
                    $pay->participant->category->name ?? '-',
                    $pay->final_amount ?? $pay->amount,
                    $pay->status,
                    $pay->payment_method ?? '-',
                    $pay->created_at->format('Y-m-d H:i:s'),
                    $pay->paid_at ? $pay->paid_at->format('Y-m-d H:i:s') : '-'
                ]);
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
        return view('admin.email-blast', compact('paidCount'));
    }

    public function sendEmailBlast(Request $request)
    {
        $request->validate(['subject' => 'required|string|max:255', 'body' => 'required|string']);
        $event = Event::latest()->first();
        if ($event) {
            SendEmailBlast::dispatch($event, $request->subject, $request->body);
        }
        return redirect()->route('admin.email-blast')->with('success', 'Email blast has been queued for delivery.');
    }

    public function sendSingleEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        try {
            // Find participant to get name, or just use email if not found
            $participant = Participant::where('email', $request->email)->first();
            $name = $participant ? $participant->full_name : $request->email;

            Mail::to($request->email)->send(
                new \App\Mail\EventBlast($request->subject, $request->body, $name)
            );
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
        $participants = Participant::with(['category', 'event', 'province', 'city', 'country', 'latestPayment'])
            ->where('event_id', optional($event)->id)
            ->orderBy('created_at')
            ->get();

        $pCols = DB::getSchemaBuilder()->getColumnListing('participants');
        $payCols = DB::getSchemaBuilder()->getColumnListing('payments');
        
        // Add relation names for convenience
        $extraCols = ['category_name', 'event_name', 'province_name', 'city_name', 'country_name'];
        $header = array_merge($pCols, $extraCols, array_map(fn($c) => 'payment_' . $c, $payCols));

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=participants_complete.csv'];
        $callback = function () use ($participants, $pCols, $payCols, $header) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);
            
            foreach ($participants as $p) {
                $row = [];
                // Participant columns
                foreach ($pCols as $col) {
                    $row[] = $p->{$col};
                }
                
                // Extra relations
                $row[] = $p->category->name ?? '';
                $row[] = $p->event->name ?? '';
                $row[] = $p->province->name ?? '';
                $row[] = $p->city->name ?? '';
                $row[] = $p->country->name ?? '';
                
                // Payment columns
                $payment = $p->latestPayment;
                foreach ($payCols as $col) {
                    $val = $payment ? $payment->{$col} : '';
                    $row[] = is_array($val) || is_object($val) ? json_encode($val) : $val;
                }
                
                fputcsv($file, $row);
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
}