@extends('layouts.admin')
@section('page_title', 'Data Peserta')
@section('content')

@php
    $labelMap = [
        'id' => 'ID',
        'bib_number' => 'BIB',
        'full_name' => 'Nama',
        'email' => 'Email',
        'phone' => 'No. Telp',
        'gender' => 'L/P',
        'age' => 'Umur',
        'category_id' => 'Kategori',
        'payment_status' => 'Status',
        'checked_in' => 'Check-in',
        'created_at' => 'Tgl Daftar',
        'shirt_size' => 'Ukuran Kaos',
        'blood_type' => 'Gol. Darah',
    ];

    $displayCols = $requestedCols ?? ['full_name', 'email', 'phone', 'age', 'shirt_size', 'payment_status', 'created_at'];
@endphp

<style>
    @media (max-width: 767px) { .desktop-view { display: none !important; } }
    @media (min-width: 768px) { .mobile-view { display: none !important; } }
</style>

<div class="bg-white border border-surface-300 rounded-xl mb-6">
    <div class="p-4 md:p-6">
        <form id="filterForm" method="GET" class="space-y-4">
            {{-- Primary Row --}}
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau BIB..." class="w-full px-4 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 sm:w-48 lg:w-56">
                        <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Dari Tanggal</label>
                        <input type="datetime-local" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500 cursor-pointer">
                    </div>
                    <div class="flex-1 sm:w-48 lg:w-56">
                        <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Sampai Tanggal</label>
                        <input type="datetime-local" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500 cursor-pointer">
                    </div>
                </div>
            </div>

            {{-- Secondary Row --}}
            <div class="flex flex-col md:flex-row items-end gap-3 pt-2 border-t border-surface-100">
                <div class="grid grid-cols-2 lg:flex gap-3 flex-1 w-full">
                    <div class="flex-1">
                        <label class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Kategori</label>
                        <select name="category" class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500 cursor-pointer">
                            <option value="">Semua</option>
                            @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Status</label>
                        <select name="payment_status" class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500 cursor-pointer">
                            <option value="">Semua</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Limit</label>
                        <select name="per_page" class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500 cursor-pointer">
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="250" {{ request('per_page') == 250 ? 'selected' : '' }}>250</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="relative flex-1 md:flex-none self-end">
                        <label class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Tampilan</label>
                        <button type="button" onclick="document.getElementById('colDropdown').classList.toggle('hidden')" class="w-full px-4 py-2 bg-white border border-surface-300 hover:bg-surface-50 text-surface-900 text-sm font-medium rounded-xl transition-colors flex items-center justify-center gap-2 cursor-pointer h-[38px]">
                            <svg class="w-4 h-4 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            Kolom
                        </button>
                        <div id="colDropdown" class="hidden absolute right-0 bottom-full md:bottom-auto md:top-full mb-2 md:mb-0 md:mt-2 w-max min-w-[280px] max-w-[90vw] bg-white border border-surface-300 rounded-xl shadow-2xl z-50 p-3">
                            <div class="mb-2 px-2 border-b border-surface-100 pb-2 flex justify-between">
                                <h4 class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Tampilkan Kolom</h4>
                                <button type="button" onclick="document.getElementById('colDropdown').classList.add('hidden')" class="text-surface-400 hover:text-surface-600">✕</button>
                            </div>
                            <div class="space-y-0.5 max-h-[350px] overflow-y-auto px-1">
                                @foreach($allColumns as $col)
                                    @if(!in_array($col, ['id', 'event_id', 'user_id']))
                                    @php $label = $labelMap[$col] ?? str_replace('_', ' ', $col); @endphp
                                    <label class="flex items-center gap-4 px-2 py-1 hover:bg-surface-50 rounded-lg cursor-pointer transition-colors group">
                                        <input type="checkbox" name="cols[]" value="{{ $col }}" {{ in_array($col, $displayCols) ? 'checked' : '' }} class="col-checkbox w-3.5 h-3.5 text-brand-500 border-surface-300 rounded focus:ring-brand-500 cursor-pointer">
                                        <span class="text-[11px] text-surface-600 group-hover:text-surface-900 capitalize whitespace-nowrap">{{ $label }}</span>
                                    </label>
                                    @endif
                                @endforeach
                            </div>
                            <div class="flex justify-between items-center mt-3 pt-2 border-t border-surface-100 px-2 gap-4">
                                <button type="button" onclick="resetColumns()" class="text-[10px] font-bold text-surface-400 hover:text-surface-600 uppercase cursor-pointer">Reset</button>
                                <button type="submit" class="text-[10px] font-bold text-brand-500 hover:text-brand-600 uppercase cursor-pointer">Terapkan</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="w-full md:px-8 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold rounded-xl transition-colors cursor-pointer h-[38px]">
                        FILTER
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bg-white border border-surface-300 rounded-xl overflow-hidden">
    <!-- Desktop View -->
    <div class="desktop-view overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-300 text-left bg-surface-50/50">
                    @foreach($displayCols as $col)
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap {{ $col == 'checked_in' ? 'text-center' : '' }}">
                        {{ $labelMap[$col] ?? str_replace('_', ' ', $col) }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $p)
                <tr class="border-b border-surface-300/50 hover:bg-surface-50/30">
                    @foreach($displayCols as $col)
                    <td class="px-4 py-3">
                        @if($col == 'full_name')
                            <span class="text-surface-900 font-medium whitespace-nowrap">{{ $p->full_name }}</span>
                        @elseif($col == 'bib_number')
                            <span class="font-mono text-brand-500 font-medium">{{ $p->bib_number ?? '-' }}</span>
                        @elseif($col == 'category_id')
                            <span class="px-2 py-1 text-xs rounded-full bg-surface-100 text-gray-400">{{ $p->category->name ?? '-' }}</span>
                        @elseif($col == 'payment_status')
                            <span class="px-2 py-1 text-xs rounded-full {{ $p->payment_status == 'paid' ? 'bg-brand-50 text-brand-500' : 'bg-accent-50 text-accent-600' }}">{{ $p->payment_status == 'paid' ? 'Lunas' : ($p->payment_status == 'pending' ? 'Pending' : 'Gagal') }}</span>
                        @elseif($col == 'checked_in')
                            <div class="text-center">{!! $p->checked_in ? '<span class="text-brand-500 font-medium">✓</span>' : '<span class="text-gray-600">—</span>' !!}</div>
                        @elseif($col == 'created_at')
                            <span class="text-surface-700 whitespace-nowrap font-mono text-[11px]">{{ $p->created_at->format('d M Y H:i:s') }}</span>
                        @else
                            <span class="text-surface-700">{{ $p->{$col} ?? '-' }}</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @empty
                <tr><td colspan="{{ count($displayCols) }}" class="px-4 py-8 text-center text-surface-700">Tidak ada data peserta ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile View (Dynamic Cards) -->
    <div class="mobile-view p-4 space-y-4">
        @forelse($participants as $p)
        <div class="bg-surface-50 rounded-xl p-4 border border-surface-300">
            {{-- Priority Header: Name & Status --}}
            <div class="flex justify-between items-start mb-4">
                <div class="max-w-[70%]">
                    <p class="font-display font-semibold text-surface-900 truncate">{{ $p->full_name }}</p>
                    @if(in_array('email', $displayCols))
                    <p class="text-[11px] text-surface-700 truncate">{{ $p->email }}</p>
                    @endif
                </div>
                @if(in_array('payment_status', $displayCols))
                <span class="px-2 py-1 text-[10px] rounded-full {{ $p->payment_status == 'paid' ? 'bg-brand-50 text-brand-500' : 'bg-accent-50 text-accent-600' }}">
                    {{ $p->payment_status == 'paid' ? 'Lunas' : ($p->payment_status == 'pending' ? 'Pending' : 'Gagal') }}
                </span>
                @endif
            </div>

            {{-- Dynamic Grid for other selected columns --}}
            <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-xs">
                @foreach($displayCols as $col)
                    {{-- Skip header columns and email (already on header) --}}
                    @if(!in_array($col, ['full_name', 'payment_status', 'email']))
                    <div class="overflow-hidden">
                        <p class="text-[9px] font-bold text-surface-400 uppercase tracking-tight mb-0.5">{{ $labelMap[$col] ?? $col }}</p>
                        <div class="text-surface-900 font-medium truncate">
                            @if($col == 'bib_number')
                                <span class="font-mono text-brand-500">{{ $p->bib_number ?? '-' }}</span>
                            @elseif($col == 'category_id')
                                <span>{{ $p->category->name ?? '-' }}</span>
                            @elseif($col == 'checked_in')
                                {!! $p->checked_in ? '<span class="text-brand-500">Sudah</span>' : '<span class="text-gray-400">Belum</span>' !!}
                            @elseif($col == 'created_at')
                                <span class="text-[10px]">{{ $p->created_at->format('d/m/y H:i') }}</span>
                            @else
                                {{ $p->{$col} ?? '-' }}
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @empty
        <div class="py-8 text-center text-surface-700 font-medium">Tidak ada data peserta ditemukan.</div>
        @endforelse
    </div>

    @if($participants->hasPages() && $participants->perPage() < $participants->total())
    <div class="p-4 border-t border-surface-300">{{ $participants->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const COL_STORAGE_KEY = 'admin_participants_cols_v2';

function resetColumns() {
    const checkboxes = document.querySelectorAll('.col-checkbox');
    checkboxes.forEach(cb => { cb.checked = false; });
}

function saveColumnsToLocal() {
    const checkboxes = document.querySelectorAll('.col-checkbox:checked');
    const cols = Array.from(checkboxes).map(cb => cb.value);
    localStorage.setItem(COL_STORAGE_KEY, JSON.stringify(cols));
}

document.getElementById('filterForm').addEventListener('submit', function() {
    saveColumnsToLocal();
});

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('colDropdown');
    const button = event.target.closest('button');
    if (!dropdown || !button) return;
    if (button.onclick && button.onclick.toString().includes('colDropdown')) return;
    if (!dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('cols[]')) {
        const saved = localStorage.getItem(COL_STORAGE_KEY);
        if (saved) {
            const cols = JSON.parse(saved);
            if (cols.length > 0) {
                cols.forEach(c => urlParams.append('cols[]', c));
                window.location.search = urlParams.toString();
            }
        }
    }
});
</script>
@endpush
