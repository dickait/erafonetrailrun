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
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class RegistrationController extends Controller
{
    public function create()
    {
        $event = Event::with('categories')->where('is_active', true)->latest('event_date')->first();

        if (!$event || !$event->isRegistrationOpen()) {
            return redirect()->route('home')->with('error', 'Registration is currently closed.');
        }

        $categories = $event->categories;
        $countries = Cache::remember('countries_list', 86400, function () {
            return Country::orderBy('name')->get();
        });

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
        $appliedPromotionId = null;

        // 1. Priority check for Manual Discount Code
        if ($request->filled('discount_code')) {
            $promo = \App\Models\Promotion::where('code', strtoupper($request->discount_code))->first();
            if ($promo && $promo->isValid()) {
                if ($promo->discount_type == 'fixed') {
                    $discountAmount = (float) $promo->discount_value;
                } else {
                    $discountAmount = $baseAmount * ((float) $promo->discount_value / 100);
                }
                $appliedPromotionId = $promo->id;
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

        \Illuminate\Support\Facades\Log::info('Registration calculation', [
            'baseAmount' => $baseAmount,
            'discountAmount' => $discountAmount,
            'finalAmount' => $finalAmount,
            'isFree' => $isFree,
            'appliedPromotionId' => $appliedPromotionId,
            'multiplier' => $multiplier
        ]);

        $participant = DB::transaction(function () use ($validated, $request, $event, $category, $isFree, $appliedPromotionId) {
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
                'promotion_id' => $appliedPromotionId,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'paid',
                'invoice_id' => 'INV-' . strtoupper(Str::random(10)),
                'payment_link' => null,
                'payment_method' => 'discount_full',
                'paid_at' => now(),
            ]);

            // Send Email Confirmation
            try {
                $participant->load(['familyMembers', 'latestPayment']);
                Mail::to($participant->email)->queue(new RegistrationConfirmation($participant));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Email sending failed for free registration', ['error' => $e->getMessage()]);
            }

            return redirect()->route('registration.payment', ['email' => $participant->email])
                ->with('success', 'Registration successful! Your registration has been confirmed.');
        }

        // Handle Manual Payment Mode
        if (config('services.payment') === 'manual') {
            // Add 3 random digits (100-999)
            $randomDigits = rand(100, 999);
            $finalAmountWithRandom = $finalAmount + $randomDigits;

            Payment::create([
                'participant_id' => $participant->id,
                'order_id' => $orderId,
                'amount' => $baseAmount,
                'promotion_id' => $appliedPromotionId,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmountWithRandom,
                'status' => 'pending',
                'invoice_id' => 'INV-' . strtoupper(Str::random(10)),
                'gateway_id' => null,
                'payment_link' => null,
                'payment_method' => 'manual',
            ]);

            // Send Email Confirmation
            try {
                $participant->load(['familyMembers', 'latestPayment']);
                Mail::to($participant->email)->queue(new RegistrationConfirmation($participant));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Email sending failed for manual registration', ['error' => $e->getMessage()]);
            }

            return redirect()->route('registration.payment', ['email' => $participant->email])
                ->with('success', 'Registration successful! Please complete your manual payment via QRIS.');
        }

        // Call Mayar API to create payment request
        $paymentLink = '#';
        $invoiceId = 'INV-' . strtoupper(Str::random(10));

        // Handle Midtrans Payment Mode
        if (config('services.payment') === 'midtrans') {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId . '-' . time(),
                    'gross_amount' => (int) $finalAmount,
                ],
                'customer_details' => [
                    'first_name' => $participant->full_name,
                    'email' => $participant->email,
                    'phone' => $participant->phone,
                ],
                'item_details' => [
                    [
                        'id' => $category->id,
                        'price' => (int) $finalAmount,
                        'quantity' => 1,
                        'name' => $category->name,
                    ]
                ],
                'callbacks' => [
                    'finish' => route('registration.payment', ['email' => $participant->email]),
                    'unfinish' => route('registration.payment', ['email' => $participant->email]),
                    'error' => route('registration.payment', ['email' => $participant->email]),
                ]
            ];

            try {
                $paymentUrl = Snap::createTransaction($params)->redirect_url;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Midtrans Link Creation Failed', ['error' => $e->getMessage()]);
                $paymentUrl = route('registration.payment', ['email' => $participant->email]);
            }

            Payment::create([
                'participant_id' => $participant->id,
                'order_id' => $orderId,
                'amount' => $baseAmount,
                'promotion_id' => $appliedPromotionId,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'invoice_id' => $invoiceId,
                'payment_link' => $paymentUrl,
                'payment_method' => 'midtrans',
            ]);

            // Send Email Confirmation
            try {
                $participant->load(['familyMembers', 'latestPayment']);
                Mail::to($participant->email)->queue(new RegistrationConfirmation($participant));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Email sending failed for midtrans registration', ['error' => $e->getMessage()]);
            }

            return redirect()->route('registration.payment', ['email' => $participant->email])
                ->with('success', 'Registration successful! Please complete your payment via Midtrans.');
        }

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
                $gatewayId = $mayarData['data']['id'] ?? null;

                \Illuminate\Support\Facades\Log::info('Mayar payment created', [
                    'participant_id' => $participant->id,
                    'order_id' => $orderId,
                    'invoice_id' => $invoiceId,
                    'gateway_id' => $gatewayId,
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
            'promotion_id' => $appliedPromotionId,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'status' => 'pending',
            'invoice_id' => $invoiceId,
            'gateway_id' => $gatewayId ?? null,
            'payment_link' => $paymentLink,
        ]);

        // Send Email Confirmation
        try {
            $participant->load(['familyMembers', 'latestPayment']);
            Mail::to($participant->email)->queue(new RegistrationConfirmation($participant));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email sending failed for gateway registration', ['error' => $e->getMessage()]);
        }

        // Clear captcha from session after successful validation to ensure new attempt uses new captcha
        $request->session()->forget('captcha');

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
                $participant = Participant::with(['category', 'latestPayment.promotion', 'event', 'familyMembers'])
                    ->where('event_id', $event->id)
                    ->where('email', $email)
                    ->first();

                if (!$participant) {
                    $participant = Participant::with(['category', 'latestPayment.promotion', 'event', 'familyMembers'])
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
                // Clear captcha from session after successful validation
                $request->session()->forget('captcha');
            }

            $event = Event::where('is_active', true)->latest('event_date')->first();
            if (!$event) {
                $event = Event::latest('event_date')->first();
            }

            if ($event) {
                $participant = Participant::with(['category', 'latestPayment.promotion', 'event', 'familyMembers'])
                    ->where('event_id', $event->id)
                    ->where('email', $request->email)
                    ->first();
            }
        }

        return view('public.status', compact('participant'));
    }

    public function getProvinces(Request $request)
    {
        $provinces = Cache::remember('provinces_list', 86400, function () {
            return Province::orderBy('name')->get();
        });
        return response()->json($provinces);
    }

    public function getCities(Request $request)
    {
        $provinceId = $request->province_id;
        $cities = Cache::remember("cities_list_{$provinceId}", 86400, function () use ($provinceId) {
            return City::where('province_id', $provinceId)->orderBy('name')->get();
        });
        return response()->json($cities);
    }
}
