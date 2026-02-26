@extends('layouts.admin')
@section('page_title', __('messages.admin_dashboard'))
@section('content')
@if(!$event)
<div class="bg-dark-800 rounded-2xl border border-amber-800/30 p-8 text-center">
    <p class="text-amber-400">{{ __('messages.admin_no_event') }}</p>
</div>
@else
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
    $statCards = [
        ['label' => __('messages.admin_total_reg'), 'value' => $stats['total_participants'], 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'forest'],
        ['label' => __('messages.admin_paid'), 'value' => $stats['paid_participants'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'forest'],
        ['label' => __('messages.admin_pending'), 'value' => $stats['pending_participants'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
        ['label' => __('messages.admin_revenue'), 'value' => 'Rp ' . number_format($stats['total_revenue'], 0, ',', '.'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'forest'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="bg-dark-800 border border-forest-900/30 rounded-xl p-5">
        <div class="flex justify-between items-start mb-3">
            <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $card['label'] }}</p>
            <div class="w-8 h-8 bg-{{ $card['color'] }}-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-{{ $card['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
        </div>
        <p class="font-display text-2xl font-bold text-white">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<!-- Category Breakdown -->
<div class="bg-dark-800 border border-forest-900/30 rounded-xl p-6 mb-8">
    <h3 class="font-display font-semibold text-lg text-white mb-4">{{ __('messages.admin_category_breakdown') }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($stats['categories'] as $cat)
        @php
        $dotColor = match(true) {
            str_contains($cat->slug, '10k') => 'bg-amber-500',
            str_contains($cat->slug, '21k') => 'bg-red-500',
            default => 'bg-forest-500',
        };
        @endphp
        <div class="bg-dark-700/50 rounded-xl p-4 border border-dark-600">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }}"></span>
                <span class="font-semibold text-white">{{ $cat->name }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div><span class="text-gray-400">{{ __('messages.admin_registered') }}:</span> <span class="text-white">{{ $cat->participants_count }}</span></div>
                <div><span class="text-gray-400">{{ __('messages.admin_paid') }}:</span> <span class="text-forest-400">{{ $cat->paid_count }}</span></div>
                <div><span class="text-gray-400">{{ __('messages.admin_quota') }}:</span> <span class="text-white">{{ $cat->quota }}</span></div>
                <div><span class="text-gray-400">{{ __('messages.admin_available') }}:</span> <span class="text-white">{{ $cat->quota - $cat->participants_count }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-dark-800 border border-forest-900/30 rounded-xl p-6">
    <h3 class="font-display font-semibold text-lg text-white mb-4">{{ __('messages.admin_quick_actions') }}</h3>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-dark-700 hover:bg-dark-600 border border-forest-800/30 rounded-xl text-sm font-medium text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('messages.admin_export_csv') }}
        </a>
        <form method="POST" action="{{ route('admin.generate-bibs') }}" class="inline" onsubmit="return confirm('{{ __('messages.admin_generate_bibs_confirm') }}')">@csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-earth-700 to-earth-600 hover:from-earth-600 hover:to-earth-500 rounded-xl text-sm font-medium text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                {{ __('messages.admin_generate_bibs') }}
            </button>
        </form>
        <a href="{{ route('admin.checkin') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-earth-700 to-earth-600 hover:from-earth-600 hover:to-earth-500 rounded-xl text-sm font-medium text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            {{ __('messages.admin_qr_checkin') }}
        </a>
    </div>
</div>
@endif
@endsection
