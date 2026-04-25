@extends('layouts.admin')

@section('title', 'Manage Promotions - Era Trail Run')
@section('page_title', 'Promotions & Coupon Codes')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-surface-600 text-sm">Manage early bird discounts and promotional coupon codes.</p>
    <a href="{{ route('admin.promotions.create') }}" class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg shadow-sm transition-all text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Promotion
    </a>
</div>

<div class="bg-white rounded-2xl border border-surface-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-surface-50 text-surface-600 uppercase text-xs font-bold border-b border-surface-300">
                <tr>
                    <th class="px-6 py-4">Name & Code</th>
                    <th class="px-6 py-4">Categories</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Discount</th>
                    <th class="px-6 py-4">Period</th>
                    <th class="px-6 py-4">Usage</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-200">
                @forelse($promotions as $promo)
                <tr class="hover:bg-surface-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-surface-900">{{ $promo->name ?: 'Unnamed Promotion' }}</div>
                        @if($promo->code)
                        <div class="text-xs font-mono text-brand-600 bg-brand-50 px-1.5 py-0.5 rounded inline-block mt-1">{{ $promo->code }}</div>
                        @else
                        <span class="text-xs text-surface-400 italic">No code (Automatic)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($promo->categories->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($promo->categories as $category)
                                    <span class="px-2 py-0.5 bg-surface-100 text-surface-600 rounded text-[10px] whitespace-nowrap">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-surface-400 italic">All Categories</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $promo->type === 'earlybird' ? 'bg-emerald-100 text-emerald-700' : 'bg-brand-100 text-brand-700' }}">
                            {{ str_replace('_', ' ', $promo->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-surface-900">
                        @if($promo->discount_type === 'percent')
                            {{ number_format((float)$promo->discount_value, 0) }}%
                        @else
                            Rp {{ number_format((float)$promo->discount_value, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-surface-600">
                        <div>S: {{ $promo->start_date ? $promo->start_date->format('d M Y') : 'Anytime' }}</div>
                        <div>E: {{ $promo->end_date ? $promo->end_date->format('d M Y') : 'No End' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-surface-900 font-bold">{{ $promo->used_count }}</span>
                            <span class="text-surface-400">/</span>
                            <span class="text-surface-600">{{ $promo->quota ?? '∞' }}</span>
                        </div>
                        <div class="w-full bg-surface-100 h-1 mt-1 rounded-full overflow-hidden">
                            @php
                                $percent = $promo->quota > 0 ? ($promo->used_count / $promo->quota) * 100 : 0;
                                if (!$promo->quota) $percent = 0;
                            @endphp
                            <div class="bg-brand-500 h-full" style="width: {{ min(100, $percent) }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="{{ route('admin.promotions.edit', $promo) }}" class="p-2 text-surface-400 hover:text-brand-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this promotion?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-surface-400 hover:text-brand-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-surface-500 italic">No promotions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($promotions->hasPages())
    <div class="px-6 py-4 border-t border-surface-200 bg-surface-50">
        {{ $promotions->links() }}
    </div>
    @endif
</div>
@endsection
