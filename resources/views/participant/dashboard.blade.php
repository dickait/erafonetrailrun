@extends('layouts.participant')
@section('title', 'Dashboard')
@section('content')
@if($participant)
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
            <h2 class="font-display font-semibold text-lg mb-4">Welcome, {{ $participant->full_name }}!</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-dark-700/50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 mb-1">Event</div>
                    <div class="text-white font-medium">{{ $participant->event->name ?? '-' }}</div>
                </div>
                <div class="bg-dark-700/50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 mb-1">Category</div>
                    <div class="font-medium" style="color: {{ $participant->category->color ?? '#fff' }}">{{ $participant->category->name ?? '-' }}</div>
                </div>
                <div class="bg-dark-700/50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 mb-1">BIB Number</div>
                    <div class="font-display font-bold text-xl text-forest-400">{{ $participant->bib_number ?? 'Pending' }}</div>
                </div>
                <div class="bg-dark-700/50 rounded-xl p-4">
                    <div class="text-xs text-gray-500 mb-1">Payment Status</div>
                    @php $sc = ['pending'=>'text-yellow-400','paid'=>'text-green-400','failed'=>'text-red-400']; @endphp
                    <div class="font-semibold {{ $sc[$participant->payment_status] ?? 'text-gray-400' }}">{{ ucfirst($participant->payment_status) }}</div>
                </div>
            </div>
        </div>
        @if($participant->event)
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
            <h3 class="font-display font-semibold text-lg mb-3">Event Details</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-400">Date</span><span class="text-white">{{ $participant->event->event_date->format('d M Y, H:i') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Location</span><span class="text-white">{{ $participant->event->location }}</span></div>
            </div>
        </div>
        @endif
    </div>
    <div class="space-y-6">
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 text-center">
            <div class="w-20 h-20 mx-auto bg-forest-900/30 rounded-full flex items-center justify-center mb-4">
                <span class="font-display font-black text-2xl text-forest-400">{{ $participant->category->distance_km ?? '?' }}K</span>
            </div>
            <h3 class="font-semibold text-white">{{ $participant->category->name ?? 'Category' }}</h3>
            <p class="text-gray-400 text-sm mt-1">{{ $participant->category->distance_km ?? '?' }} kilometers</p>
        </div>
    </div>
</div>
@else
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-12 text-center">
    <p class="text-gray-400">No registration found for your account.</p>
    <a href="{{ route('register.create') }}" class="mt-4 inline-block px-6 py-3 bg-forest-600 text-white font-semibold rounded-xl">Register Now</a>
</div>
@endif
@endsection
