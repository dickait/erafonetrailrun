@extends('layouts.participant')
@section('title', 'My Profile')
@section('content')
@if($participant)
<div class="max-w-2xl">
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
        <h2 class="font-display font-semibold text-lg mb-6">My Profile</h2>

        <!-- Read-only fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div><label class="text-xs text-gray-500">Full Name</label><div class="text-white font-medium mt-1">{{ $participant->full_name }}</div></div>
            <div><label class="text-xs text-gray-500">Email</label><div class="text-white font-medium mt-1">{{ $participant->email }}</div></div>
            <div><label class="text-xs text-gray-500">Gender</label><div class="text-white font-medium mt-1">{{ ucfirst($participant->gender) }}</div></div>
            <div><label class="text-xs text-gray-500">Date of Birth</label><div class="text-white font-medium mt-1">{{ $participant->date_of_birth->format('d M Y') }}</div></div>
        </div>

        <hr class="border-dark-600 my-6">

        <!-- Editable fields -->
        <form action="{{ route('participant.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $participant->phone) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Blood Type</label>
                    <select name="blood_type" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
                        <option value="">Select</option>
                        @foreach(['A','B','AB','O'] as $bt)
                        <option value="{{ $bt }}" {{ $participant->blood_type==$bt?'selected':'' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Jersey Size</label>
                    <select name="jersey_size" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
                        <option value="">Select</option>
                        @foreach(['XS','S','M','L','XL','XXL'] as $sz)
                        <option value="{{ $sz }}" {{ $participant->jersey_size==$sz?'selected':'' }}>{{ $sz }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Community</label>
                    <input type="text" name="community" value="{{ old('community', $participant->community) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Emergency Contact Name</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $participant->emergency_contact_name) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Emergency Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $participant->emergency_contact_phone) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Address</label>
                    <input type="text" name="address" value="{{ old('address', $participant->address) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Medical Conditions</label>
                    <textarea name="medical_conditions" rows="3" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 resize-none">{{ old('medical_conditions', $participant->medical_conditions) }}</textarea>
                </div>
            </div>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-forest-600 to-forest-500 text-white font-semibold rounded-xl hover:from-forest-500 hover:to-forest-400 transition-all">
                Update Profile
            </button>
        </form>
    </div>
</div>
@endif
@endsection
