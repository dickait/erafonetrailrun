@extends('layouts.public')
@section('title', __('messages.reg_badge') . ' - ERA TRAIL RUN 2026')
@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                                        <input type="radio" name="category_id" value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" class="category-radio hidden peer"
                                            {{ old('category_id', request('category')) == $cat->id ? 'checked' : '' }}>
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
                                    $usia = $cat->slug == '5k-family-trail' ? '7+' : '18+';
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
                                                <li class="flex items-start gap-2"><svg
                                                        class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>{{ __('messages.categories_item_cert') }}</li>
                                                <li class="flex items-start gap-2"><svg
                                                        class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>{{ __('messages.categories_item_timing') }}</li>
                                                @if($cat->slug === '15k')
                                                    <li class="flex items-start gap-2"><svg
                                                            class="w-4 h-4 text-accent-500 mt-0.5 flex-shrink-0" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg><span
                                                            class="text-accent-600 font-medium">{{ __('messages.categories_item_finisher_tee') }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Family Participant Count -->
                        <div id="family-count-container" class="mt-6 border-t border-surface-300 pt-6 hidden">
                            <label class="block text-sm font-medium text-surface-800 mb-2">Jumlah Peserta (2-4 orang) *</label>
                            <select id="family_count" name="family_count"
                                class="w-full sm:w-1/2 px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                <option value="2">2 Peserta</option>
                                <option value="3">3 Peserta</option>
                                <option value="4">4 Peserta</option>
                            </select>
                        </div>
                    </div>

                    <!-- Main Form Fields Container -->
                    <div id="form-fields-container" class="space-y-8">
                        <!-- Family Indicator (Sticky) -->
                        <div id="participant-indicator"
                            class="hidden p-4 rounded-xl bg-brand-50 border border-brand-200 text-center sticky top-20 z-30 shadow-md transition-shadow">
                            <h4 class="font-display font-bold text-brand-600 text-lg">Peserta <span
                                    id="ind-current">1</span> dari <span id="ind-total">2</span></h4>
                            <p id="ind-role" class="text-brand-500 font-medium text-sm">Team Leader (Kontak Utama)</p>
                        </div>

                        <!-- Personal Information -->
                        <div class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
                            <h3 class="font-display font-semibold text-lg text-surface-900">
                                {{ __('messages.reg_personal_info') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_full_name') }}
                                        *</label>
                                    <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                        placeholder="{{ __('messages.reg_full_name') }}">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_bib_name') }}
                                        (Maks 15) *</label>
                                    <input type="text" name="bib_name" value="{{ old('bib_name') }}" required maxlength="15"
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                        placeholder="Name on BIB">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_email') }}
                                        *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                        placeholder="your@email.com">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_phone') }}
                                        *</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                        placeholder="08xxxxxxxxx">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
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
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_dob') }}
                                        (DD/MM/YYYY) *</label>
                                    <input type="text" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                        placeholder="DD/MM/YYYY" maxlength="10">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
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
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_nationality') }}
                                        *</label>
                                    <input type="text" name="nationality" value="{{ old('nationality', 'Indonesia') }}" required
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
                            <div class="flex justify-between items-center">
                                <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.reg_location') }}</h3>
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
                                        @foreach($countries as $country)<option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
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
                            <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.reg_additional') }}
                            </h3>

                            <div>
                                <div class="flex justify-between items-end mb-1.5">
                                    <label
                                        class="block text-sm font-medium text-surface-800">{{ __('messages.reg_jersey_size') }}</label>
                                    <button type="button"
                                        onclick="document.getElementById('sizeChartModal').classList.remove('hidden')"
                                        class="text-xs text-brand-500 hover:text-brand-600 underline font-medium">Panduan / Size
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
                                        <p><span class="font-bold text-surface-700">B. Panjang:</span> Dari kerah ke bawah.</p>
                                    </div>
                                </div>
                                <select name="jersey_size"
                                    class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                    <option value="">{{ __('messages.reg_select') }}</option>
                                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $sz)<option value="{{ $sz }}" {{ old('jersey_size') == $sz ? 'selected' : '' }}>{{ $sz }}</option>@endforeach
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

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_emergency_name') }}</label>
                                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_emergency_phone') }}</label>
                                    <input type="text" name="emergency_contact_phone"
                                        value="{{ old('emergency_contact_phone') }}"
                                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
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
                            <h3 class="font-display font-semibold text-lg text-surface-900 mb-4">Review Data Peserta</h3>
                            <div id="review-content" class="space-y-4"></div>
                            <button type="button" id="btn-edit-data"
                                class="mt-6 px-6 py-2 bg-surface-100 hover:bg-surface-200 text-surface-700 font-medium rounded-xl transition-colors text-sm">
                                &larr; Perbaiki Data
                            </button>
                        </div>
                    </div>

                    <div id="btn-next-container" class="hidden flex gap-3">
                        <button type="button" id="btn-prev"
                            class="hidden flex-1 py-4 bg-surface-100 text-surface-700 font-bold rounded-2xl hover:bg-surface-200 transition-all duration-200 text-lg">
                            &larr; Kembali
                        </button>
                        <button type="button" id="btn-next"
                            class="flex-[2] py-4 bg-white text-brand-600 border-2 border-brand-500 hover:bg-brand-50 font-bold rounded-2xl transition-all duration-200 text-lg">
                            Lanjutkan
                        </button>
                    </div>

                    <div id="btn-submit-container">
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
                    <!-- Men Size Chart -->
                    <div class="mb-8">
                        <h4 class="text-lg font-bold text-brand-500 mb-4 border-l-4 border-brand-500 pl-3">Laki-laki (Men's
                            Size)</h4>
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

                    <!-- Women Size Chart -->
                    <div>
                        <h4 class="text-lg font-bold text-accent-600 mb-4 border-l-4 border-accent-500 pl-3">Perempuan (Women's
                            Size)</h4>
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
                                        <td class="px-4 py-3">42</td>
                                        <td class="px-4 py-3">60</td>
                                    </tr>
                                    <tr class="border-b border-surface-100 hover:bg-surface-50">
                                        <td class="px-4 py-3 font-medium text-surface-900">S</td>
                                        <td class="px-4 py-3">44</td>
                                        <td class="px-4 py-3">62</td>
                                    </tr>
                                    <tr class="border-b border-surface-100 hover:bg-surface-50">
                                        <td class="px-4 py-3 font-medium text-surface-900">M</td>
                                        <td class="px-4 py-3">46</td>
                                        <td class="px-4 py-3">64</td>
                                    </tr>
                                    <tr class="border-b border-surface-100 hover:bg-surface-50">
                                        <td class="px-4 py-3 font-medium text-surface-900">L</td>
                                        <td class="px-4 py-3">48</td>
                                        <td class="px-4 py-3">66</td>
                                    </tr>
                                    <tr class="border-b border-surface-100 hover:bg-surface-50">
                                        <td class="px-4 py-3 font-medium text-surface-900">XL</td>
                                        <td class="px-4 py-3">50</td>
                                        <td class="px-4 py-3">68</td>
                                    </tr>
                                    <tr class="hover:bg-surface-50">
                                        <td class="px-4 py-3 font-medium text-surface-900 rounded-bl-lg">XXL</td>
                                        <td class="px-4 py-3">52</td>
                                        <td class="px-4 py-3 rounded-br-lg">70</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-surface-700 mt-4 text-center">* Toleransi ukuran perbedaan 1-2 cm.</p>
                    </div>

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
                        const res = await fetch('/api/provinces');
                        const data = await res.json();
                        data.forEach(p => { province.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
                    } catch(e) { console.error('Failed to fetch provinces', e); }
                }

                async function fetchCities(provinceId) {
                    const city = document.getElementById('city');
                    city.innerHTML = '<option value="">{{ __("messages.reg_select_city") }}</option>';
                    if (!provinceId) return;
                    try {
                        const res = await fetch(`/api/cities?province_id=${provinceId}`);
                        const data = await res.json();
                        data.forEach(c => { city.innerHTML += `<option value="${c.id}">${c.name}</option>`; });
                    } catch(e) { console.error('Failed to fetch cities', e); }
                }

                // ===== Cascading dropdown events =====
                document.getElementById('country')?.addEventListener('change', async function () {
                    await fetchProvinces(this.value);
                });
                document.getElementById('province')?.addEventListener('change', async function () {
                    await fetchCities(this.value);
                });

                // ===== DOB auto-format (DD/MM/YYYY) =====
                document.querySelector('input[name="date_of_birth"]')?.addEventListener('input', function(e) {
                    let v = this.value.replace(/[^0-9]/g, '');
                    if(v.length > 8) v = v.substring(0, 8);
                    if(v.length >= 5) {
                        this.value = v.substring(0,2) + '/' + v.substring(2,4) + '/' + v.substring(4);
                    } else if(v.length >= 3) {
                        this.value = v.substring(0,2) + '/' + v.substring(2);
                    } else {
                        this.value = v;
                    }
                });

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

                let isFamily = false;
                let totalParticipants = 1;
                let currentParticipantIndex = 0;
                let participantsData = [];

                // ===== Save current form data to array =====
                function saveCurrentParticipantData() {
                    let pData = {};
                    formFieldsContainer.querySelectorAll('input, select, textarea').forEach(el => {
                        if(el.name && el.name !== 'category_id' && el.name !== 'family_count' && !el.name.startsWith('agreement') && el.name !== 'g-recaptcha-response') {
                            if(el.type === 'checkbox' || el.type === 'radio') {
                                if(el.checked) pData[el.name] = el.value;
                            } else {
                                pData[el.name] = el.value;
                            }
                        }
                    });
                    if(Object.keys(pData).length > 0) {
                        participantsData[currentParticipantIndex] = pData;
                    }
                }

                // ===== Load participant data into form =====
                async function loadParticipantData(index) {
                    const data = participantsData[index] || {};
                    const hasData = Object.keys(data).length > 0;

                    // Set simple fields first
                    formFieldsContainer.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(el => {
                        if(el.name === 'category_id' || el.name === 'family_count') return;
                        if(el.id === 'country' || el.id === 'province' || el.id === 'city') return;
                        if(el.type === 'checkbox' || el.type === 'radio') {
                            el.checked = hasData ? (data[el.name] === el.value) : false;
                        } else {
                            el.value = hasData ? (data[el.name] || '') : '';
                        }
                    });

                    // Restore cascading dropdowns
                    const countryEl = document.getElementById('country');
                    if(hasData && data.country_id) {
                        countryEl.value = data.country_id;
                        await fetchProvinces(data.country_id);
                        if(data.province_id) {
                            document.getElementById('province').value = data.province_id;
                            await fetchCities(data.province_id);
                            if(data.city_id) {
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
                    if(isFamily) {
                        indCurrent.innerText = currentParticipantIndex + 1;
                        indTotal.innerText = totalParticipants;

                        // Roles & labels
                        if(currentParticipantIndex === 0) {
                            indRole.innerText = 'Team Leader (Kontak Utama)';
                            lblIdentity.innerHTML = '{{ __("messages.reg_identity") }} *';
                            hlpIdentity.classList.add('hidden');
                            btnPrev.classList.add('hidden');
                            btnCopyLeader.classList.add('hidden');
                        } else {
                            indRole.innerText = 'Family Member';
                            lblIdentity.innerHTML = 'Nomor Identitas (NIK / KIA / Passport) *';
                            hlpIdentity.classList.remove('hidden');
                            btnPrev.classList.remove('hidden');
                            btnCopyLeader.classList.remove('hidden');
                        }

                        // Load saved data
                        await loadParticipantData(currentParticipantIndex);

                        // Button text
                        btnNext.innerHTML = (currentParticipantIndex === totalParticipants - 1) ? 'Review Data' : 'Lanjutkan &rarr;';
                    } else {
                        lblIdentity.innerHTML = '{{ __("messages.reg_identity") }} *';
                        hlpIdentity.classList.add('hidden');
                        btnCopyLeader.classList.add('hidden');
                    }
                }

                // ===== Copy Team Leader address =====
                btnCopyLeader.addEventListener('click', async function() {
                    const leader = participantsData[0];
                    if(!leader) return;

                    // Set country
                    if(leader.country_id) {
                        document.getElementById('country').value = leader.country_id;
                        await fetchProvinces(leader.country_id);
                    }
                    // Set province
                    if(leader.province_id) {
                        document.getElementById('province').value = leader.province_id;
                        await fetchCities(leader.province_id);
                    }
                    // Set city
                    if(leader.city_id) {
                        document.getElementById('city').value = leader.city_id;
                    }
                    // Set address
                    const addrEl = formFieldsContainer.querySelector('input[name="address"]');
                    if(addrEl && leader.address) addrEl.value = leader.address;
                });

                // ===== Category selection =====
                function updateCategoryDetails() {
                    let isAnyChecked = false;
                    categoryRadios.forEach(radio => {
                        if (radio.checked) {
                            isAnyChecked = true;

                            isFamily = radio.dataset.slug === '5k-family-trail';
                            if(isFamily) {
                                familyCountContainer.classList.remove('hidden');
                                participantIndicator.classList.remove('hidden');
                                btnNextContainer.classList.remove('hidden');
                                btnSubmitContainer.classList.add('hidden');
                                totalParticipants = parseInt(familyCountSelect.value);
                            } else {
                                familyCountContainer.classList.add('hidden');
                                participantIndicator.classList.add('hidden');
                                btnNextContainer.classList.add('hidden');
                                btnSubmitContainer.classList.remove('hidden');
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
                familyCountSelect.addEventListener('change', function() {
                    saveCurrentParticipantData();
                    totalParticipants = parseInt(this.value);
                    // Trim extra participants if needed
                    if(participantsData.length > totalParticipants) {
                        participantsData.length = totalParticipants;
                    }
                    if(currentParticipantIndex >= totalParticipants) {
                        currentParticipantIndex = totalParticipants - 1;
                    }
                    updateUI();
                    formFieldsContainer.classList.remove('hidden');
                    reviewContainer.classList.add('hidden');
                });

                // ===== DOB validation helper =====
                function validateDOB(input) {
                    const val = input.value;
                    if(!/^\d{2}\/\d{2}\/\d{4}$/.test(val)) {
                        input.setCustomValidity('Format harus DD/MM/YYYY');
                        input.reportValidity();
                        return false;
                    }
                    const parts = val.split('/');
                    const day = parseInt(parts[0]), month = parseInt(parts[1]), year = parseInt(parts[2]);
                    if(month < 1 || month > 12 || day < 1 || day > 31 || year < 1900 || year > 2026) {
                        input.setCustomValidity('Tanggal tidak valid');
                        input.reportValidity();
                        return false;
                    }
                    input.setCustomValidity('');
                    return true;
                }

                // ===== Prev button =====
                btnPrev.addEventListener('click', async function() {
                    saveCurrentParticipantData();
                    if(currentParticipantIndex > 0) {
                        currentParticipantIndex--;
                        await updateUI();
                        window.scrollTo({top: formFieldsContainer.offsetTop - 100, behavior: 'smooth'});
                    }
                });

                // ===== Next / Review button =====
                btnNext.addEventListener('click', async function() {
                    // Validate required fields
                    let inputs = formFieldsContainer.querySelectorAll('input[required], select[required], textarea[required]');
                    for(let i=0; i<inputs.length; i++) {
                        // DOB custom validation
                        if(inputs[i].name === 'date_of_birth') {
                            if(!validateDOB(inputs[i])) return;
                        }
                        if(!inputs[i].checkValidity()) {
                            inputs[i].reportValidity();
                            return;
                        }
                    }

                    saveCurrentParticipantData();

                    if (currentParticipantIndex < totalParticipants - 1) {
                        currentParticipantIndex++;
                        await updateUI();
                        window.scrollTo({top: formFieldsContainer.offsetTop - 100, behavior: 'smooth'});
                    } else if (currentParticipantIndex === totalParticipants - 1) {
                        showReview();
                        window.scrollTo({top: reviewContainer.offsetTop - 100, behavior: 'smooth'});
                    }
                });

                // ===== Edit from Review =====
                btnEditData.addEventListener('click', async function() {
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
                    if(!hiddenContainer) {
                        hiddenContainer = document.createElement('div');
                        hiddenContainer.id = 'hidden-participants-container';
                        mainForm.appendChild(hiddenContainer);
                    }
                    hiddenContainer.innerHTML = '';

                    let html = '';
                    participantsData.forEach((p, index) => {
                        html += `<div class="p-4 border border-surface-200 rounded-xl mb-4 bg-surface-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-brand-600">Peserta ${index+1} ${index===0 ? '(Team Leader)' : '(Family Member)'}</h4>
                                <button type="button" onclick="editParticipant(${index})" class="text-xs px-3 py-1 bg-brand-50 text-brand-600 border border-brand-200 rounded-lg hover:bg-brand-100 transition-colors font-medium">✏️ Edit</button>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <p><span class="text-surface-600">Nama:</span> <br><span class="font-medium text-surface-900">${p.full_name || '-'}</span></p>
                                <p><span class="text-surface-600">Tanggal Lahir:</span> <br><span class="font-medium text-surface-900">${p.date_of_birth || '-'}</span></p>
                                <p><span class="text-surface-600">Email:</span> <br><span class="font-medium text-surface-900">${p.email || '-'}</span></p>
                                <p><span class="text-surface-600">Telepon:</span> <br><span class="font-medium text-surface-900">${p.phone || '-'}</span></p>
                                <p><span class="text-surface-600">Identitas:</span> <br><span class="font-medium text-surface-900">${p.identity_number || '-'}</span></p>
                                <p><span class="text-surface-600">Jersey:</span> <br><span class="font-medium text-surface-900">${p.jersey_size || '-'}</span></p>
                            </div>
                        </div>`;

                        for(let key in p) {
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
                    window.scrollTo({top: formFieldsContainer.offsetTop - 100, behavior: 'smooth'});
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