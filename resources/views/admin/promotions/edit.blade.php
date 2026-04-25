@extends('layouts.admin')

@section('title', 'Edit Promotion - Era Trail Run')
@section('page_title', 'Edit Promotion: ' . ($promotion->name ?: $promotion->code))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.promotions.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to List
    </a>
</div>

<div class="max-w-3xl">
    <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-8 transition-all">
        <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800">Promotion Name <span class="text-surface-400 font-normal italic">(for internal tracking)</span></label>
                    <input type="text" name="name" value="{{ old('name', $promotion->name) }}"
                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                        placeholder="e.g. Early Bird Promo, Community 10% Discount">
                    @error('name')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800 uppercase tracking-tighter">Coupon Code <span class="text-surface-400 font-normal italic">(optional)</span></label>
                    <input type="text" name="code" value="{{ old('code', $promotion->code) }}"
                        class="w-full px-4 py-3 bg-white border border-surface-300 rounded-xl text-surface-900 font-mono uppercase focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                        placeholder="e.g. EARLY2026, RUNNER10">
                    <p class="text-[10px] text-surface-500 italic">Leave empty for automatic (Early Bird) promotions.</p>
                    @error('code')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800">Promotion Type</label>
                    <select name="type" class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="discount_code" {{ old('type', $promotion->type) === 'discount_code' ? 'selected' : '' }}>Coupon Code (Manual Input)</option>
                        <option value="earlybird" {{ old('type', $promotion->type) === 'earlybird' ? 'selected' : '' }}>Early Bird (Automatic, No Code)</option>
                    </select>
                    @error('type')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800">Discount Type</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 border border-surface-300 rounded-xl bg-surface-50 hover:bg-surface-100 cursor-pointer transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                            <input type="radio" name="discount_type" value="fixed" {{ old('discount_type', $promotion->discount_type) === 'fixed' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                            <span class="text-sm font-medium text-surface-800">Fixed IDR</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-surface-300 rounded-xl bg-surface-50 hover:bg-surface-100 cursor-pointer transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                            <input type="radio" name="discount_type" value="percent" {{ old('discount_type', $promotion->discount_type) === 'percent' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                            <span class="text-sm font-medium text-surface-800">Percentage %</span>
                        </label>
                    </div>
                    @error('discount_type')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800">Discount Value (Rp / %)</label>
                    <input type="number" name="discount_value" value="{{ old('discount_value', (float)$promotion->discount_value) }}" required
                        class="w-full px-4 py-3 bg-white border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                        placeholder="e.g. 50000 or 10">
                    @error('discount_value')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800">Quota <span class="text-surface-400 font-normal italic">(optional)</span></label>
                    <input type="number" name="quota" value="{{ old('quota', $promotion->quota) }}"
                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                        placeholder="Limit total usage">
                    <p class="text-[10px] text-surface-500 italic">Leave empty for unlimited usage.</p>
                    @error('quota')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $promotion->start_date ? $promotion->start_date->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('start_date')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-surface-800">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $promotion->end_date ? $promotion->end_date->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('end_date')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-sm font-medium text-surface-800">Applicable Categories <span class="text-surface-400 font-normal italic">(optional)</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php $selectedCategories = old('category_ids', $promotion->categories->pluck('id')->toArray()); @endphp
                    @foreach($categories as $category)
                        <label class="flex items-center gap-3 p-3 border border-surface-300 rounded-xl bg-surface-50 hover:bg-surface-100 cursor-pointer transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                            <span class="text-sm font-medium text-surface-800">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-[10px] text-surface-500 italic">If no categories are selected, the promotion will apply to ALL categories.</p>
                @error('category_ids')<span class="text-xs text-brand-600 mt-1 font-medium">{{ $message }}</span>@enderror
            </div>

            <div class="pt-6 mt-6 border-t border-surface-100 flex justify-end gap-4">
                <button type="button" onclick="history.back()" class="px-8 py-3 bg-surface-100 hover:bg-surface-200 text-surface-700 font-bold rounded-xl transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-3 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-0.5 active:translate-y-0">
                    Update Promotion
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
