@extends('layouts.public')
@section('title', __('messages.reg_badge') . ' - Erafone Trail Run 2026')
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="inline-block px-4 py-1.5 bg-forest-900/40 text-forest-400 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.reg_badge') }}</span>
            <h1 class="font-display font-bold text-3xl md:text-4xl text-white mb-2">{{ __('messages.reg_title') }} {{ $event->name ?? 'Erafone Trail Run 2026' }}</h1>
            <p class="text-gray-400">{{ __('messages.reg_subtitle') }}</p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-900/30 border border-red-700/50 rounded-xl">
            <ul class="text-sm text-red-300 space-y-1">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 p-4 bg-forest-900/30 border border-forest-700/50 rounded-xl text-forest-300">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-8">
            @csrf
            <!-- Category Selection -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
                <h3 class="font-display font-semibold text-lg text-white mb-4">{{ __('messages.reg_select_category') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($categories as $cat)
                    <label class="cursor-pointer">
                        <input type="radio" name="category_id" value="{{ $cat->id }}" class="hidden peer" {{ old('category_id', request('category')) == $cat->id ? 'checked' : '' }}>
                        <div class="border border-dark-600 rounded-xl p-4 text-center transition-all peer-checked:border-forest-500 peer-checked:bg-forest-900/20 hover:border-forest-700">
                            <p class="font-display font-bold text-xl {{ $loop->index == 0 ? 'text-amber-400' : ($loop->index == 1 ? 'text-red-400' : 'text-forest-400') }}">{{ strtoupper(explode(' ', $cat->name)[0]) }}</p>
                            <p class="text-sm text-gray-400">{{ $cat->name }}</p>
                            <p class="text-sm text-forest-400 font-medium">Rp {{ number_format($cat->getCurrentPrice(), 0, ',', '.') }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 space-y-5">
                <h3 class="font-display font-semibold text-lg text-white">{{ __('messages.reg_personal_info') }}</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_full_name') }} *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="{{ __('messages.reg_full_name') }}">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_email') }} *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_phone') }} *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="08xxxxxxxxx">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_gender') }} *</label>
                        <select name="gender" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">{{ __('messages.reg_gender_select') }}</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('messages.reg_gender_male') }}</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('messages.reg_gender_female') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_dob') }} *</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_identity') }}</label>
                        <input type="text" name="identity_number" value="{{ old('identity_number') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="ID Number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_nationality') }} *</label>
                        <input type="text" name="nationality" value="{{ old('nationality', 'Indonesia') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 space-y-5">
                <h3 class="font-display font-semibold text-lg text-white">{{ __('messages.reg_location') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_country') }}</label>
                        <select name="country_id" id="country" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">{{ __('messages.reg_select_country') }}</option>
                            @foreach($countries as $country)<option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_province') }}</label>
                        <select name="province_id" id="province" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">{{ __('messages.reg_select_province') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_city') }}</label>
                        <select name="city_id" id="city" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">{{ __('messages.reg_select_city') }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_address') }}</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 space-y-5">
                <h3 class="font-display font-semibold text-lg text-white">{{ __('messages.reg_additional') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_blood_type') }}</label>
                        <select name="blood_type" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">{{ __('messages.reg_select') }}</option>
                            @foreach(['A','B','AB','O'] as $bt)<option value="{{ $bt }}" {{ old('blood_type') == $bt ? 'selected' : '' }}>{{ $bt }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_jersey_size') }}</label>
                        <select name="jersey_size" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">{{ __('messages.reg_select') }}</option>
                            @foreach(['XS','S','M','L','XL','XXL'] as $sz)<option value="{{ $sz }}" {{ old('jersey_size') == $sz ? 'selected' : '' }}>{{ $sz }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_emergency_name') }}</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_emergency_phone') }}</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_community') }}</label>
                    <input type="text" name="community" value="{{ old('community') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_medical') }}</label>
                    <textarea name="medical_conditions" rows="3" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="{{ __('messages.reg_medical_placeholder') }}">{{ old('medical_conditions') }}</textarea>
                </div>
            </div>

            <!-- Agreements & Recaptcha -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 space-y-5">
                <div class="space-y-4">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="flex-shrink-0 mt-1">
                            <input type="checkbox" name="agreement_1" required class="w-5 h-5 rounded border-dark-600 bg-dark-700 text-forest-500 focus:ring-forest-500 focus:ring-offset-dark-800">
                        </div>
                        <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ __('messages.reg_agreement_1') }} *</span>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="flex-shrink-0 mt-1">
                            <input type="checkbox" name="agreement_2" required class="w-5 h-5 rounded border-dark-600 bg-dark-700 text-forest-500 focus:ring-forest-500 focus:ring-offset-dark-800">
                        </div>
                        <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ __('messages.reg_agreement_2') }} *</span>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="flex-shrink-0 mt-1">
                            <input type="checkbox" name="agreement_3" required class="w-5 h-5 rounded border-dark-600 bg-dark-700 text-forest-500 focus:ring-forest-500 focus:ring-offset-dark-800">
                        </div>
                        <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ __('messages.reg_agreement_3') }} *</span>
                    </label>
                </div>
                
                <div class="pt-4 border-t border-dark-700">
                    <div class="g-recaptcha" data-theme="dark" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold rounded-2xl shadow-xl shadow-forest-500/25 hover:shadow-forest-500/40 transition-all duration-200 text-lg">
                {{ __('messages.reg_submit') }}
            </button>
        </form>
    </div>
</section>

@push('scripts')
<script>
    document.getElementById('country')?.addEventListener('change', async function() {
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
    document.getElementById('province')?.addEventListener('change', async function() {
        const city = document.getElementById('city');
        city.innerHTML = '<option value="">{{ __("messages.reg_select_city") }}</option>';
        if (this.value) {
            const res = await fetch(`/api/cities?province_id=${this.value}`);
            const data = await res.json();
            data.forEach(c => { city.innerHTML += `<option value="${c.id}">${c.name}</option>`; });
        }
    });
</script>
@endpush
@endsection
