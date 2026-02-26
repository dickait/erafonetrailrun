@extends('layouts.participant')
@section('title', 'My BIB')
@section('content')
@if($participant)
<div class="max-w-md mx-auto">
    @if($participant->hasBib())
    <div class="bg-dark-800 rounded-3xl border-2 border-forest-700/50 overflow-hidden shadow-2xl">
        <!-- BIB Card -->
        <div class="bg-gradient-to-br from-forest-800 to-dark-800 p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-2" style="background-color: {{ $participant->category->color ?? '#22c55e' }}"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-forest-700/20 via-transparent to-transparent"></div>
            <div class="relative z-10">
                <div class="text-xs text-forest-300/60 uppercase tracking-widest font-semibold mb-2">{{ $participant->event->name ?? '' }}</div>
                <div class="font-display font-black text-7xl tracking-tight mb-2" style="color: {{ $participant->category->color ?? '#22c55e' }}">
                    {{ $participant->bib_number }}
                </div>
                <div class="text-2xl font-display font-bold text-white mb-1">{{ $participant->full_name }}</div>
                <div class="text-sm text-forest-300/80 font-medium">{{ $participant->category->name ?? '' }}</div>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="p-6 text-center bg-dark-800">
            <div class="inline-block p-4 bg-white rounded-2xl mb-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($participant->id) }}&bgcolor=ffffff&color=0c1410" alt="QR Code" class="w-44 h-44">
            </div>
            <p class="text-xs text-gray-500">Show this QR code at the check-in counter</p>
            <p class="text-xs text-gray-600 font-mono mt-1">{{ $participant->id }}</p>
        </div>
    </div>
    @else
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-12 text-center">
        <svg class="w-20 h-20 mx-auto text-forest-700/40 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        <h3 class="font-display text-xl font-bold text-white mb-3">BIB Not Available Yet</h3>
        <p class="text-gray-400 text-sm max-w-xs mx-auto">
            @if($participant->payment_status !== 'paid')
            Please complete your payment first. BIB will be generated after payment confirmation.
            @else
            Your payment has been confirmed. BIB numbers will be assigned by the organizer. Please check back later.
            @endif
        </p>
    </div>
    @endif
</div>
@endif
@endsection
