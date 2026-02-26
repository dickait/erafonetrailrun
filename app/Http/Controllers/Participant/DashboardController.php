<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $participant = Participant::with(['event', 'category', 'latestPayment'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('participant.dashboard', compact('participant'));
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $participant = Participant::with(['event', 'category', 'country', 'province', 'city'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('participant.profile', compact('participant'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $participant = Participant::where('user_id', $user->id)->latest()->firstOrFail();

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'blood_type' => 'nullable|string|max:3',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'jersey_size' => 'nullable|string|max:5',
            'community' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|string|max:1000',
        ]);

        $participant->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function paymentStatus(Request $request)
    {
        $user = $request->user();
        $participant = Participant::with(['event', 'category', 'payments'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('participant.payment', compact('participant'));
    }

    public function bib(Request $request)
    {
        $user = $request->user();
        $participant = Participant::with(['event', 'category'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('participant.bib', compact('participant'));
    }
}
