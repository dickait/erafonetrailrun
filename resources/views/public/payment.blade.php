@extends('layouts.public')
@section('title', __('messages.part_payment_title') . ' - Erafone Trail Run 2026')
@section('content')
    <section class="pt-28 pb-20 bg-surface-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <span
                    class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.part_payment_title') }}</span>
                <h1 class="font-display font-bold text-3xl md:text-4xl text-surface-900 mb-2">Complete Your Payment</h1>
                <p class="text-surface-700">Please review your registration details and proceed to payment to secure your
                    spot.</p>
            </div>

            @if(isset($participant))
                <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-3 h-3 rounded-full {{ $participant->payment_status == 'paid' ? 'bg-emerald-500' : ($participant->payment_status == 'pending' ? 'bg-accent-500' : 'bg-brand-500') }}">
                        </div>
                        <h3 class="font-display font-semibold text-lg text-surface-900">Registration Details</h3>
                    </div>

                    <div class="space-y-4">
                        @php
                            $fields = [
                                __('messages.status_name') . ($participant->familyMembers && $participant->familyMembers->count() > 0 ? ' (Leader)' : '') => $participant->full_name,
                                __('messages.status_email') => $participant->email,
                                __('messages.status_category') => $participant->category->name ?? '-',
                                __('messages.status_event') => $participant->event->name ?? '-',
                                __('messages.status_registered') => $participant->created_at->format('d M Y'),
                                __('messages.status_blood_type') => $participant->blood_type ?? '-',
                                __('messages.status_jersey_size') => $participant->jersey_size ?? '-',
                            ];

                            if ($participant->familyMembers && $participant->familyMembers->count() > 0) {
                                $idx = 1;
                                foreach ($participant->familyMembers as $member) {
                                    $emailText = $member->email ? ' - ' . $member->email : '';
                                    $bloodText = $member->blood_type ? ' - (' . __('messages.status_blood_type') . ': ' . $member->blood_type . ')' : '';
                                    $jerseyText = $member->jersey_size ? ' - (' . __('messages.status_jersey_size') . ': ' . $member->jersey_size . ')' : '';

                                    $fields["Family Member $idx"] = $member->full_name . $emailText . $bloodText . $jerseyText;
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
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold self-start sm:self-auto {{ $participant->payment_status == 'paid' ? 'bg-emerald-50 text-emerald-600' : ($participant->payment_status == 'pending' ? 'bg-accent-50 text-accent-600' : 'bg-brand-50 text-brand-500') }}">
                                {{ $participant->payment_status == 'paid' ? __('messages.status_paid') : ($participant->payment_status == 'pending' ? __('messages.status_pending') : __('messages.status_failed')) }}
                            </span>
                        </div>
                    </div>

                    @if($participant->payment_status == 'pending' && isset($participant->latestPayment))
                        <div class="mt-8">
                            <a href="{{ $participant->latestPayment->payment_link ?? '#' }}" target="_blank"
                                class="block w-full py-4 text-center bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold text-lg rounded-xl shadow-md transition-all">Proceed
                                to Payment (Mayar.id)</a>
                        </div>
                    @endif

                    @if($participant->payment_status == 'paid')
                        <div class="mt-8">
                            <a href="{{ route('registration.status', ['email' => $participant->email]) }}"
                                class="block w-full py-3 text-center bg-surface-100 hover:bg-surface-200 text-surface-900 font-semibold rounded-xl transition-all">Check
                                Status</a>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-2xl border border-brand-100 p-8 text-center shadow-sm">
                    <svg class="w-16 h-16 mx-auto text-brand-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="font-display font-semibold text-lg text-surface-900 mb-2">
                        {{ __('messages.status_not_found_title') }}
                    </h3>
                    <p class="text-surface-700 text-sm">{{ __('messages.status_not_found_desc') }}</p>
                    <a href="{{ route('home') }}"
                        class="mt-6 inline-block px-6 py-2 bg-brand-50 text-brand-600 font-semibold rounded-lg hover:bg-brand-100 transition-colors">Back
                        to Home</a>
                </div>
            @endif
        </div>
    </section>
@endsection