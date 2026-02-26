@extends('layouts.participant')
@section('title', __('messages.part_bib') . ' - Erafone Trail Run')
@section('content')
<div class="mb-8">
    <h1 class="font-display font-bold text-2xl text-white mb-2">{{ __('messages.part_bib') }}</h1>
</div>

@if(!$participant)
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-8 text-center text-gray-400">
    {{ __('messages.part_no_registration') }}
</div>
@else
<div class="max-w-md mx-auto relative group">
    @if(!$participant->bib_number)
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-amber-500/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <h4 class="text-xl font-display font-bold text-white mb-2">{{ __('messages.part_bib_not_available') }}</h4>
        @if($participant->payment_status !== 'paid')
        <p class="text-gray-400 text-sm">{{ __('messages.part_bib_pay_first') }}</p>
        @else
        <p class="text-gray-400 text-sm">{{ __('messages.part_bib_assigned_later') }}</p>
        @endif
    </div>
    @else
    @php
    $catColor = str_contains($participant->category->slug, '10k') ? 'from-amber-600 to-amber-500' : (str_contains($participant->category->slug, '21k') ? 'from-red-600 to-red-500' : 'from-forest-600 to-forest-500');
    $distKm = $participant->category->distance_km ?? substr($participant->category->slug, 0, strpos($participant->category->slug, 'k'));
    @endphp
    <!-- BIB Card -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-2xl relative">
        <div class="absolute inset-0 bg-opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, #1a1a1a 1px, transparent 0); background-size: 20px 20px;"></div>
        
        <!-- Header -->
        <div class="bg-gradient-to-r {{ $catColor }} px-6 py-4 flex justify-between items-center relative z-10">
            <span class="font-display font-bold text-lg text-white">ERAFONE<span class="text-dark-900">TRAIL</span></span>
            <span class="font-display font-bold text-sm bg-white/20 px-3 py-1 rounded-full text-white backdrop-blur-sm">{{ $participant->category->name }}</span>
        </div>
        
        <!-- BIB Number -->
        <div class="text-center py-10 relative z-10">
            <h2 class="font-display font-black text-6xl tracking-widest text-dark-900">{{ $participant->bib_number }}</h2>
        </div>
        
        <!-- Detail Info -->
        <div class="px-8 pb-4 relative z-10">
            <p class="font-bold text-xl text-dark-900 text-center uppercase mb-1">{{ $participant->full_name }}</p>
            <p class="text-center text-dark-600 text-sm font-medium">{{ $participant->community ?? '-' }}</p>
        </div>
        
        <!-- Footer / QR Placeholder -->
        <div class="bg-gray-100 flex items-center justify-between p-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white rounded border border-gray-200 flex items-center justify-center">
                    <!-- Placeholder QR (akan diganti paket QR asli nanti) -->
                    <svg class="w-10 h-10 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h4v4H4V4zm6 0h10v4H10V4zM4 10h4v10H4V10zm6 0h4v4h-4v-4zm6 0h4v10h-4V10zm-6 6h4v4h-4v-4z"/></svg>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-medium pb-0.5">{{ __('messages.part_bib_show_qr') }}</span>
                    <span class="block text-xl font-display font-bold text-gray-800">{{ $distKm }} <span class="text-sm">{{ __('messages.part_kilometers') }}</span></span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-full border-4 border-white shadow-inner bg-current text-[{{ str_contains($participant->blood_type ?? '', ' ') ? '#a8a29e' : '#b91c1c' }}] flex items-center justify-center">
                <span class="font-black text-white text-sm">{{ $participant->blood_type ?? '?' }}</span>
            </div>
        </div>
    </div>
    @endif
</div>
@endif
@endsection
