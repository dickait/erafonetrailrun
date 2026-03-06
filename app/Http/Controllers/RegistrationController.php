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
            'bib_name' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
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
            'jersey_size' => 'nullable|string|max:5',
            'community' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|string|max:1000',
            'agreement_1' => 'accepted',
            'agreement_2' => 'accepted',
            'agreement_3' => 'accepted',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'agreement_1.accepted' => 'You must accept the terms and conditions.',
            'agreement_2.accepted' => 'You must accept the terms and conditions.',
            'agreement_3.accepted' => 'You must accept the terms and conditions.',
        ]);

        // Verify reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip()
        ]);

        if (!$response->json('success')) {
            return back()->withInput()->withErrors(['g-recaptcha-response' => 'Failed to verify reCAPTCHA. Please try again.']);
        }

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

        $participant = DB::transaction(function () use ($validated, $request, $event, $category) {
            $participantInfo = $validated;

            // If it's family registration, we use the first participant data as the main one 
            // if form inputs are not accurately mapped
            if ($request->has('participants') && is_array($request->participants) && count($request->participants) > 0) {
                $mainData = $request->participants[0];
                // map needed fields just in case
                foreach (['full_name', 'email', 'phone', 'gender', 'date_of_birth', 'identity_number', 'blood_type', 'jersey_size', 'bib_name'] as $field) {
                    if (isset($mainData[$field])) {
                        $participantInfo[$field] = $mainData[$field];
                    }
                }
            }

            $participant = Participant::create([
                ...$participantInfo,
                'event_id' => $event->id,
                'payment_status' => 'pending',
            ]);

            // Create family members
            if ($request->has('participants') && is_array($request->participants) && count($request->participants) > 1) {
                // Skip the first one as it's the leader
                $membersData = array_slice($request->participants, 1);
                foreach ($membersData as $member) {
                    $participant->familyMembers()->create([
                        'full_name' => $member['full_name'] ?? '',
                        'bib_name' => $member['bib_name'] ?? '',
                        'email' => $member['email'] ?? null,
                        'phone' => $member['phone'] ?? null,
                        'gender' => $member['gender'] ?? 'male',
                        'date_of_birth' => $member['date_of_birth'] ?? now()->format('Y-m-d'),
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

            return $participant;
        });

        // Call Mayar API to create payment request
        // Calculate amount
        $multiplier = 1;
        if ($request->has('participants') && is_array($request->participants) && count($request->participants) > 0) {
            $multiplier = count($request->participants);
        }
        $amount = $category->getCurrentPrice() * $multiplier;
        $paymentLink = '#';
        $invoiceId = 'INV-' . strtoupper(Str::random(10));

        try {
            $mayarResponse = Http::withToken(config('services.mayar.api_key'))
                ->post(config('services.mayar.api_url') . '/payment/create', [
                    'name' => $participant->full_name,
                    'email' => $participant->email,
                    'amount' => (int) $amount,
                    'mobile' => $participant->phone,
                    'description' => "Registration {$event->name} - {$category->name}",
                    'redirectUrl' => route('registration.payment', ['email' => $participant->email]),
                ]);

            if ($mayarResponse->successful()) {
                $mayarData = $mayarResponse->json();
                $paymentLink = $mayarData['data']['link'] ?? $mayarData['data']['paymentLink'] ?? '#';
                $invoiceId = $mayarData['data']['id'] ?? $invoiceId;

                \Illuminate\Support\Facades\Log::info('Mayar payment created', [
                    'participant_id' => $participant->id,
                    'invoice_id' => $invoiceId,
                    'payment_link' => $paymentLink,
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
            'amount' => $amount,
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
            if ($event) {
                $participant = Participant::with(['category', 'latestPayment', 'event', 'familyMembers'])
                    ->where('event_id', $event->id)
                    ->where('email', $request->email)
                    ->first();
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
            $event = Event::where('is_active', true)->latest('event_date')->first();
            if ($event) {
                $participant = Participant::with(['category', 'latestPayment', 'event', 'familyMembers'])
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
