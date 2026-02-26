@extends('layouts.participant')
@section('title', __('messages.part_dashboard') . ' - Erafone Trail Run')
@section('content')
<div class="mb-8">
    <h1 class="font-display font-bold text-2xl text-white mb-2">{{ __('messages.part_my_dashboard') }}</h1>
    <p class="text-gray-400">{{ __('messages.part_welcome') }} <span class="text-forest-400 font-semibold">{{ auth()->user()->name }}</span></p>
</div>

@if(!$participant)
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-8 text-center">
    <div class="w-16 h-16 mx-auto bg-dark-700 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <h3 class="font-display font-semibold text-lg text-white mb-2">{{ __('messages.part_no_registration') }}</h3>
    <a href="{{ route('register.create') }}" class="inline-block mt-4 px-6 py-2.5 bg-forest-700 hover:bg-forest-600 font-medium text-white rounded-xl transition-colors">{{ __('messages.part_register_now') }}</a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 overflow-hidden">
            <div class="p-6 border-b border-forest-900/30 flex justify-between items-center">
                <h3 class="font-display font-semibold text-lg text-white">{{ __('messages.part_event_details') }}</h3>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-forest-900/40 text-forest-400">{{ $participant->category->name }}</span>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wide mb-1">{{ __('messages.part_event') }}</span>
                    <span class="text-white font-medium">{{ $participant->event->name }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wide mb-1">{{ __('messages.part_event_date') }}</span>
                    <span class="text-white font-medium">{{ $participant->event->event_date->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wide mb-1">{{ __('messages.part_event_location') }}</span>
                    <span class="text-white font-medium">{{ __('messages.hero_location') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wide mb-1">{{ __('messages.part_payment_status') }}</span>
                    <span class="text-{{ $participant->payment_status == 'paid' ? 'forest' : ($participant->payment_status == 'pending' ? 'amber' : 'red') }}-400 font-medium">{{ ucfirst($participant->payment_status) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="bg-gradient-to-br from-forest-900/40 to-dark-800 rounded-2xl border border-forest-800/30 p-6 text-center transform hover:-translate-y-1 transition-transform">
            <span class="block text-sm text-forest-400 font-medium mb-3">{{ __('messages.part_bib_number') }}</span>
            <div class="font-display font-black text-4xl text-white mb-2 tracking-wider">{{ $participant->bib_number ?? '-' }}</div>
            @if(!$participant->bib_number)
            <span class="text-xs text-gray-400 bg-dark-700 px-3 py-1 rounded-full">{{ __('messages.part_bib_pending') }}</span>
            @endif
        </div>
    </div>
</div>
@endif
@endsection
