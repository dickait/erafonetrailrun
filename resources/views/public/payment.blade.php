@extends('layouts.public')
@section('title', __('messages.part_payment_title') . ' - Erafone Trail Run 2026')
@section('content')
    <section class="pt-28 pb-20 bg-surface-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <span
                    class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.part_payment_title') }}</span>
                <h1 class="font-display font-bold text-3xl md:text-4xl text-surface-900 mb-2">
                    {{ $participant->payment_status == 'paid' ? __('messages.part_payment_success_title') : __('messages.part_payment_complete_title') }}
                </h1>
                <p class="text-surface-700">
                    {{ $participant->payment_status == 'paid' ? __('messages.part_payment_success_subtitle') : __('messages.part_payment_complete_subtitle') }}
                </p>
            </div>

            @if(isset($participant))
                <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-3 h-3 rounded-full {{ $participant->payment_status == 'paid' ? 'bg-emerald-500' : ($participant->payment_status == 'pending' ? 'bg-accent-500' : 'bg-brand-500') }}">
                        </div>
                        <h3 class="font-display font-semibold text-lg text-surface-900">
                            {{ __('messages.status_found') }}
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @php
                            $isFamily = $participant->familyMembers && $participant->familyMembers->count() > 0;

                            $fields = [
                                'Order ID' => $participant->latestPayment->order_id ?? '-',
                                __('messages.status_name') . ($isFamily ? ' (Leader)' : '') => $participant->full_name,
                                __('messages.status_email') => $participant->email,
                                __('messages.status_category') => $participant->category->name ?? '-',
                                __('messages.status_event') => $participant->event->name ?? '-',
                                __('messages.status_registered') => $participant->created_at->format('d M Y'),
                                __('messages.status_bib') => $participant->bib_number ?? __('messages.status_bib_pending'),
                            ];

                            if (!$isFamily) {
                                $fields[__('messages.status_blood_type')] = $participant->blood_type ?? '-';
                                $fields[__('messages.status_jersey_size')] = $participant->jersey_size ?? '-';
                            }

                            $latestPayment = $participant->latestPayment;
                            $multiplier = $isFamily ? ($participant->familyMembers->count() + 1) : 1;

                            $baseAmount = $latestPayment ? $latestPayment->amount : ($participant->category ? $participant->category->getBasePrice($multiplier) : 0);
                            $discountAmount = $latestPayment ? $latestPayment->discount_amount : 0;
                            $finalAmount = $latestPayment ? ($latestPayment->final_amount ?? ($baseAmount - $discountAmount)) : ($baseAmount - $discountAmount);
                        @endphp

                        @foreach($fields as $label => $val)
                            <div
                                class="flex flex-col sm:flex-row justify-between py-2 border-b border-surface-100 last:border-0 gap-1 sm:gap-4">
                                <span class="text-surface-700 text-sm whitespace-nowrap">{{ $label }}</span>
                                <span class="text-surface-900 text-sm font-medium sm:text-right">{{ $val }}</span>
                            </div>
                        @endforeach

                        @if($isFamily)
                            <div class="mt-4 border border-surface-200 rounded-xl overflow-hidden">
                                <div class="bg-surface-50 px-4 py-3 border-b border-surface-200">
                                    <h4 class="font-semibold text-surface-900 text-sm">{{ __('messages.status_family_members') }}
                                    </h4>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="bg-surface-50 text-surface-600 text-xs uppercase">
                                            <tr>
                                                <th class="px-4 py-3 font-medium">{{ __('messages.status_name') }}</th>
                                                <th class="px-4 py-3 font-medium">Email</th>
                                                <th class="px-4 py-3 font-medium">{{ __('messages.status_blood_type') }}</th>
                                                <th class="px-4 py-3 font-medium">{{ __('messages.status_jersey_size') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-surface-100">
                                            <tr class="bg-white">
                                                <td class="px-4 py-3 font-medium text-surface-900 whitespace-nowrap">
                                                    {{ $participant->full_name }} <span
                                                        class="text-xs text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full ml-1 capitalize">{{ $participant->role ? __('messages.role_' . $participant->role) : 'Leader' }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-surface-600">{{ $participant->email }}</td>
                                                <td class="px-4 py-3 text-surface-600">{{ $participant->blood_type ?? '-' }}</td>
                                                <td class="px-4 py-3 text-surface-600">{{ $participant->jersey_size ?? '-' }}</td>
                                            </tr>
                                            @foreach($participant->familyMembers as $member)
                                                <tr class="bg-white">
                                                    <td class="px-4 py-3 font-medium text-surface-900 whitespace-nowrap">
                                                        {{ $member->full_name }} <span
                                                            class="text-xs text-surface-500 bg-surface-100 px-2 py-0.5 rounded-full ml-1 capitalize">{{ $member->role ? __('messages.role_' . $member->role) : 'Member' }}</span>
                                                    </td>
                                                    <td class="px-4 py-3 text-surface-600">{{ $member->email ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-surface-600">{{ $member->blood_type ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-surface-600">{{ $member->jersey_size ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 border-t border-surface-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-surface-600">{{ __('messages.reg_fee') }}</span>
                                <span class="text-surface-900 font-medium">
                                    @if($discountAmount > 0)<strike class="opacity-50">@endif
                                    Rp {{ number_format($baseAmount, 0, ',', '.') }}
                                    @if($discountAmount > 0)</strike>@endif
                                </span>
                            </div>

                            @if($discountAmount > 0)
                                <div class="flex justify-between text-sm text-emerald-600">
                                    <span>{{ __('messages.reg_discount') }}
                                        ({{ $latestPayment->discountCode->code ?? $latestPayment->promotion->name ?? 'PROMO' }})</span>
                                    <span class="font-medium">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center py-2 border-t border-surface-100 mt-2">
                                <span class="text-surface-900 font-bold">{{ __('messages.reg_total') }}</span>
                                <span class="text-brand-600 text-xl font-bold">Rp
                                    {{ number_format($finalAmount, 0, ',', '.') }}</span>
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
                                    <span class="text-sm text-surface-600 font-medium whitespace-nowrap">
                                        {{ $participant->latestPayment->payment_method ?? 'Manual' }} &bull;
                                        {{ $participant->latestPayment->paid_at->format('d M Y, H:i:s') }} WIB
                                    </span>
                                @endif
                            </div>
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