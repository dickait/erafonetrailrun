@extends('layouts.public')
@section('title', __('messages.status_title') . ' - Era Trail Run 2026')
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
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-semibold rounded-xl hover:from-brand-600 hover:to-brand-700 transition-all shadow-md whitespace-nowrap cursor-pointer">{{ __('messages.status_check') }}</button>
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
                            $latestPayment = $participant->latestPayment;
                            $multiplier = $isFamily ? ($participant->familyMembers->count() + 1) : 1;

                            $baseAmount = $latestPayment ? $latestPayment->amount : ($participant->category ? $participant->category->getBasePrice($multiplier) : 0);
                            $discountAmount = $latestPayment ? $latestPayment->discount_amount : 0;
                            $finalAmount = $latestPayment ? ($latestPayment->final_amount ?? ($baseAmount - $discountAmount)) : ($baseAmount - $discountAmount);

                            $fields = [
                                'Order ID' => $latestPayment->order_id ?? '-',
                                __('messages.status_name') . ($isFamily ? ' (Leader)' : '') => $participant->full_name,
                                __('messages.status_email') => $participant->email,
                                __('messages.status_category') => $participant->category->name ?? '-',
                                __('messages.status_event') => $participant->event->name ?? '-',
                                __('messages.status_registered') => $participant->created_at->format('d M Y H:i:s') . ' WIB',
                                __('messages.status_bib') => $participant->bib_number ?? __('messages.status_bib_pending'),
                            ];

                            if ($discountAmount > 0) {
                                $promoLabel = optional($latestPayment->promotion)->code ?? optional($latestPayment->promotion)->name ?? 'PROMO';
                                $fields[__('messages.reg_discount')] = $promoLabel . ' (- Rp ' . number_format($discountAmount, 0, ',', '.') . ')';
                                $fields[__('messages.reg_total')] = 'Rp ' . number_format($finalAmount, 0, ',', '.');
                            }

                            if (!$isFamily) {
                                $fields[__('messages.status_blood_type')] = $participant->blood_type ?? '-';
                                $fields[__('messages.status_jersey_size')] = $participant->jersey_size ?? '-';
                            }
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
                                        ({{ optional($latestPayment->promotion)->code ?? optional($latestPayment->promotion)->name ?? 'PROMO' }})</span>
                                    <span class="font-medium">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="py-2 border-t border-surface-100 mt-2">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-surface-900 font-bold">{{ __('messages.reg_total') }}</span>
                                    <span class="text-brand-600 text-xl font-bold">Rp
                                        {{ number_format($finalAmount, 0, ',', '.') }}</span>
                                </div>
                                @if(config('services.payment') === 'manual' && $finalAmount > 0)
                                    <p style="font-size: 8px;"
                                        class="text-surface-500 text-right leading-none mt-1 uppercase tracking-tighter">3 digit
                                        terakhir adalah kode unik untuk verifikasi pembayaran</p>
                                @endif
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
                    @if($participant->payment_status == 'pending' && $latestPayment)
                        <div class="mt-6">
                            @if(config('services.payment') === 'manual')
                                <div class="bg-surface-50 border border-surface-200 rounded-2xl p-6">
                                    <h4
                                        class="font-display font-bold text-surface-900 mb-4 text-center text-lg uppercase tracking-wider">
                                        Bayar via QRIS</h4>
                                    <div class="mb-6 flex justify-center">
                                        <div class="p-4 bg-white rounded-2xl shadow-sm border border-surface-200">
                                            <img src="{{ asset('qris.webp') }}" alt="QRIS Payment"
                                                class="w-full max-w-[300px] h-auto rounded-lg mx-auto">
                                        </div>
                                    </div>

                                    <div class="mb-6 px-4">
                                        <h5 class="text-sm font-semibold text-surface-900 mb-3">Selesaikan pembayaran Anda dengan 4
                                            langkah mudah:</h5>
                                        <ul class="space-y-2 text-sm text-surface-700">
                                            <li class="flex gap-2"><span>1️⃣</span> <span>Simpan gambar QRIS di HP Anda</span></li>
                                            <li class="flex gap-2"><span>2️⃣</span> <span>Buka aplikasi dompet digital Anda</span></li>
                                            <li class="flex gap-2"><span>3️⃣</span> <span>Pilih fitur Scan QR atau Upload QR</span></li>
                                            <li class="flex gap-2"><span>4️⃣</span> <span>Upload gambar QRIS ini, lalu ikuti instruksi
                                                    untuk menyelesaikan pembayaran</span></li>
                                        </ul>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="p-4 bg-brand-50 rounded-xl border border-brand-100">
                                            <p class="text-sm text-brand-800 text-center leading-relaxed">
                                                <strong>Informasi:</strong> Status pembayaran Anda akan diperbarui dalam
                                                waktu <strong>1x24 jam</strong> setelah Anda melakukan konfirmasi pembayaran.
                                            </p>
                                        </div>

                                        @php
                                            $waMessage = "Halo Admin ERA Trail Run 2026,\n" .
                                                "Saya ingin mengonfirmasi pembayaran atas pendaftaran saya dengan detail sebagai berikut:\n\n" .
                                                "*Order ID:* " . ($latestPayment->order_id ?? '-') . "\n" .
                                                "*Nama:* " . $participant->full_name . "\n" .
                                                "*Email:* " . $participant->email . "\n" .
                                                "*Kategori:* " . ($participant->category->name ?? '-') . "\n" .
                                                "*Acara:* " . ($participant->event->name ?? '-') . "\n" .
                                                "*Tanggal Daftar:* " . $participant->created_at->format('d M Y H:i:s') . " WIB\n" .
                                                "*Golongan Darah:* " . ($participant->blood_type ?? '-') . "\n" .
                                                "*Ukuran Jersey:* " . ($participant->jersey_size ?? '-') . "\n\n";
                                            if ($participant->familyMembers && $participant->familyMembers->count() > 0) {
                                                $waMessage .= "*Anggota Keluarga:*\n";
                                                foreach ($participant->familyMembers as $m) {
                                                    $waMessage .= "- " . $m->full_name . " (" . ($m->jersey_size ?? '-') . ")\n";
                                                }
                                                $waMessage .= "\n";
                                            }
                                            $waMessage .= "*Biaya Pendaftaran:* Rp " . number_format($baseAmount, 0, ',', '.') . "\n" .
                                                "*Total Pembayaran:* Rp " . number_format($finalAmount, 0, ',', '.') . "\n\n" .
                                                "Berikut bukti pembayaran melalui QRIS yang ditampilkan di website.\n\n" .
                                                "Mohon konfirmasi dan pengecekan lebih lanjut.\n" .
                                                "Terima kasih.";
                                            $waUrl = "https://wa.me/628561310130?text=" . urlencode($waMessage);
                                        @endphp

                                        <a href="{{ $waUrl }}" target="_blank"
                                            style="background-color: #25D366 !important; color: white !important;"
                                            class="flex items-center justify-center gap-2 w-full py-4 font-bold text-lg rounded-xl shadow-md transition-all hover:opacity-90 cursor-pointer">
                                            <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                            </svg>
                                            <span>Konfirmasi Pembayaran</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <a href="{{ $latestPayment->payment_link ?? '#' }}" target="_blank"
                                    class="block w-full py-3 text-center bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-xl shadow-md cursor-pointer">{{ __('messages.status_complete_payment') }}</a>
                            @endif
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