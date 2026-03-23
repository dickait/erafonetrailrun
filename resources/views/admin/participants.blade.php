@extends('layouts.admin')
@section('page_title', 'Data Peserta')
@section('content')
<div class="bg-white border border-surface-300 rounded-xl mb-6">
    <div class="p-4 md:p-6">
        <form method="GET" class="flex flex-col md:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau BIB..." class="flex-1 px-4 py-2.5 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:flex gap-3">
                <select name="category" class="px-3 md:px-4 py-2.5 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                </select>
                <select name="payment_status" class="px-3 md:px-4 py-2.5 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <select name="per_page" class="px-3 md:px-4 py-2.5 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500">
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 Data</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Data</option>
                    <option value="250" {{ request('per_page') == 250 ? 'selected' : '' }}>250 Data</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-xl transition-colors w-full md:w-auto">Filter</button>
        </form>
    </div>
</div>

<style>
    @media (max-width: 767px) { .desktop-view { display: none !important; } }
    @media (min-width: 768px) { .mobile-view { display: none !important; } }
</style>

<div class="bg-white border border-surface-300 rounded-xl overflow-hidden">
    <!-- Desktop View (Tabel) -->
    <div class="desktop-view overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-300 text-left bg-surface-50/50">
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap">BIB</th>
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap">Nama</th>
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap">Email</th>
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap">Kategori</th>
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap">Status</th>
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap">Check-in</th>
                    <th class="px-4 py-3 text-surface-700 font-medium whitespace-nowrap">Tgl Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $p)
                <tr class="border-b border-surface-300/50 hover:bg-surface-50/30">
                    <td class="px-4 py-3 font-mono text-brand-500">{{ $p->bib_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-surface-900 font-medium">{{ $p->full_name }}</td>
                    <td class="px-4 py-3 text-surface-700">{{ $p->email }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-surface-100 text-gray-400">{{ $p->category->name ?? '-' }}</span></td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $p->payment_status == 'paid' ? 'bg-brand-50 text-brand-500' : 'bg-accent-50 text-accent-600' }}">{{ $p->payment_status == 'paid' ? 'Lunas' : ($p->payment_status == 'pending' ? 'Pending' : 'Gagal') }}</span></td>
                    <td class="px-4 py-3 text-center">{!! $p->checked_in ? '<span class="text-brand-500 font-medium">✓</span>' : '<span class="text-gray-600">—</span>' !!}</td>
                    <td class="px-4 py-3 text-surface-700 whitespace-nowrap">{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-surface-700">Tidak ada data peserta ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile View (Card List) -->
    <div class="mobile-view">
        <div class="p-4 space-y-4">
            @forelse($participants as $p)
            <div class="bg-surface-50 rounded-xl p-4 border border-surface-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="max-w-[70%]">
                        <p class="font-display font-semibold text-surface-900 truncate">{{ $p->full_name }}</p>
                        <p class="text-[11px] text-surface-700 truncate">{{ $p->email }}</p>
                    </div>
                    <span class="px-2 py-1 text-[10px] rounded-full {{ $p->payment_status == 'paid' ? 'bg-brand-50 text-brand-500' : 'bg-accent-50 text-accent-600' }}">
                        {{ $p->payment_status == 'paid' ? 'Lunas' : ($p->payment_status == 'pending' ? 'Pending' : 'Gagal') }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-surface-700 uppercase tracking-tight text-[10px] mb-1">BIB</p>
                        <p class="font-mono text-brand-500 font-medium">{{ $p->bib_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-surface-700 uppercase tracking-tight text-[10px] mb-1">Kategori</p>
                        <p class="text-surface-900 font-medium">{{ $p->category->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-surface-700 uppercase tracking-tight text-[10px] mb-1">Check-in</p>
                        <p class="text-surface-900 font-medium text-xs">{!! $p->checked_in ? '<span class="text-brand-500">Sudah</span>' : '<span class="text-gray-400">Belum</span>' !!}</p>
                    </div>
                    <div>
                        <p class="text-surface-700 uppercase tracking-tight text-[10px] mb-1">Tgl Daftar</p>
                        <p class="text-surface-900 font-medium">{{ $p->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-surface-700">Tidak ada data peserta ditemukan.</div>
            @endforelse
        </div>
    </div>

    @if($participants->hasPages())
    <div class="p-4 border-t border-surface-300">{{ $participants->links() }}</div>
    @endif
</div>
@endsection
