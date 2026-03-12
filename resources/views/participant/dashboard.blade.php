@extends('layouts.participant')
@section('title', __('messages.part_dashboard') . ' - Era Trail Run')
@section('content')
    <div class="mb-8">
        <h1 class="font-display font-bold text-2xl text-surface-900 mb-2">{{ __('messages.part_my_dashboard') }}</h1>
        <p class="text-surface-700">{{ __('messages.part_welcome') }} <span
                class="text-brand-500 font-semibold">{{ auth()->user()->name }}</span></p>
    </div>

    @if(!$participant)
        <div class="bg-white rounded-2xl border border-surface-300 p-8 text-center shadow-sm">
            <div class="w-16 h-16 mx-auto bg-surface-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-surface-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="font-display font-semibold text-lg text-surface-900 mb-2">{{ __('messages.part_no_registration') }}</h3>
            <a href="{{ route('register.create') }}"
                class="inline-block mt-4 px-6 py-2.5 bg-brand-500 hover:bg-brand-600 font-medium text-white rounded-xl transition-colors shadow-md">{{ __('messages.part_register_now') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-surface-300 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-surface-300 flex justify-between items-center">
                        <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.part_event_details') }}
                        </h3>
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full bg-brand-50 text-brand-500">{{ $participant->category->name }}</span>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">
                        <div>
                            <span
                                class="block text-xs text-surface-700 uppercase tracking-wide mb-1">{{ __('messages.part_event') }}</span>
                            <span class="text-surface-900 font-medium">{{ $participant->event->name }}</span>
                        </div>
                        <div>
                            <span
                                class="block text-xs text-surface-700 uppercase tracking-wide mb-1">{{ __('messages.part_event_date') }}</span>
                            <span
                                class="text-surface-900 font-medium">{{ $participant->event->event_date->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span
                                class="block text-xs text-surface-700 uppercase tracking-wide mb-1">{{ __('messages.part_event_location') }}</span>
                            <span class="text-surface-900 font-medium">{{ __('messages.hero_location') }}</span>
                        </div>
                        <div>
                            <span
                                class="block text-xs text-surface-700 uppercase tracking-wide mb-1">{{ __('messages.part_payment_status') }}</span>
                            <span
                                class="text-{{ $participant->payment_status == 'paid' ? 'emerald-600' : ($participant->payment_status == 'pending' ? 'accent-600' : 'brand-500') }} font-medium">{{ ucfirst($participant->payment_status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div
                    class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl border border-brand-400/30 p-6 text-center transform hover:-translate-y-1 transition-transform shadow-lg">
                    <span class="block text-sm text-white/80 font-medium mb-3">{{ __('messages.part_bib_number') }}</span>
                    <div class="font-display font-black text-4xl text-white mb-2 tracking-wider">
                        {{ $participant->bib_number ?? '-' }}</div>
                    @if(!$participant->bib_number)
                        <span
                            class="text-xs text-white/60 bg-white/10 px-3 py-1 rounded-full">{{ __('messages.part_bib_pending') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection