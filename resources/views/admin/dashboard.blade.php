@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('content')
@if($event)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Total Registrations</span>
            <div class="w-10 h-10 bg-forest-900/40 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-forest-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
        </div>
        <div class="font-display font-bold text-3xl text-white">{{ $stats['total_participants'] }}</div>
    </div>
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Paid</span>
            <div class="w-10 h-10 bg-green-900/40 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
        <div class="font-display font-bold text-3xl text-white">{{ $stats['paid_participants'] }}</div>
    </div>
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Pending</span>
            <div class="w-10 h-10 bg-yellow-900/40 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
        <div class="font-display font-bold text-3xl text-white">{{ $stats['pending_participants'] }}</div>
    </div>
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Revenue</span>
            <div class="w-10 h-10 bg-earth-900/40 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-earth-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
        <div class="font-display font-bold text-2xl text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
    </div>
</div>

<!-- Category Breakdown -->
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 mb-8">
    <h3 class="font-display font-semibold text-lg mb-4">Category Breakdown</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach($stats['categories'] as $cat)
        <div class="bg-dark-700/50 rounded-xl p-4 border border-dark-600">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-3 h-3 rounded-full" style="background-color: {{ $cat->color }}"></div>
                <span class="font-semibold text-white">{{ $cat->name }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div><span class="text-gray-500">Registered:</span> <span class="text-white">{{ $cat->participants_count }}</span></div>
                <div><span class="text-gray-500">Paid:</span> <span class="text-green-400">{{ $cat->paid_count }}</span></div>
                <div><span class="text-gray-500">Quota:</span> <span class="text-white">{{ $cat->quota }}</span></div>
                <div><span class="text-gray-500">Available:</span> <span class="text-earth-400">{{ $cat->quota - $cat->participants_count }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
    <h3 class="font-display font-semibold text-lg mb-4">Quick Actions</h3>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.export-csv') }}" class="px-4 py-2.5 bg-dark-700 hover:bg-dark-600 border border-dark-600 rounded-xl text-sm font-medium text-white transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export CSV
        </a>
        <form action="{{ route('admin.generate-bibs') }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('Generate BIB numbers for all paid participants without BIB?')" class="px-4 py-2.5 bg-forest-700 hover:bg-forest-600 border border-forest-600 rounded-xl text-sm font-medium text-white transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Generate BIBs
            </button>
        </form>
        <a href="{{ route('admin.checkin') }}" class="px-4 py-2.5 bg-earth-700 hover:bg-earth-600 border border-earth-600 rounded-xl text-sm font-medium text-white transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            QR Check-in
        </a>
    </div>
</div>
@else
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-12 text-center">
    <p class="text-gray-400">No active event found. Please create an event first.</p>
</div>
@endif
@endsection
