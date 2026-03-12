<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function create()
    {
        $event = Event::with('categories')->where('is_active', true)->latest('event_date')->first();

        if (!$event || !$event->isRegistrationOpen()) {
            return redirect()->route('home')->with('error', 'Registration is currently closed.');
        }

        $categories = $event->categories;
        $countries = Country::orderBy('name')->get();

        return view('public.register', compact('event', 'categories', 'countries'));
    }

    public function store(Request $request)
    {
        $event = Event::where('is_active', true)->latest('event_date')->first();

        if (!$event || !$event->isRegistrationOpen()) {
            return redirect()->route('home')->with('error', 'Registration is currently closed.');
        }

        if ($request->has('participants') && is_array($request->participants) && count($request->participants) > 0) {
            $request->merge($request->participants[0]);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:20',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'identity_number' => 'required|string|max:30',
            'nationality' => 'required|string|max:100',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:regencies,id',
            'address' => 'nullable|string|max:255',
            'blood_type' => 'nullable|string|max:3',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'jersey_size' => 'nullable|string|max:20',
            'community' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|string|max:1000',
            'agreement_1' => 'accepted',
            'agreement_2' => 'accepted',
            'agreement_3' => 'accepted',
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => 'Please enter the captcha code.',
            'captcha.captcha' => 'Invalid captcha code.',
            'agreement_1.accepted' => 'You must accept the terms and conditions.',
            'agreement_2.accepted' => 'You must accept the terms and conditions.',
            'agreement_3.accepted' => 'You must accept the terms and conditions.',
        ]);

        // Check duplicate registration
        $existing = Participant::where('event_id', $event->id)
            ->where('email', $validated['email'])
            ->first();

        if ($existing) {
            return back()->withInput()
                ->withErrors(['email' => 'This email is already registered for this event.']);
        }

        // Check quota
        $category = Category::findOrFail($validated['category_id']);
        if ($category->getRemainingQuota() <= 0) {
            return back()->withInput()
                ->withErrors(['category_id' => 'This category is full. Please select another category.']);
        }

        // Calculate amount
        $multiplier = 1;
        if ($request->has('participants') && is_array($request->participants) && count($request->participants) > 0) {
            $multiplier = count($request->participants);
        }

        // Calculate base price
        $baseAmount = $category->getBasePrice($multiplier);
        
        $discountAmount = 0;
        $discountCodeId = null;
        $appliedPromotionId = null;

        // 1. Priority check for Manual Discount Code
        if ($request->filled('discount_code')) {
            $dc = \App\Models\DiscountCode::where('code', strtoupper($request->discount_code))->first();
            if ($dc && $dc->isValid()) {
                $promotion = $dc->promotion;
                if ($promotion->discount_type == 'fixed') {
                    $discountAmount = (float) $promotion->discount_value;
                } else {
                    $discountAmount = $baseAmount * ((float) $promotion->discount_value / 100);
                }
                $discountCodeId = $dc->id;
                $appliedPromotionId = $promotion->id;
            } else {
                return back()->withInput()->withErrors(['discount_code' => 'Invalid or expired discount code.']);
            }
        } 
        // 2. If no discount code is provided, check for automatic Early Bird
        else {
            $earlyBirdPromo = $category->getActivePromotion('earlybird');
            if ($earlyBirdPromo) {
                if ($earlyBirdPromo->discount_type == 'fixed') {
                    $discountAmount = (float) $earlyBirdPromo->discount_value;
                } else {
                    $discountAmount = $baseAmount * ((float) $earlyBirdPromo->discount_value / 100);
                }
                $appliedPromotionId = $earlyBirdPromo->id;
            }
        }

        $finalAmount = max(0, $baseAmount - $discountAmount);
        $isFree = $finalAmount <= 0;

        $participant = DB::transaction(function () use ($validated, $request, $event, $category, $isFree, $appliedPromotionId, $discountCodeId) {
            $participantInfo = $validated;

            // If it's family registration, we use the first participant data as the main one 
            // if form inputs are not accurately mapped
            if ($request->has('participants') && is_array($request->participants) && count($request->participants) > 0) {
                $mainData = $request->participants[0];
                // map needed fields just in case
                foreach (['role', 'full_name', 'email', 'phone', 'gender', 'date_of_birth', 'age', 'identity_number', 'blood_type', 'jersey_size'] as $field) {
                    if (isset($mainData[$field])) {
                        $participantInfo[$field] = $mainData[$field];
                    }
                }
            }

            $participant = Participant::create([
                ...$participantInfo,
                'event_id' => $event->id,
                'payment_status' => $isFree ? 'paid' : 'pending',
            ]);

            // Create family members
            if ($request->has('participants') && is_array($request->participants) && count($request->participants) > 1) {
                // Skip the first one as it's the leader
                $membersData = array_slice($request->participants, 1);
                foreach ($membersData as $member) {
                    $participant->familyMembers()->create([
                        'role' => $member['role'] ?? null,
                        'full_name' => $member['full_name'] ?? '',
                        'email' => $member['email'] ?? null,
                        'phone' => $member['phone'] ?? null,
                        'gender' => $member['gender'] ?? 'male',
                        'date_of_birth' => $member['date_of_birth'] ?? now()->format('Y-m-d'),
                        'age' => $member['age'] ?? null,
                        'identity_number' => $member['identity_number'] ?? null,

                        'nationality' => $member['nationality'] ?? 'Indonesia',
                        'country_id' => $member['country_id'] ?? null,
                        'province_id' => $member['province_id'] ?? null,
                        'city_id' => $member['city_id'] ?? null,
                        'address' => $member['address'] ?? null,

                        'blood_type' => $member['blood_type'] ?? null,
                        'emergency_contact_name' => $member['emergency_contact_name'] ?? null,
                        'emergency_contact_phone' => $member['emergency_contact_phone'] ?? null,
                        'jersey_size' => $member['jersey_size'] ?? null,
                        'community' => $member['community'] ?? null,
                        'medical_conditions' => $member['medical_conditions'] ?? null,
                    ]);
                }
            }

            // If free, handle quota/used_count immediately
            if ($isFree) {
                if ($appliedPromotionId) {
                    $promo = \App\Models\Promotion::find($appliedPromotionId);
                    if ($promo) {
                        $promo->increment('used_count');
                        if ($promo->quota !== null && $promo->quota > 0) {
                            $promo->decrement('quota');
                        }
                    }
                }
                if ($discountCodeId) {
                    $dc = \App\Models\DiscountCode::find($discountCodeId);
                    if ($dc) {
                        $dc->increment('used_count');
                        if ($dc->usage_limit !== null && $dc->usage_limit > 0) {
                            $dc->decrement('usage_limit');
                        }
                    }
                }
            }

            return $participant;
        });

        // Generate human-readable Order ID
        $orderId = Payment::generateOrderId();

        if ($isFree) {
            Payment::create([
                'participant_id' => $participant->id,
                'order_id' => $orderId,
                'amount' => $baseAmount,
                'discount_code_id' => $discountCodeId,
                'promotion_id' => $appliedPromotionId,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'paid',
                'invoice_id' => 'FREE-' . strtoupper(Str::random(10)),
                'payment_link' => null,
                'payment_method' => 'discount_full',
                'paid_at' => now(),
            ]);

            return redirect()->route('registration.payment', ['email' => $participant->email])
                ->with('success', 'Registration successful! Your registration has been confirmed.');
        }

        // Call Mayar API to create payment request
        $paymentLink = '#';
        $invoiceId = 'INV-' . strtoupper(Str::random(10));

        $mobile = $participant->phone;
        if (strlen($mobile) < 10) {
            $mobile = str_pad($mobile, 10, '0', STR_PAD_RIGHT);
        }

        try {
            $mayarResponse = Http::withToken(config('services.mayar.api_key'))
                ->post(config('services.mayar.api_url') . '/payment/create', [
                    'name' => $participant->full_name,
                    'email' => $participant->email,
                    'amount' => (int) $finalAmount,
                    'mobile' => $mobile,
                    'description' => "Order #{$orderId} - {$event->name} - {$category->name}",
                    'redirectUrl' => route('registration.payment', ['email' => $participant->email]),
                ]);

            if ($mayarResponse->successful()) {
                $mayarData = $mayarResponse->json();
                $paymentLink = $mayarData['data']['link'] ?? $mayarData['data']['paymentLink'] ?? '#';
                $invoiceId = $mayarData['data']['id'] ?? $invoiceId;

                \Illuminate\Support\Facades\Log::info('Mayar payment created', [
                    'participant_id' => $participant->id,
                    'order_id' => $orderId,
                    'invoice_id' => $invoiceId,
                    'payment_link' => $paymentLink,
                    'amount' => $finalAmount
                ]);
            } else {
                \Illuminate\Support\Facades\Log::error('Mayar API error', [
                    'status' => $mayarResponse->status(),
                    'body' => $mayarResponse->body(),
                    'participant_id' => $participant->id,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mayar API exception', [
                'message' => $e->getMessage(),
                'participant_id' => $participant->id,
            ]);
        }

        // Create payment record with the Mayar link
        Payment::create([
            'participant_id' => $participant->id,
            'order_id' => $orderId,
            'amount' => $baseAmount,
            'discount_code_id' => $discountCodeId,
            'promotion_id' => $appliedPromotionId,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'status' => 'pending',
            'invoice_id' => $invoiceId,
            'payment_link' => $paymentLink,
        ]);

        return redirect()->route('registration.payment', ['email' => $participant->email])
            ->with('success', 'Registration successful! Please complete your payment.');
    }

    public function payment(Request $request)
    {
        $participant = null;

        if ($request->has('email')) {
            $event = Event::where('is_active', true)->latest('event_date')->first();
            if (!$event) {
                $event = Event::latest('event_date')->first();
            }

            if ($event && $request->email) {
                $email = trim($request->email);
                $participant = Participant::with(['category', 'latestPayment.promotion', 'latestPayment.discountCode', 'event', 'familyMembers'])
                    ->where('event_id', $event->id)
                    ->where('email', $email)
                    ->first();

                if (!$participant) {
                    $participant = Participant::with(['category', 'latestPayment.promotion', 'latestPayment.discountCode', 'event', 'familyMembers'])
                        ->where('email', $email)
                        ->latest()
                        ->first();
                }
            }
        }

        if (!$participant) {
            return redirect()->route('home')->with('error', 'Peserta tidak ditemukan.');
        }

        return view('public.payment', compact('participant'));
    }

    public function checkStatus(Request $request)
    {
        $participant = null;

        if ($request->has('email')) {
            if ($request->isMethod('post')) {
                $request->validate([
                    'email' => 'required|email',
                    'captcha' => 'required|captcha',
                ], [
                    'captcha.required' => 'Please enter the captcha code.',
                    'captcha.captcha' => 'Invalid captcha code.',
                ]);
            }

            $event = Event::where('is_active', true)->latest('event_date')->first();
            if (!$event) {
                $event = Event::latest('event_date')->first();
            }

            if ($event) {
                $participant = Participant::with(['category', 'latestPayment.promotion', 'latestPayment.discountCode', 'event', 'familyMembers'])
                    ->where('event_id', $event->id)
                    ->where('email', $request->email)
                    ->first();
            }
        }

        return view('public.status', compact('participant'));
    }

    public function getProvinces(Request $request)
    {
        $provinces = Province::orderBy('name')->get();
        return response()->json($provinces);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('province_id', $request->province_id)->orderBy('name')->get();
        return response()->json($cities);
    }
}
