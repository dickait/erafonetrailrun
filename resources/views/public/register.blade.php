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
                                <input type="radio" name="category_id" value="{{ $cat->id }}" class="category-radio hidden peer"
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
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
                    <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.reg_personal_info') }}
                    </h3>
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
                            <label class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_email') }}
                                *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                placeholder="your@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_phone') }}
                                *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                placeholder="08xxxxxxxxx">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_gender') }}
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
                            <label class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_dob') }}
                                *</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label
                                class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.reg_identity') }}
                                *</label>
                            <input type="text" name="identity_number" value="{{ old('identity_number') }}" required
                                class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                                placeholder="KTP / Passport Number">
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
                    <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.reg_location') }}</h3>
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
                    <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.reg_additional') }}</h3>

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
                                <p class="mb-1"><span class="font-bold text-surface-700">A. Lebar Dada:</span> Dari ketiak
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
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
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

                    <div class="pt-4 border-t border-surface-300">
                        <div class="g-recaptcha" data-theme="light"
                            data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold rounded-2xl shadow-xl shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 text-lg">
                    {{ __('messages.reg_submit') }}
                </button>
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
            document.getElementById('country')?.addEventListener('change', async function () {
                const province = document.getElementById('province');
                const city = document.getElementById('city');
                province.innerHTML = '<option value="">{{ __("messages.reg_select_province") }}</option>';
                city.innerHTML = '<option value="">{{ __("messages.reg_select_city") }}</option>';
                if (this.value) {
                    const res = await fetch(`/api/provinces?country_id=${this.value}`);
                    const data = await res.json();
                    data.forEach(p => { province.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
                }
            });
            document.getElementById('province')?.addEventListener('change', async function () {
                const city = document.getElementById('city');
                city.innerHTML = '<option value="">{{ __("messages.reg_select_city") }}</option>';
                if (this.value) {
                    const res = await fetch(`/api/cities?province_id=${this.value}`);
                    const data = await res.json();
                    data.forEach(c => { city.innerHTML += `<option value="${c.id}">${c.name}</option>`; });
                }
            });

            const categoryRadios = document.querySelectorAll('.category-radio');
            const categoryDetailsContainer = document.getElementById('category-details-container');
            const categoryDetails = document.querySelectorAll('.category-detail-content');

            function updateCategoryDetails() {
                let isAnyChecked = false;
                categoryRadios.forEach(radio => {
                    if (radio.checked) {
                        isAnyChecked = true;
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

            categoryRadios.forEach(radio => {
                radio.addEventListener('change', updateCategoryDetails);
            });

            // Run on init
            updateCategoryDetails();
        </script>
    @endpush
@endsection