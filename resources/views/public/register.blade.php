@extends('layouts.public')
@section('title', 'Register - Erafone Trail Run 2026')
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="inline-block px-3 py-1 text-xs font-semibold text-forest-400 uppercase tracking-wider bg-forest-900/40 rounded-full mb-4">Registration</span>
            <h1 class="font-display font-bold text-3xl sm:text-4xl text-white mb-2">Register for {{ $event->name }}</h1>
            <p class="text-gray-400">Fill in your details below to secure your spot</p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-900/30 border border-red-700/50 rounded-xl">
            <ul class="list-disc list-inside text-red-300 text-sm space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Category Selection -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
                <h3 class="font-display font-semibold text-lg text-white mb-4">Select Category</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($categories as $cat)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="category_id" value="{{ $cat->id }}" class="peer sr-only" {{ old('category_id') == $cat->id ? 'checked' : '' }} required>
                        <div class="p-4 rounded-xl border-2 border-dark-600 peer-checked:border-forest-500 bg-dark-700/50 peer-checked:bg-forest-900/20 transition-all text-center hover:border-forest-700/50">
                            <div class="font-display font-bold text-2xl mb-1" style="color: {{ $cat->color }}">{{ $cat->distance_km }}K</div>
                            <div class="text-sm text-gray-400">{{ $cat->name }}</div>
                            <div class="text-xs text-forest-400 font-semibold mt-1">Rp {{ number_format($cat->getCurrentPrice(), 0, ',', '.') }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Personal Info -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
                <h3 class="font-display font-semibold text-lg text-white mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Full Name *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="Enter your full name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Phone *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Gender *</label>
                        <select name="gender" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Date of Birth *</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Identity Number (KTP/Passport)</label>
                        <input type="text" name="identity_number" value="{{ old('identity_number') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="ID Number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Nationality *</label>
                        <input type="text" name="nationality" value="{{ old('nationality', 'Indonesia') }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
                <h3 class="font-display font-semibold text-lg text-white mb-4">Location</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Country</label>
                        <select name="country_id" id="country_id" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">Select Country</option>
                            @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ old('country_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Province</label>
                        <select name="province_id" id="province_id" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">Select Province</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">City</label>
                        <select name="city_id" id="city_id" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="Street address">
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
                <h3 class="font-display font-semibold text-lg text-white mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Blood Type</label>
                        <select name="blood_type" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">Select</option>
                            @foreach(['A','B','AB','O'] as $bt)
                            <option value="{{ $bt }}" {{ old('blood_type')==$bt?'selected':'' }}>{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Jersey Size</label>
                        <select name="jersey_size" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                            <option value="">Select</option>
                            @foreach(['XS','S','M','L','XL','XXL'] as $sz)
                            <option value="{{ $sz }}" {{ old('jersey_size')==$sz?'selected':'' }}>{{ $sz }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Running Community</label>
                        <input type="text" name="community" value="{{ old('community') }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="e.g. Indorunners">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Medical Conditions</label>
                        <textarea name="medical_conditions" rows="3" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors resize-none" placeholder="Any allergies or medical conditions we should know about">{{ old('medical_conditions') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold rounded-xl shadow-lg shadow-forest-500/25 hover:shadow-forest-500/40 transition-all duration-200 text-lg">
                Submit Registration
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.getElementById('country_id')?.addEventListener('change', function() {
        fetch(`/api/provinces?country_id=${this.value}`)
            .then(r => r.json())
            .then(data => {
                const el = document.getElementById('province_id');
                el.innerHTML = '<option value="">Select Province</option>';
                data.forEach(p => el.innerHTML += `<option value="${p.id}">${p.name}</option>`);
                document.getElementById('city_id').innerHTML = '<option value="">Select City</option>';
            });
    });
    document.getElementById('province_id')?.addEventListener('change', function() {
        fetch(`/api/cities?province_id=${this.value}`)
            .then(r => r.json())
            .then(data => {
                const el = document.getElementById('city_id');
                el.innerHTML = '<option value="">Select City</option>';
                data.forEach(c => el.innerHTML += `<option value="${c.id}">${c.name}</option>`);
            });
    });
</script>
@endpush
