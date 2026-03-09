@extends('layouts.public')
@section('title', __('messages.reg_badge') . ' - ERA TRAIL RUN 2026')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush
@section('content')
    <section class="pt-28 pb-20 bg-surface-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <span
                    class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.reg_badge') }}</span>
                <h1 class="font-display font-bold text-3xl md:text-4xl text-surface-900 mb-2">{{ __('messages.reg_title') }}
                    {{ $event->name ?? 'Erafone Trail Run 2026' }}
                </h1>
                <p class="text-surface-700">{{ __('messages.reg_subtitle') }}</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-brand-50 border border-brand-200 rounded-xl">
                    <ul class="text-sm text-brand-600 space-y-1">@foreach($errors->all() as $error)<li>• {{ $error }}</li>
                    @endforeach</ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-8">
                @csrf
                <!-- Category Selection -->
                <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                    <h3 class="font-display font-semibold text-lg text-surface-900 mb-4">
                        {{ __('messages.reg_select_category') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($categories as $cat)
                            <label class="cursor-pointer">
                                <input type="radio" name="category_id" value="{{ $cat->id }}" data-slug="{{ $cat->slug }}"
                                    class="category-radio hidden peer" {{ old('category_id', request('category')) == $cat->id ? 'checked' : '' }}>
                                <div
                                    class="border border-surface-300 rounded-xl p-4 text-center transition-all peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:border-brand-300">
                                    <p
                                        class="font-display font-bold text-xl {{ $loop->index == 0 ? 'text-accent-600' : ($loop->index == 1 ? 'text-brand-500' : 'text-emerald-600') }}">
                                        {{ strtoupper(explode(' ', $cat->name)[0]) }}
                                    </p>
                                    <p class="text-sm text-surface-700">{{ $cat->name }}</p>
                                    <p class="text-sm text-brand-500 font-medium">Rp
                                        {{ number_format($cat->getCurrentPrice(), 0, ',', '.') }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Dynamic Category Details -->
                    <div id="category-details-container" class="mt-6 border-t border-surface-300 pt-6 hidden">
                        @foreach($categories as $cat)
                            @php
                                $usia = $cat->slug == '5k-family-trail' ? '10+' : '17+';
                            @endphp
                            <div id="cat-detail-{{ $cat->id }}" class="category-detail-content hidden">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-3">
                                        <h4 class="font-display font-bold text-surface-900 mb-2">{{ $cat->name }}</h4>
                                        <p class="text-surface-700 text-sm mb-4">
                                            {{ __('messages.cat_' . $cat->slug . '_desc') }}
                                        </p>
                                        <div class="flex justify-between text-sm"><span
                                                class="text-surface-700">{{ __('messages.categories_distance') }}</span><span
                                                class="text-surface-900 font-medium">{{ $cat->distance_km }} km</span></div>
                                        <div class="flex justify-between text-sm"><span
                                                class="text-surface-700">{{ __('messages.categories_elevation') }}</span><span
                                                class="text-surface-900 font-medium">{{ $cat->elevation ?? 0 }} m</span></div>
                                        <div class="flex justify-between text-sm"><span
                                                class="text-surface-700">{{ __('messages.categories_cot') }}</span><span
                                                class="text-surface-900 font-medium">{{ $cat->cot ?? 0 }}
                                                {{ app()->getLocale() == 'id' ? 'Jam' : 'Hours' }}</span></div>
                                        <div class="flex justify-between text-sm"><span
                                                class="text-surface-700">{{ __('messages.categories_age') }}</span><span
                                                class="text-surface-900 font-medium">{{ $usia }}
                                                {{ __('messages.cat_age') }}</span></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-surface-900 mb-3">
                                            {{ __('messages.categories_entitlements') }}:
                                        </p>
                                        <ul class="text-sm text-surface-700 space-y-2">
                                            <li class="flex items-start gap-2"><svg
                                                    class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>{{ __('messages.categories_item_jersey') }}</li>
                                            <li class="flex items-start gap-2"><svg
                                                    class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>{{ __('messages.categories_item_medal') }}</li>
                                            <li class="flex items-start gap-2"><svg
                                                    class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>{{ __('messages.categories_item_racepack') }}</li>
                                            <li class="flex items-start gap-2"><svg
                                                    class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>{{ __('messages.categories_item_refreshment') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Family Participant Count -->
                    <div id="family-count-container" class="mt-6 border-t border-surface-300 pt-6 hidden">
                        <label
                            class="block text-sm font-medium text-surface-800 mb-2">{{ __('messages.reg_family_count') }}</label>
                        <select id="family_count" name="family_count"
                            class="w-full sm:w-1/2 px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="2" selected>{{ __('messages.reg_family_count_2') }}</option>
                            <option value="3" selected>{{ __('messages.reg_family_count_3') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Main Form Fields Container -->
                <div id="form-fields-container" class="space-y-8">
                    <!-- Family Indicator (Sticky) -->
                    <div id="participant-indicator"
                        class="hidden p-4 rounded-xl bg-brand-50 border border-brand-200 text-center shadow-md transition-shadow relative z-40"
                        style="position: -webkit-sticky; position: sticky; top: 80px; margin-bottom: 2rem;">
                        <h4 class="font-display font-bold text-brand-600 text-lg">
                            {!! str_replace([':current', ':total'], ['1', '2'], __('messages.reg_participant_n')) !!}
                        </h4>
                        <p id="ind-role" class="text-brand-500 font-medium text-sm">{{ __('messages.reg_role_leader') }}</p>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
                        <h3 class="font-display font-semibold text-lg text-surface-900">
                            {{ __('messages.reg_personal_info') }}
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-surface-800 mb-1.5">Name
                                    *</label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                    placeholder="Name">
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_dob') }}
                                    *</label>
                                <input type="text" id="date_of_birth" name="date_of_birth"
                                    value="{{ old('date_of_birth') }}" required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                    placeholder="Pilih Tanggal Lahir">
                                <p class="text-xs text-red-500 mt-1 hidden" id="err-dob"></p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_age') }}
                                    *</label>
                                <input type="number" id="age" name="age" readonly
                                    class="w-full px-4 py-3 bg-surface-100 border border-surface-300 rounded-xl text-surface-900 cursor-not-allowed focus:outline-none"
                                    placeholder="{{ __('messages.reg_age_auto') }}">
                                <p class="text-xs font-bold text-accent-500 mt-1 hidden" id="lbl-age">MASTER</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_gender') }}
                                    *</label>
                                <select name="gender" required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                    <option value="">{{ __('messages.reg_gender_select') }}</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                        {{ __('messages.reg_gender_male') }}
                                    </option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                        {{ __('messages.reg_gender_female') }}
                                    </option>
                                </select>
                            </div>

                            <div id="wrapper-email">
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_email') }}
                                    *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                    placeholder="your@email.com">
                                <p class="text-xs text-red-500 mt-1 hidden" id="err-email"></p>
                            </div>

                            <div id="wrapper-phone">
                                <label class="block text-sm font-medium text-surface-800 mb-1.5">No Whatsapp
                                    *</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                    placeholder="08xxxxxxxxx">
                                <p class="text-xs text-red-500 mt-1 hidden" id="err-phone"></p>
                            </div>

                            <div>
                                <label id="lbl-identity"
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_identity') }}
                                    *</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number') }}" required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                    placeholder="KTP / Passport Number">
                                <p id="hlp-identity" class="mt-1.5 text-xs text-brand-500 hidden">Untuk peserta anak dapat
                                    menggunakan nomor KIA atau identitas lainnya.</p>
                            </div>

                            <divid="wrapper-nationality">
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_nationality') }}
                                    *</label>
                                <input type="text" name="nationality" value="{{ old('nationality', 'Indonesia') }}" required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        </div>
                    </div>

                    <!-- Location -->
                    <div id="section-location"
                        class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
                        <div class="flex justify-between items-center">
                            <h3 class="font-display font-semibold text-lg text-surface-900">
                                {{ __('messages.reg_location') }}
                            </h3>
                            <button type="button" id="btn-copy-leader-address"
                                class="hidden text-xs px-3 py-1.5 bg-brand-50 text-brand-600 border border-brand-200 rounded-lg hover:bg-brand-100 transition-colors font-medium">
                                📋 Sama dengan Team Leader
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_country') }}</label>
                                <select name="country_id" id="country"
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                    <option value="">{{ __('messages.reg_select_country') }}</option>
                                    @foreach($countries as $country)<option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_province') }}</label>
                                <select name="province_id" id="province"
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                    <option value="">{{ __('messages.reg_select_province') }}</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_city') }}</label>
                                <select name="city_id" id="city"
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                    <option value="">{{ __('messages.reg_select_city') }}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_address') }}</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
                        <h3 class="font-display font-semibold text-lg text-surface-900">
                            {{ __('messages.reg_additional') }}
                        </h3>

                        <div>
                            <div class="flex justify-between items-end mb-1.5">
                                <label
                                    class="block text-sm font-medium text-surface-800">{{ __('messages.reg_jersey_size') }}</label>
                                <button type="button"
                                    onclick="document.getElementById('sizeChartModal').classList.remove('hidden')"
                                    class="text-xs text-brand-500 hover:text-brand-600 underline font-medium">Panduan /
                                    Size
                                    Chart</button>
                            </div>
                            <div class="mb-3 p-4 bg-surface-50 rounded-xl flex items-center gap-4 text-center">
                                <div class="flex-shrink-0 w-16 h-16 text-brand-400">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                                        <path
                                            d="M19.7 7.7L16 4.6C15.8 4.4 15.6 4.3 15.3 4.3H8.7C8.4 4.3 8.2 4.4 8 4.6L4.3 7.7C3.9 8 3.8 8.4 4 8.8L5.4 12c.1.3.4.4.7.4H7v8.3c0 .4.3.7.7.7h8.7c.4 0 .7-.3.7-.7V12.3h.9c.3 0 .6-.2.7-.4L20 8.8C20.2 8.4 20.1 8 19.7 7.7zM15 6.3V8c0 .6-.4 1-1 1H10C9.4 9 9 8.6 9 8V6.3c1-.3 2-1.3 3-1.3S14 6 15 6.3z" />
                                    </svg>
                                </div>
                                <div class="text-xs text-surface-700 text-left w-full max-w-sm">
                                    <p class="mb-1"><span class="font-bold text-surface-700">A. Lebar Dada:</span> Dari
                                        ketiak
                                        ke ketiak.</p>
                                    <p><span class="font-bold text-surface-700">B. Panjang:</span> Dari kerah ke bawah.
                                    </p>
                                </div>
                            </div>
                            <select name="jersey_size"
                                class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                <option value="">{{ __('messages.reg_select') }}</option>
                                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XS (Anak-anak)', 'S (Anak-anak)', 'M (Anak-anak)', 'L (Anak-anak)', 'XL (Anak-anak)'] as $sz)
                                    <option value="{{ $sz }}" {{ old('jersey_size') == $sz ? 'selected' : '' }}>{{ $sz }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_blood_type') }}</label>
                                <select name="blood_type"
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                    <option value="">{{ __('messages.reg_select') }}</option>
                                    @foreach(['A', 'B', 'AB', 'O'] as $bt)<option value="{{ $bt }}" {{ old('blood_type') == $bt ? 'selected' : '' }}>{{ $bt }}</option>@endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_community') }}</label>
                                <input type="text" name="community" value="{{ old('community') }}"
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" id="wrapper-emergency">
                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_emergency_name') }}
                                    *</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                    required
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                <p class="text-xs text-red-500 mt-1 hidden" id="err-em-name">Nama kontak darurat wajib
                                    diisi.</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_emergency_phone') }}
                                    *</label>
                                <input type="text" name="emergency_contact_phone" required
                                    value="{{ old('emergency_contact_phone') }}"
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                <p class="text-xs text-red-500 mt-1 hidden" id="err-em-phone"></p>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_medical') }}</label>
                            <textarea name="medical_conditions" rows="3"
                                class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                placeholder="{{ __('messages.reg_medical_placeholder') }}">{{ old('medical_conditions') }}</textarea>
                        </div>
                    </div>

                    <!-- Agreements & Recaptcha -->
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
                        <div class="space-y-4">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="checkbox" name="agreement_1" required
                                        class="w-5 h-5 rounded border-surface-300 bg-surface-50 text-brand-500 focus:ring-brand-500 focus:ring-offset-white">
                                </div>
                                <span
                                    class="text-sm text-surface-600 group-hover:text-surface-900 transition-colors">{{ __('messages.reg_agreement_1') }}
                                    *</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="checkbox" name="agreement_2" required
                                        class="w-5 h-5 rounded border-surface-300 bg-surface-50 text-brand-500 focus:ring-brand-500 focus:ring-offset-white">
                                </div>
                                <span
                                    class="text-sm text-surface-600 group-hover:text-surface-900 transition-colors">{{ __('messages.reg_agreement_2') }}
                                    *</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="checkbox" name="agreement_3" required
                                        class="w-5 h-5 rounded border-surface-300 bg-surface-50 text-brand-500 focus:ring-brand-500 focus:ring-offset-white">
                                </div>
                                <span
                                    class="text-sm text-surface-600 group-hover:text-surface-900 transition-colors">{{ __('messages.reg_agreement_3') }}
                                    *</span>
                            </label>
                        </div>

                    </div>
                </div>

                <!-- Review Container -->
                <div id="review-container" class="hidden space-y-6">
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="font-display font-semibold text-lg text-surface-900 mb-4">
                            {{ __('messages.reg_review_title') }}
                        </h3>
                        <div id="review-content" class="space-y-4"></div>
                        <button type="button" id="btn-edit-data"
                            class="mt-6 cursor-pointer px-6 py-3 bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 font-semibold rounded-xl transition-all shadow-sm hover:shadow text-sm">
                            {!! __('messages.reg_btn_edit') !!}
                        </button>
                    </div>
                </div>

                <div id="btn-next-container" class="hidden flex gap-3">
                    <button type="button" id="btn-prev"
                        class="hidden cursor-pointer flex-1 py-4 bg-surface-200 text-surface-800 font-bold rounded-2xl hover:bg-surface-300 transition-all duration-200 text-lg shadow-sm hover:shadow-md">
                        {!! __('messages.reg_btn_prev') !!}
                    </button>
                    <button type="button" id="btn-next"
                        class="cursor-pointer flex-1 py-4 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold rounded-2xl shadow-xl shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 text-lg">
                        {{ __('messages.reg_btn_next') }}
                    </button>
                </div>

                <div id="btn-submit-container">
                    <div class="mb-6 flex justify-center">
                        <div class="g-recaptcha"
                            data-sitekey="{{ env('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}">
                        </div>
                    </div>
                    <button type="submit" id="btn-submit"
                        class="w-full py-4 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold rounded-2xl shadow-xl shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 text-lg">
                        {{ __('messages.reg_submit') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Size Chart Modal -->
    <div id="sizeChartModal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm p-4">
        <div
            class="bg-white border border-surface-300 rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div
                class="sticky top-0 bg-white/90 backdrop-blur-md p-6 border-b border-surface-300 flex justify-between items-center z-10">
                <h3 class="text-xl font-display font-bold text-surface-900">Panduan Ukuran Jersey</h3>
                <button type="button" onclick="document.getElementById('sizeChartModal').classList.add('hidden')"
                    class="text-surface-700 hover:text-surface-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <!-- Unisex Size Chart -->
                <div class="mb-8">
                    <h4 class="text-lg font-bold text-brand-500 mb-4 border-l-4 border-brand-500 pl-3">Unisex Size Chart
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-surface-600">
                            <thead class="text-xs text-surface-900 uppercase bg-surface-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 rounded-tl-lg">Ukuran (Size)</th>
                                    <th scope="col" class="px-4 py-3">Lebar Dada (cm)</th>
                                    <th scope="col" class="px-4 py-3 rounded-tr-lg">Panjang Badan (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">XS</td>
                                    <td class="px-4 py-3">46</td>
                                    <td class="px-4 py-3">66</td>
                                </tr>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">S</td>
                                    <td class="px-4 py-3">48</td>
                                    <td class="px-4 py-3">68</td>
                                </tr>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">M</td>
                                    <td class="px-4 py-3">50</td>
                                    <td class="px-4 py-3">70</td>
                                </tr>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">L</td>
                                    <td class="px-4 py-3">52</td>
                                    <td class="px-4 py-3">72</td>
                                </tr>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">XL</td>
                                    <td class="px-4 py-3">54</td>
                                    <td class="px-4 py-3">74</td>
                                </tr>
                                <tr class="hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900 rounded-bl-lg">XXL</td>
                                    <td class="px-4 py-3">56</td>
                                    <td class="px-4 py-3 rounded-br-lg">76</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kids Size Chart -->
                <div class="mb-8">
                    <h4 class="text-lg font-bold text-emerald-500 mb-4 border-l-4 border-emerald-500 pl-3">Kids Unisex Size
                        Chart (Anak-anak)
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-surface-600">
                            <thead class="text-xs text-surface-900 uppercase bg-surface-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 rounded-tl-lg">Ukuran (Size)</th>
                                    <th scope="col" class="px-4 py-3">Lebar Dada (cm)</th>
                                    <th scope="col" class="px-4 py-3 rounded-tr-lg">Panjang Badan (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">XS (Anak-anak)</td>
                                    <td class="px-4 py-3">34</td>
                                    <td class="px-4 py-3">46</td>
                                </tr>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">S (Anak-anak)</td>
                                    <td class="px-4 py-3">36</td>
                                    <td class="px-4 py-3">48</td>
                                </tr>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">M (Anak-anak)</td>
                                    <td class="px-4 py-3">38</td>
                                    <td class="px-4 py-3">50</td>
                                </tr>
                                <tr class="border-b border-surface-100 hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900">L (Anak-anak)</td>
                                    <td class="px-4 py-3">40</td>
                                    <td class="px-4 py-3">52</td>
                                </tr>
                                <tr class="hover:bg-surface-50">
                                    <td class="px-4 py-3 font-medium text-surface-900 rounded-bl-lg">XL (Anak-anak)</td>
                                    <td class="px-4 py-3">42</td>
                                    <td class="px-4 py-3 rounded-br-lg">54</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <p class="text-xs text-surface-700 mt-4 text-center">* Toleransi ukuran perbedaan 1-2 cm.</p>

                <div class="mt-8">
                    <button type="button" onclick="document.getElementById('sizeChartModal').classList.add('hidden')"
                        class="w-full py-3 bg-surface-100 hover:bg-surface-200 text-surface-900 font-medium rounded-xl transition-colors">Tutup
                        Panduan</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // ===== Shared fetch helpers =====
            async function fetchProvinces(countryId) {
                const province = document.getElementById('province');
                const city = document.getElementById('city');
                province.innerHTML = '<option value="">{{ __("messages.reg_select_province") }}</option>';
                city.innerHTML = '<option value="">{{ __("messages.reg_select_city") }}</option>';
                if (!countryId) return;
                try {
                    const res = await fetch('{{ url("/api/provinces") }}');
                    const data = await res.json();
                    data.forEach(p => { province.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
                } catch (e) { console.error('Failed to fetch provinces', e); }
            }

            async function fetchCities(provinceId) {
                const city = document.getElementById('city');
                city.innerHTML = '<option value="">{{ __("messages.reg_select_city") }}</option>';
                if (!provinceId) return;
                try {
                    const res = await fetch(`{{ url("/api/cities") }}?province_id=${provinceId}`);
                    const data = await res.json();
                    data.forEach(c => { city.innerHTML += `<option value="${c.id}">${c.name}</option>`; });
                } catch (e) { console.error('Failed to fetch cities', e); }
            }

            // ===== Cascading dropdown events =====
            document.getElementById('country')?.addEventListener('change', async function () {
                await fetchProvinces(this.value);
            });
            document.getElementById('province')?.addEventListener('change', async function () {
                await fetchCities(this.value);
            });

            document.addEventListener('DOMContentLoaded', async function () {
                const countryEl = document.getElementById('country');
                if (countryEl && countryEl.value) {
                    await fetchProvinces(countryEl.value);
                    const oldProvince = "{{ old('province_id') }}";
                    if (oldProvince) {
                        document.getElementById('province').value = oldProvince;
                        await fetchCities(oldProvince);
                        const oldCity = "{{ old('city_id') }}";
                        if (oldCity) {
                            document.getElementById('city').value = oldCity;
                        }
                    }
                }
            });

            // ===== DOB and Age Helper =====
            function calculateAndDisplayAge(dateStr) {
                const ageInput = document.getElementById('age');
                const lblAge = document.getElementById('lbl-age');
                if (!ageInput || !lblAge) return;

                if (!dateStr) {
                    ageInput.value = '';
                    lblAge.classList.add('hidden');
                    return;
                }

                const dob = new Date(dateStr);
                if (isNaN(dob.getTime())) {
                    ageInput.value = '';
                    lblAge.classList.add('hidden');
                    return;
                }

                const now = new Date();
                let age = now.getFullYear() - dob.getFullYear();
                const m = now.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && now.getDate() < dob.getDate())) {
                    age--;
                }

                ageInput.value = age;

                if (age >= 40) {
                    lblAge.classList.remove('hidden');
                } else {
                    lblAge.classList.add('hidden');
                }
            }

            // ===== Flatpickr DOB Init =====
            let fpDob = flatpickr(document.getElementById('date_of_birth'), {
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 17)),
                disableMobile: "true",
                allowInput: true,
                onChange: function (selectedDates, dateStr, instance) {
                    calculateAndDisplayAge(dateStr);
                },
                onValueUpdate: function (selectedDates, dateStr, instance) {
                    calculateAndDisplayAge(dateStr);
                }
            });

            // Re-calculate age if there's old input value on page load
            const initialDob = document.getElementById('date_of_birth').value;
            if (initialDob) {
                calculateAndDisplayAge(initialDob);
            }

            // ===== DOM references =====
            const categoryRadios = document.querySelectorAll('.category-radio');
            const categoryDetailsContainer = document.getElementById('category-details-container');
            const categoryDetails = document.querySelectorAll('.category-detail-content');

            const familyCountContainer = document.getElementById('family-count-container');
            const familyCountSelect = document.getElementById('family_count');
            const participantIndicator = document.getElementById('participant-indicator');
            const indCurrent = document.getElementById('ind-current');
            const indTotal = document.getElementById('ind-total');
            const indRole = document.getElementById('ind-role');
            const lblIdentity = document.getElementById('lbl-identity');
            const hlpIdentity = document.getElementById('hlp-identity');
            const formFieldsContainer = document.getElementById('form-fields-container');
            const reviewContainer = document.getElementById('review-container');
            const reviewContent = document.getElementById('review-content');
            const btnNextContainer = document.getElementById('btn-next-container');
            const btnNext = document.getElementById('btn-next');
            const btnPrev = document.getElementById('btn-prev');
            const btnSubmitContainer = document.getElementById('btn-submit-container');
            const btnEditData = document.getElementById('btn-edit-data');
            const btnCopyLeader = document.getElementById('btn-copy-leader-address');
            const mainForm = document.querySelector('form');

            // ===== Realtime Validation Fields =====
            const inEmail = document.querySelector('input[name="email"]');
            const inPhone = document.querySelector('input[name="phone"]');
            const inEmName = document.querySelector('input[name="emergency_contact_name"]');
            const inEmPhone = document.querySelector('input[name="emergency_contact_phone"]');

            const eEmail = document.getElementById('err-email');
            const ePhone = document.getElementById('err-phone');
            const eEmName = document.getElementById('err-em-name');
            const eEmPhone = document.getElementById('err-em-phone');

            function setInvalid(input, errorEl, msg) {
                if (!input) return;
                // Menggunakan inline style untuk menjamin warna aktif 
                // tanpa bergantung pada hasil kompilasi Tailwind css.
                input.style.borderColor = '#ef4444';
                input.style.color = '#ef4444';

                if (errorEl) {
                    errorEl.textContent = msg;
                    errorEl.classList.remove('hidden');
                }
                input.setCustomValidity(msg);
            }

            function setValid(input, errorEl) {
                if (!input) return;
                input.style.borderColor = '';
                input.style.color = '';

                if (errorEl) {
                    errorEl.classList.add('hidden');
                }
                input.setCustomValidity('');
            }

            function checkEmail() {
                if (inEmail.value && !inEmail.value.includes('@')) {
                    setInvalid(inEmail, eEmail, 'Format email tidak valid (harus mengandung @).');
                } else if (!inEmail.value) {
                    setInvalid(inEmail, eEmail, 'Email wajib diisi.');
                } else {
                    setValid(inEmail, eEmail);
                }
            }

            function checkPhone() {
                if (inPhone.value && inPhone.value.replace(/\D/g, '').length <= 9) {
                    setInvalid(inPhone, ePhone, 'Nomor HP harus lebih dari 9 digit.');
                } else if (!inPhone.value) {
                    setInvalid(inPhone, ePhone, 'Nomor HP wajib diisi.');
                } else {
                    setValid(inPhone, ePhone);
                }
            }

            function checkEmName() {
                if (!inEmName.value.trim()) {
                    setInvalid(inEmName, eEmName, 'Nama kontak darurat wajib diisi.');
                } else {
                    setValid(inEmName, eEmName);
                }
            }

            function checkEmPhone() {
                if (inEmPhone.value && inEmPhone.value.replace(/\D/g, '').length <= 9) {
                    setInvalid(inEmPhone, eEmPhone, 'Nomor HP darurat harus lebih dari 9 digit.');
                } else if (!inEmPhone.value) {
                    setInvalid(inEmPhone, eEmPhone, 'Nomor HP darurat wajib diisi.');
                } else {
                    setValid(inEmPhone, eEmPhone);
                }
            }

            if (inEmail) inEmail.addEventListener('input', checkEmail);
            if (inPhone) inPhone.addEventListener('input', checkPhone);
            if (inEmName) inEmName.addEventListener('input', checkEmName);
            if (inEmPhone) inEmPhone.addEventListener('input', checkEmPhone);

            let isFamily = false;
            let totalParticipants = 1;
            let currentParticipantIndex = 0;
            let participantsData = [];

            // ===== Save current form data to array =====
            function saveCurrentParticipantData() {
                let pData = {};
                formFieldsContainer.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el.name && el.name !== 'category_id' && el.name !== 'family_count' && !el.name.startsWith('agreement') && el.name !== 'g-recaptcha-response') {
                        if (el.type === 'checkbox' || el.type === 'radio') {
                            if (el.checked) pData[el.name] = el.value;
                        } else {
                            pData[el.name] = el.value;
                        }
                    }
                });
                if (Object.keys(pData).length > 0) {
                    participantsData[currentParticipantIndex] = pData;
                }
            }

            // ===== Load participant data into form =====
            async function loadParticipantData(index) {
                const data = participantsData[index] || {};
                const hasData = Object.keys(data).length > 0;

                // Reset validation state
                [inEmail, inPhone, inEmName, inEmPhone].forEach(el => setValid(el, null));
                if (eEmail) eEmail.classList.add('hidden');
                if (ePhone) ePhone.classList.add('hidden');
                if (eEmName) eEmName.classList.add('hidden');
                if (eEmPhone) eEmPhone.classList.add('hidden');

                // Set simple fields first
                formFieldsContainer.querySelectorAll('input, select, textarea').forEach(el => {
                    if (!el.name || el.name === 'category_id' || el.name === 'family_count') return;
                    if (el.id === 'country' || el.id === 'province' || el.id === 'city') return;
                    if (el.type === 'checkbox' || el.type === 'radio') {
                        el.checked = hasData ? (data[el.name] === el.value) : false;
                    } else {
                        el.value = hasData ? (data[el.name] || '') : '';
                        if (el.name === 'date_of_birth' && fpDob) {
                            fpDob.setDate(el.value);
                            calculateAndDisplayAge(el.value);
                        }
                    }
                });

                // Restore cascading dropdowns
                const countryEl = document.getElementById('country');
                if (hasData && data.country_id) {
                    countryEl.value = data.country_id;
                    await fetchProvinces(data.country_id);
                    if (data.province_id) {
                        document.getElementById('province').value = data.province_id;
                        await fetchCities(data.province_id);
                        if (data.city_id) {
                            document.getElementById('city').value = data.city_id;
                        }
                    }
                } else {
                    countryEl.value = '';
                    document.getElementById('province').innerHTML = '<option value="">{{ __("messages.reg_select_province") }}</option>';
                    document.getElementById('city').innerHTML = '<option value="">{{ __("messages.reg_select_city") }}</option>';
                }
            }

            // ===== Update UI for current participant =====
            async function updateUI() {
                if (isFamily) {
                    indCurrent.innerText = currentParticipantIndex + 1;
                    indTotal.innerText = totalParticipants;

                    const wrapperEmail = document.getElementById('wrapper-email');
                    const wrapperPhone = document.getElementById('wrapper-phone');
                    const wrapperNationality = document.getElementById('wrapper-nationality');
                    const sectionLocation = document.getElementById('section-location');
                    const wrapperEmergency = document.getElementById('wrapper-emergency');
                    const inNationality = document.querySelector('input[name="nationality"]');

                    // Roles & labels
                    if (currentParticipantIndex === 0) {
                        indRole.innerText = '{{ __('messages.reg_role_leader') }}';
                        lblIdentity.innerHTML = '{{ __("messages.reg_identity") }} *';
                        hlpIdentity.classList.add('hidden');
                        btnPrev.classList.add('hidden');
                        btnCopyLeader.classList.add('hidden');
                        if (fpDob) fpDob.set('maxDate', new Date(new Date().setFullYear(new Date().getFullYear() - 17)));

                        if (wrapperEmail) wrapperEmail.classList.remove('hidden');
                        if (wrapperPhone) wrapperPhone.classList.remove('hidden');
                        if (wrapperNationality) wrapperNationality.classList.remove('hidden');
                        if (sectionLocation) sectionLocation.classList.remove('hidden');
                        if (wrapperEmergency) wrapperEmergency.classList.remove('hidden');
                        if (inEmail) inEmail.setAttribute('required', 'required');
                        if (inPhone) inPhone.setAttribute('required', 'required');
                        if (inNationality) inNationality.setAttribute('required', 'required');
                        if (inEmName) inEmName.setAttribute('required', 'required');
                        if (inEmPhone) inEmPhone.setAttribute('required', 'required');
                    } else {
                        indRole.innerText = '{{ __('messages.reg_role_member') }}';
                        lblIdentity.innerHTML = 'Nomor Identitas (NIK / KIA / Passport) *';
                        hlpIdentity.classList.remove('hidden');
                        btnPrev.classList.remove('hidden');
                        btnCopyLeader.classList.remove('hidden');
                        if (fpDob) fpDob.set('maxDate', new Date(new Date().setFullYear(new Date().getFullYear() - 10)));

                        if (wrapperEmail) wrapperEmail.classList.add('hidden');
                        if (wrapperPhone) wrapperPhone.classList.add('hidden');
                        if (wrapperNationality) wrapperNationality.classList.add('hidden');
                        if (sectionLocation) sectionLocation.classList.add('hidden');
                        if (wrapperEmergency) wrapperEmergency.classList.add('hidden');
                        if (inEmail) inEmail.removeAttribute('required');
                        if (inPhone) inPhone.removeAttribute('required');
                        if (inNationality) inNationality.removeAttribute('required');
                        if (inEmName) inEmName.removeAttribute('required');
                        if (inEmPhone) inEmPhone.removeAttribute('required');
                    }

                    // Load saved data
                    await loadParticipantData(currentParticipantIndex);

                    // Button text
                    btnNext.innerHTML = (currentParticipantIndex === totalParticipants - 1) ? '{{ __('messages.reg_btn_review') }}' : '{!! __('messages.reg_btn_next_arrow') !!}';
                } else {
                    lblIdentity.innerHTML = '{{ __("messages.reg_identity") }} *';
                    hlpIdentity.classList.add('hidden');
                    btnCopyLeader.classList.add('hidden');
                    if (fpDob) fpDob.set('maxDate', new Date(new Date().setFullYear(new Date().getFullYear() - 17)));

                    const wrapperEmail = document.getElementById('wrapper-email');
                    const wrapperPhone = document.getElementById('wrapper-phone');
                    const wrapperNationality = document.getElementById('wrapper-nationality');
                    const sectionLocation = document.getElementById('section-location');
                    const wrapperEmergency = document.getElementById('wrapper-emergency');
                    const inNationality = document.querySelector('input[name="nationality"]');

                    if (wrapperEmail) wrapperEmail.classList.remove('hidden');
                    if (wrapperPhone) wrapperPhone.classList.remove('hidden');
                    if (wrapperNationality) wrapperNationality.classList.remove('hidden');
                    if (sectionLocation) sectionLocation.classList.remove('hidden');
                    if (wrapperEmergency) wrapperEmergency.classList.remove('hidden');
                    if (inEmail) inEmail.setAttribute('required', 'required');
                    if (inPhone) inPhone.setAttribute('required', 'required');
                    if (inNationality) inNationality.setAttribute('required', 'required');
                    if (inEmName) inEmName.setAttribute('required', 'required');
                    if (inEmPhone) inEmPhone.setAttribute('required', 'required');

                    // Load saved data
                    await loadParticipantData(currentParticipantIndex);

                    // Button text
                    btnNext.innerHTML = '{{ __('messages.reg_btn_review') }}';
                }
            }

            // ===== Copy Team Leader address =====
            btnCopyLeader.addEventListener('click', async function () {
                const leader = participantsData[0];
                if (!leader) return;

                // Set country
                if (leader.country_id) {
                    document.getElementById('country').value = leader.country_id;
                    await fetchProvinces(leader.country_id);
                }
                // Set province
                if (leader.province_id) {
                    document.getElementById('province').value = leader.province_id;
                    await fetchCities(leader.province_id);
                }
                // Set city
                if (leader.city_id) {
                    document.getElementById('city').value = leader.city_id;
                }
                // Set address
                const addrEl = formFieldsContainer.querySelector('input[name="address"]');
                if (addrEl && leader.address) addrEl.value = leader.address;
            });

            // ===== Category selection =====
            function updateCategoryDetails() {
                let isAnyChecked = false;
                categoryRadios.forEach(radio => {
                    if (radio.checked) {
                        isAnyChecked = true;

                        isFamily = radio.dataset.slug === '5k-family-trail';

                        btnNextContainer.classList.remove('hidden');
                        btnSubmitContainer.classList.add('hidden');

                        if (isFamily) {
                            familyCountContainer.classList.remove('hidden');
                            participantIndicator.classList.remove('hidden');
                            totalParticipants = parseInt(familyCountSelect.value);
                        } else {
                            familyCountContainer.classList.add('hidden');
                            participantIndicator.classList.add('hidden');
                            totalParticipants = 1;
                            btnCopyLeader.classList.add('hidden');
                        }

                        currentParticipantIndex = 0;
                        participantsData = [];
                        updateUI();
                        formFieldsContainer.classList.remove('hidden');
                        reviewContainer.classList.add('hidden');

                        categoryDetails.forEach(detail => detail.classList.add('hidden'));
                        const detailContent = document.getElementById('cat-detail-' + radio.value);
                        if (detailContent) detailContent.classList.remove('hidden');
                    }
                });
                if (isAnyChecked) {
                    categoryDetailsContainer.classList.remove('hidden');
                } else {
                    categoryDetailsContainer.classList.add('hidden');
                }
            }

            // ===== Family count change =====
            familyCountSelect.addEventListener('change', function () {
                saveCurrentParticipantData();
                totalParticipants = parseInt(this.value);
                // Trim extra participants if needed
                if (participantsData.length > totalParticipants) {
                    participantsData.length = totalParticipants;
                }
                if (currentParticipantIndex >= totalParticipants) {
                    currentParticipantIndex = totalParticipants - 1;
                }
                updateUI();
                formFieldsContainer.classList.remove('hidden');
                reviewContainer.classList.add('hidden');
            });

            // ===== DOB validation helper =====
            function validateDOB(input) {
                const val = input.value;
                if (!val || val.trim() === '') {
                    input.setCustomValidity('Tanggal lahir wajib diisi.');
                    input.reportValidity();
                    return false;
                }
                input.setCustomValidity('');
                return true;
            }

            // ===== Prev button =====
            btnPrev.addEventListener('click', async function () {
                saveCurrentParticipantData();
                if (currentParticipantIndex > 0) {
                    currentParticipantIndex--;
                    await updateUI();
                    window.scrollTo({ top: formFieldsContainer.offsetTop - 100, behavior: 'smooth' });
                }
            });

            // ===== Next / Review button =====
            btnNext.addEventListener('click', async function () {
                // Run custom realtime validations forcefully to catch empty inputs but only for required fields
                if (inEmail && inEmail.hasAttribute('required')) checkEmail();
                if (inPhone && inPhone.hasAttribute('required')) checkPhone();
                if (inEmName && inEmName.hasAttribute('required')) checkEmName();
                if (inEmPhone && inEmPhone.hasAttribute('required')) checkEmPhone();

                // Additional check to prevent moving forward if any error is showing and field is required
                if (eEmail && !eEmail.classList.contains('hidden') && inEmail && inEmail.hasAttribute('required')) return;
                if (ePhone && !ePhone.classList.contains('hidden') && inPhone && inPhone.hasAttribute('required')) return;
                if (eEmName && !eEmName.classList.contains('hidden') && inEmName && inEmName.hasAttribute('required')) return;
                if (eEmPhone && !eEmPhone.classList.contains('hidden') && inEmPhone && inEmPhone.hasAttribute('required')) return;

                // Validate required fields
                let inputs = formFieldsContainer.querySelectorAll('input[required], select[required], textarea[required]');
                for (let i = 0; i < inputs.length; i++) {
                    // DOB custom validation
                    if (inputs[i].name === 'date_of_birth') {
                        if (!validateDOB(inputs[i])) return;
                    }
                    if (!inputs[i].checkValidity()) {
                        inputs[i].reportValidity();
                        return;
                    }
                }

                saveCurrentParticipantData();

                if (currentParticipantIndex < totalParticipants - 1) {
                    currentParticipantIndex++;
                    await updateUI();
                    window.scrollTo({ top: formFieldsContainer.offsetTop - 100, behavior: 'smooth' });
                } else if (currentParticipantIndex === totalParticipants - 1) {
                    showReview();
                    window.scrollTo({ top: reviewContainer.offsetTop - 100, behavior: 'smooth' });
                }
            });

            // ===== Edit from Review =====
            btnEditData.addEventListener('click', async function () {
                formFieldsContainer.classList.remove('hidden');
                btnNextContainer.classList.remove('hidden');
                reviewContainer.classList.add('hidden');
                btnSubmitContainer.classList.add('hidden');
                currentParticipantIndex = 0;
                await updateUI();
            });

            // ===== Show review =====
            function showReview() {
                formFieldsContainer.classList.add('hidden');
                btnNextContainer.classList.add('hidden');
                reviewContainer.classList.remove('hidden');
                btnSubmitContainer.classList.remove('hidden');

                let hiddenContainer = document.getElementById('hidden-participants-container');
                if (!hiddenContainer) {
                    hiddenContainer = document.createElement('div');
                    hiddenContainer.id = 'hidden-participants-container';
                    mainForm.appendChild(hiddenContainer);
                }
                hiddenContainer.innerHTML = '';

                let html = '';
                participantsData.forEach((p, index) => {
                    let title = isFamily ? `Peserta ${index + 1} ${index === 0 ? '(Team Leader)' : '(Family Member)'}` : 'Data Peserta';
                    html += `<div class="p-4 border border-surface-200 rounded-xl mb-4 bg-surface-50">
                                                                                                                                                                                                    <div class="flex justify-between items-start mb-2">
                                                                                                                                                                                                        <h4 class="font-bold text-brand-600">${title}</h4>
                                                                                                                                                                                                        <button type="button" onclick="editParticipant(${index})" class="text-xs px-3 py-1 bg-brand-50 text-brand-600 border border-brand-200 rounded-lg hover:bg-brand-100 transition-colors font-medium">✏️ Edit</button>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                                                                                                                                                                                        <p><span class="text-surface-600">Nama:</span> <br><span class="font-medium text-surface-900">${p.full_name || '-'}</span></p>
                                                                                                                                                                                                        <p><span class="text-surface-600">Tanggal Lahir:</span> <br><span class="font-medium text-surface-900">${p.date_of_birth || '-'}</span></p>
                                                                                                                                                                                                        <p><span class="text-surface-600">Email:</span> <br><span class="font-medium text-surface-900 break-all">${p.email || '-'}</span></p>
                                                                                                                                                                                                        <p><span class="text-surface-600">Telepon:</span> <br><span class="font-medium text-surface-900">${p.phone || '-'}</span></p>
                                                                                                                                                                                                        <p><span class="text-surface-600">Identitas:</span> <br><span class="font-medium text-surface-900 break-all">${p.identity_number || '-'}</span></p>
                                                                                                                                                                                                        <p><span class="text-surface-600">Jersey:</span> <br><span class="font-medium text-surface-900">${p.jersey_size || '-'}</span></p>
                                                                                                                                                                                                        <p><span class="text-surface-600">{{ __('messages.reg_blood_type') }}:</span> <br><span class="font-medium text-surface-900">${p.blood_type || '-'}</span></p>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>`;

                    for (let key in p) {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `participants[${index}][${key}]`;
                        input.value = p[key];
                        hiddenContainer.appendChild(input);
                    }
                });
                reviewContent.innerHTML = html;
            }

            // ===== Edit specific participant from review =====
            async function editParticipant(index) {
                formFieldsContainer.classList.remove('hidden');
                btnNextContainer.classList.remove('hidden');
                reviewContainer.classList.add('hidden');
                btnSubmitContainer.classList.add('hidden');
                currentParticipantIndex = index;
                await updateUI();
                window.scrollTo({ top: formFieldsContainer.offsetTop - 100, behavior: 'smooth' });
            }
            // Expose to onclick
            window.editParticipant = editParticipant;

            // ===== Init =====
            categoryRadios.forEach(radio => {
                radio.addEventListener('change', updateCategoryDetails);
            });

            updateCategoryDetails();
        </script>
    @endpush
@endsection