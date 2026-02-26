@extends('layouts.participant')
@section('title', __('messages.part_profile') . ' - Erafone Trail Run')
@section('content')
<div class="mb-8">
    <h1 class="font-display font-bold text-2xl text-white mb-2">{{ __('messages.part_my_profile') }}</h1>
</div>

@if(!$participant)
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-8 text-center text-gray-400">
    {{ __('messages.part_no_registration') }}
</div>
@else
<form method="POST" action="{{ route('participant.profile.update') }}" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.reg_full_name') }}</label>
                <input type="text" name="full_name" value="{{ old('full_name', $participant->full_name) }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $participant->phone) }}" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_blood_type') }}</label>
                <select name="blood_type" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    <option value="">{{ __('messages.reg_select') }}</option>
                    @foreach(['A','B','AB','O'] as $bt)<option value="{{ $bt }}" {{ old('blood_type', $participant->blood_type) == $bt ? 'selected' : '' }}>{{ $bt }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_jersey_size') }}</label>
                <select name="jersey_size" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                    <option value="">{{ __('messages.reg_select') }}</option>
                    @foreach(['XS','S','M','L','XL','XXL'] as $sz)<option value="{{ $sz }}" {{ old('jersey_size', $participant->jersey_size) == $sz ? 'selected' : '' }}>{{ $sz }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_community') }}</label>
                <input type="text" name="community" value="{{ old('community', $participant->community) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_emergency_name') }}</label>
                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $participant->emergency_contact_name) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_emergency_phone') }}</label>
                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $participant->emergency_contact_phone) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_address') }}</label>
            <input type="text" name="address" value="{{ old('address', $participant->address) }}" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.part_medical') }}</label>
            <textarea name="medical_conditions" rows="3" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">{{ old('medical_conditions', $participant->medical_conditions) }}</textarea>
        </div>
        
        <div class="pt-4 border-t border-dark-700">
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-forest-600 to-forest-500 text-white font-semibold rounded-xl hover:from-forest-500 hover:to-forest-400 transition-all shadow-lg shadow-forest-500/20">{{ __('messages.part_update') }}</button>
        </div>
    </div>
</form>
@endif
@endsection
