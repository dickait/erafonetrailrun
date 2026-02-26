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

        $participant = DB::transaction(function () use ($validated, $event, $category) {
            $participant = Participant::create([
                ...$validated,
                'event_id' => $event->id,
                'payment_status' => 'pending',
            ]);

            // Create payment record
            $amount = $category->getCurrentPrice();
            Payment::create([
                'participant_id' => $participant->id,
                'amount' => $amount,
                'status' => 'pending',
                'invoice_id' => 'INV-' . strtoupper(Str::random(10)),
                'payment_link' => '#', // Would be replaced with Mayar link
            ]);

            return $participant;
        });

        // In production, redirect to Mayar payment link
        // For now, redirect to payment page
        return redirect()->route('registration.payment', ['email' => $participant->email])
            ->with('success', 'Registration successful! Please complete your payment.');
    }

    public function payment(Request $request)
    {
        $participant = null;

        if ($request->has('email')) {
            $event = Event::where('is_active', true)->latest('event_date')->first();
            if ($event) {
                $participant = Participant::with(['category', 'latestPayment', 'event'])
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
                $participant = Participant::with(['category', 'latestPayment', 'event'])
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
