@extends('layouts.public')
@section('title', __('messages.status_title') . ' - Erafone Trail Run 2026')
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="inline-block px-4 py-1.5 bg-forest-900/40 text-forest-400 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.status_badge') }}</span>
            <h1 class="font-display font-bold text-3xl md:text-4xl text-white mb-2">{{ __('messages.status_title') }}</h1>
            <p class="text-gray-400">{{ __('messages.status_subtitle') }}</p>
        </div>
        <form method="GET" action="{{ route('registration.status') }}" class="flex gap-3 mb-8">
            <input type="email" name="email" value="{{ request('email') }}" placeholder="{{ __('messages.status_placeholder') }}" class="flex-1 px-4 py-3 bg-dark-800 border border-forest-900/30 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-forest-600 to-forest-500 text-white font-semibold rounded-xl hover:from-forest-500 hover:to-forest-400 transition-all">{{ __('messages.status_check') }}</button>
        </form>
        @if(isset($participant))
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-3 h-3 rounded-full {{ $participant->payment_status == 'paid' ? 'bg-forest-500' : ($participant->payment_status == 'pending' ? 'bg-amber-500' : 'bg-red-500') }}"></div>
                <h3 class="font-display font-semibold text-lg text-white">{{ __('messages.status_found') }}</h3>
            </div>
            <div class="space-y-4">
                @php
                $fields = [
                    __('messages.status_name') => $participant->full_name,
                    __('messages.status_email') => $participant->email,
                    __('messages.status_category') => $participant->category->name ?? '-',
                    __('messages.status_event') => $participant->event->name ?? '-',
                    __('messages.status_registered') => $participant->created_at->format('d M Y'),
                    __('messages.status_bib') => $participant->bib_number ?? __('messages.status_bib_pending'),
                ];
                @endphp
                @foreach($fields as $label => $val)
                <div class="flex justify-between py-2 border-b border-dark-700 last:border-0">
                    <span class="text-gray-400 text-sm">{{ $label }}</span>
                    <span class="text-white text-sm font-medium">{{ $val }}</span>
                </div>
                @endforeach
                <div class="flex justify-between py-2">
                    <span class="text-gray-400 text-sm">{{ __('messages.part_payment_status') }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $participant->payment_status == 'paid' ? 'bg-forest-900/40 text-forest-400' : ($participant->payment_status == 'pending' ? 'bg-amber-900/40 text-amber-400' : 'bg-red-900/40 text-red-400') }}">
                        {{ $participant->payment_status == 'paid' ? __('messages.status_paid') : ($participant->payment_status == 'pending' ? __('messages.status_pending') : __('messages.status_failed')) }}
                    </span>
                </div>
            </div>
            @if($participant->payment_status == 'pending' && isset($participant->payments) && $participant->payments->first())
            <div class="mt-6">
                <a href="{{ $participant->payments->first()->payment_link ?? '#' }}" target="_blank" class="block w-full py-3 text-center bg-gradient-to-r from-amber-600 to-amber-500 text-white font-semibold rounded-xl">{{ __('messages.status_complete_payment') }}</a>
            </div>
            @endif
        </div>
        @elseif(request('email'))
        <div class="bg-dark-800 rounded-2xl border border-red-900/30 p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-red-500/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="font-display font-semibold text-lg text-white mb-2">{{ __('messages.status_not_found_title') }}</h3>
            <p class="text-gray-400 text-sm">{{ __('messages.status_not_found_desc') }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
