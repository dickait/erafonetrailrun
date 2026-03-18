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
            'total_revenue' => Payment::whereHas('participant', fn($q) => $q->where('event_id', $event->id))->where('status', 'paid')->sum('amount'),
            'categories' => $cats,
        ];

        return view('admin.dashboard', compact('event', 'stats'));
    }

    public function participants(Request $request)
    {
        $event = Event::latest()->first();
        $categories = $event ? $event->categories : collect();

        $query = Participant::with(['category', 'event'])->where('event_id', optional($event)->id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('bib_number', 'like', "%$s%");
            });
        }
        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);

        $participants = $query->latest()->paginate(20)->withQueryString();

        return view('admin.participants', compact('participants', 'categories'));
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
                  ->orWhere('email', 'like', "%$s%");
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

        $payments = $query->latest()->paginate(20)->withQueryString();

        return view('admin.payments', compact('payments', 'categories'));
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
                    $paidAt = Carbon::createFromFormat('Y-m-d H:i', $request->paid_date . ' ' . $request->paid_time);
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
                    Mail::to($payment->participant->email)->queue(new PaymentConfirmation($payment->participant));
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
        $participants = Participant::with(['category', 'event'])
            ->where('event_id', optional($event)->id)
            ->orderBy('created_at')
            ->get();

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=participants.csv'];
        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['BIB', 'Name', 'Email', 'Phone', 'Gender', 'DOB', 'Category', 'Payment Status', 'Jersey Size', 'Community', 'Registered At']);
            foreach ($participants as $p) {
                fputcsv($file, [$p->bib_number, $p->full_name, $p->email, $p->phone, $p->gender, $p->date_of_birth?->format('Y-m-d'), $p->category->name ?? '', $p->payment_status, $p->jersey_size, $p->community, $p->created_at->format('Y-m-d H:i')]);
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