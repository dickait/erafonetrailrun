@extends('layouts.admin')
@section('page_title', __('messages.admin_dashboard'))
@section('content')
@if(!$event)
<div class="bg-white rounded-2xl border border-accent-200 p-8 text-center">
    <p class="text-accent-600">{{ __('messages.admin_no_event') }}</p>
</div>
@else
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    @php
    $statCards = [
        ['label' => __('messages.admin_total_reg'), 'value' => $stats['total_participants'], 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'brand'],
        ['label' => __('messages.admin_total_headcount'), 'value' => $stats['total_people'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'brand'],
        ['label' => __('messages.admin_paid_headcount'), 'value' => $stats['total_paid_people'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'brand'],
        ['label' => __('messages.admin_pending'), 'value' => $stats['pending_participants'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
        ['label' => __('messages.admin_revenue'), 'value' => 'Rp ' . number_format($stats['total_revenue'], 0, ',', '.'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'brand'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="bg-white border border-surface-300 rounded-xl p-5">
        <div class="flex justify-between items-start mb-3">
            <p class="text-xs text-surface-700 uppercase tracking-wide">{{ $card['label'] }}</p>
            <div class="w-8 h-8 bg-{{ $card['color'] }}-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-{{ $card['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
        </div>
        <p class="font-display text-2xl font-bold text-surface-900">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<!-- Line Chart -->
<div class="bg-white border border-surface-300 rounded-xl p-6 mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h3 class="font-display font-semibold text-lg text-surface-900">{{ __('messages.admin_reg_stats') }}</h3>
        
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-2">
                <input type="datetime-local" 
                       name="start_date" 
                       value="{{ $startDate->format('Y-m-d\TH:i') }}" 
                       class="text-xs border-surface-300 rounded-lg focus:ring-accent-500 focus:border-accent-500 bg-surface-50">
                <span class="text-surface-400">to</span>
                <input type="datetime-local" 
                       name="end_date" 
                       value="{{ $endDate->format('Y-m-d\TH:i') }}" 
                       class="text-xs border-surface-300 rounded-lg focus:ring-accent-500 focus:border-accent-500 bg-surface-50">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-accent-600 hover:bg-accent-500 rounded-lg text-xs font-medium text-white transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
            @if(request('start_date') || request('end_date'))
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-surface-500 hover:text-accent-600 underline">Reset</a>
            @endif
        </form>
    </div>
    <div class="h-[350px]">
        <canvas id="registrationChart"></canvas>
    </div>
</div>

<!-- Category Breakdown -->
<div class="bg-white border border-surface-300 rounded-xl p-6 mb-8">
    <h3 class="font-display font-semibold text-lg text-surface-900 mb-4">{{ __('messages.admin_category_breakdown') }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($stats['categories'] as $cat)
        @php
        $dotColor = match(true) {
            str_contains($cat->slug, '10k') => 'bg-amber-500',
            str_contains($cat->slug, '21k') => 'bg-red-500',
            str_contains($cat->slug, '5k') => 'bg-cyan-500',
            default => 'bg-brand-500',
        };
        @endphp
        <div class="bg-surface-50 rounded-xl p-4 border border-surface-300">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }}"></span>
                <span class="font-semibold text-surface-900">{{ $cat->name }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div><span class="text-surface-700">{{ __('messages.admin_registered') }}:</span> <span class="text-surface-900">{{ $cat->participants_count }}</span></div>
                <div><span class="text-surface-700">{{ __('messages.admin_paid') }}:</span> <span class="text-brand-500">{{ $cat->paid_count }}</span></div>
                <div><span class="text-surface-700">{{ __('messages.admin_quota') }}:</span> <span class="text-surface-900">{{ $cat->quota }}</span></div>
                <div><span class="text-surface-700">{{ __('messages.admin_available') }}:</span> <span class="text-surface-900">{{ max(0, $cat->quota - $cat->paid_count) }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white border border-surface-300 rounded-xl p-6">
    <h3 class="font-display font-semibold text-lg text-surface-900 mb-4">{{ __('messages.admin_quick_actions') }}</h3>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-surface-50 hover:bg-surface-100 border border-surface-300 rounded-xl text-sm font-medium text-surface-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('messages.admin_export_csv') }}
        </a>
        <form method="POST" action="{{ route('admin.generate-bibs') }}" class="inline" onsubmit="return confirm('{{ __('messages.admin_generate_bibs_confirm') }}')">@csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-accent-600 to-accent-500 hover:from-accent-500 hover:to-accent-400 rounded-xl text-sm font-medium text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                {{ __('messages.admin_generate_bibs') }}
            </button>
        </form>
        <a href="{{ route('admin.checkin') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-accent-600 to-accent-500 hover:from-accent-500 hover:to-accent-400 rounded-xl text-sm font-medium text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            {{ __('messages.admin_qr_checkin') }}
        </a>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('registrationChart').getContext('2d');
    const data = @json($chartData);
    
    new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 6,
                        boxHeight: 6,
                        padding: 20,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: {
                        size: 13,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    cornerRadius: 8,
                    displayColors: true
                }
            }
        }
    });
});
</script>
@endpush
