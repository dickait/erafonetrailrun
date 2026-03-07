@extends('layouts.public')
@section('title', __('messages.status_title') . ' - Erafone Trail Run 2026')
@section('content')
    <section class="pt-28 pb-20 bg-surface-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <span
                    class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.status_badge') }}</span>
                <h1 class="font-display font-bold text-3xl md:text-4xl text-surface-900 mb-2">
                    {{ __('messages.status_title') }}
                </h1>
                <p class="text-surface-700">{{ __('messages.status_subtitle') }}</p>
            </div>
            <form method="GET" action="{{ route('registration.status') }}" class="flex gap-3 mb-8">
                <input type="email" name="email" value="{{ request('email') }}"
                    placeholder="{{ __('messages.status_placeholder') }}"
                    class="flex-1 px-4 py-3 bg-white border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-semibold rounded-xl hover:from-brand-600 hover:to-brand-700 transition-all shadow-md">{{ __('messages.status_check') }}</button>
            </form>
            @if(isset($participant))
                <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-3 h-3 rounded-full {{ $participant->payment_status == 'paid' ? 'bg-emerald-500' : ($participant->payment_status == 'pending' ? 'bg-accent-500' : 'bg-brand-500') }}">
                        </div>
                        <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.status_found') }}</h3>
                    </div>
                    <div class="space-y-4">
                        @php
                            $fields = [
                                __('messages.status_name') . ($participant->familyMembers && $participant->familyMembers->count() > 0 ? ' (Leader)' : '') => $participant->full_name,
                                __('messages.status_email') => $participant->email,
                                __('messages.status_category') => $participant->category->name ?? '-',
                                __('messages.status_event') => $participant->event->name ?? '-',
                                __('messages.status_registered') => $participant->created_at->format('d M Y'),
                                __('messages.status_bib') => $participant->bib_number ?? __('messages.status_bib_pending'),
                                __('messages.status_blood_type') => $participant->blood_type ?? '-',
                                __('messages.status_jersey_size') => $participant->jersey_size ?? '-',
                            ];

                            if ($participant->familyMembers && $participant->familyMembers->count() > 0) {
                                $idx = 1;
                                foreach ($participant->familyMembers as $member) {
                                    $emailText = $member->email ? ' - ' . $member->email : '';
                                    $bibText = $member->bib_number ? ' (BIB: ' . $member->bib_number . ')' : '';
                                    $bloodText = $member->blood_type ? ' - (' . __('messages.status_blood_type') . ': ' . $member->blood_type . ')' : '';
                                    $jerseyText = $member->jersey_size ? ' - (' . __('messages.status_jersey_size') . ': ' . $member->jersey_size . ')' : '';

                                    $fields["Family Member $idx"] = $member->full_name . $emailText . $bibText . $bloodText . $jerseyText;
                                    $idx++;
                                }
                            }

                            $multiplier = $participant->familyMembers ? ($participant->familyMembers->count() + 1) : 1;
                            $amount = $participant->latestPayment ? $participant->latestPayment->amount : ($participant->category ? $participant->category->getCurrentPrice() * $multiplier : 0);
                        @endphp

                        @foreach($fields as $label => $val)
                            <div
                                class="flex flex-col sm:flex-row justify-between py-2 border-b border-surface-100 last:border-0 gap-1 sm:gap-4">
                                <span class="text-surface-700 text-sm whitespace-nowrap">{{ $label }}</span>
                                <span class="text-surface-900 text-sm font-medium sm:text-right">{{ $val }}</span>
                            </div>
                        @endforeach

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-surface-100">
                            <span class="text-surface-700 text-sm font-semibold mb-1 sm:mb-0">Total Payment</span>
                            <div class="text-right flex flex-col sm:flex-row items-end sm:items-center gap-2 mt-1 sm:mt-0">
                                @if($multiplier > 1 && $participant->category)
                                    <span
                                        class="text-surface-500 text-xs font-medium bg-surface-100 px-2 py-1 rounded text-right sm:text-left">
                                        ({{ $multiplier }} Participants &times; Rp
                                        {{ number_format($participant->category->getCurrentPrice(), 0, ',', '.') }})
                                    </span>
                                @endif
                                <div class="text-brand-600 text-lg sm:text-lg font-bold whitespace-nowrap">
                                    Rp {{ number_format($amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between py-2 gap-2">
                            <span class="text-surface-700 text-sm mt-1 sm:mt-0">{{ __('messages.part_payment_status') }}</span>
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $participant->payment_status == 'paid' ? 'bg-emerald-50 text-emerald-600' : ($participant->payment_status == 'pending' ? 'bg-accent-50 text-accent-600' : 'bg-brand-50 text-brand-500') }}">
                                    {{ $participant->payment_status == 'paid' ? __('messages.status_paid') : ($participant->payment_status == 'pending' ? __('messages.status_pending') : __('messages.status_failed')) }}
                                </span>
                                @if($participant->payment_status == 'paid' && $participant->latestPayment && $participant->latestPayment->paid_at)
                                    <span class="text-sm text-surface-600 font-medium">
                                        {{ $participant->latestPayment->payment_method ?? 'Manual' }} &bull;
                                        {{ $participant->latestPayment->paid_at->format('d M Y, H:i:s') }} WIB
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($participant->payment_status == 'pending' && isset($participant->payments) && $participant->payments->first())
                        <div class="mt-6">
                            <a href="{{ $participant->payments->first()->payment_link ?? '#' }}" target="_blank"
                                class="block w-full py-3 text-center bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-xl shadow-md">{{ __('messages.status_complete_payment') }}</a>
                        </div>
                    @endif
                </div>
            @elseif(request('email'))
                <div class="bg-white rounded-2xl border border-brand-100 p-8 text-center shadow-sm">
                    <svg class="w-16 h-16 mx-auto text-brand-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="font-display font-semibold text-lg text-surface-900 mb-2">
                        {{ __('messages.status_not_found_title') }}
                    </h3>
                    <p class="text-surface-700 text-sm">{{ __('messages.status_not_found_desc') }}</p>
                </div>
            @endif
        </div>
    </section>
@endsection