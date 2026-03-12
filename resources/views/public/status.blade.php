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
            @if($errors->any())
                <div class="mb-6 p-4 bg-brand-50 border border-brand-200 rounded-xl">
                    <ul class="text-sm text-brand-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('registration.status') }}" class="mb-8">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    <input type="email" name="email" value="{{ old('email', request('email')) }}"
                        placeholder="{{ __('messages.status_placeholder') }}" required
                        class="flex-1 w-full px-4 py-3 bg-white border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-semibold rounded-xl hover:from-brand-600 hover:to-brand-700 transition-all shadow-md whitespace-nowrap">{{ __('messages.status_check') }}</button>
                </div>
                <div class="flex flex-col items-center sm:items-start gap-3 mb-6">
                    <div class="captcha-img-container flex items-center gap-2">
                        {!! captcha_img('flat') !!}
                        <button type="button" class="p-2 bg-surface-100 rounded-lg hover:bg-surface-200 transition-colors"
                            onclick="refreshCaptcha()">
                            <svg class="w-5 h-5 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                    <input type="text" name="captcha" placeholder="Enter Captcha Code" required value="{{ old('captcha') }}"
                        class="w-full max-w-[200px] px-4 py-3 bg-white border border-surface-300 rounded-xl text-center text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                </div>
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
                            $isFamily = $participant->familyMembers && $participant->familyMembers->count() > 0;

                            $fields = [
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
                            
                            $baseAmount = $latestPayment ? $latestPayment->amount : ($participant->category ? $participant->category->getCurrentPrice() * $multiplier : 0);
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
                                <span class="text-surface-600">Registration Fee</span>
                                <span class="text-surface-900 font-medium">Rp {{ number_format($baseAmount, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($discountAmount > 0)
                                <div class="flex justify-between text-sm text-emerald-600">
                                    <span>Discount ({{ $latestPayment->discountCode->code ?? 'PROMO' }})</span>
                                    <span class="font-medium">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center py-2 border-t border-surface-100 mt-2">
                                <span class="text-surface-900 font-bold">Total Payment</span>
                                <span class="text-brand-600 text-xl font-bold">Rp {{ number_format($finalAmount, 0, ',', '.') }}</span>
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
            @elseif(request()->isMethod('post'))
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

@push('scripts')
    <script>
        function refreshCaptcha() {
            const img = document.querySelector('.captcha-img-container img');
            if (img) {
                const url = '{{ captcha_src("flat") }}';
                img.src = url + '?' + Math.random();
            }
        }
    </script>
@endpush