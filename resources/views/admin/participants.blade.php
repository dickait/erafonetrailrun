@extends('layouts.admin')
@section('page_title', __('messages.admin_participants'))
@section('content')
<div class="bg-white border border-surface-200 rounded-xl">
    <div class="p-4 border-b border-surface-200">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.admin_search_placeholder') }}" class="flex-1 min-w-[200px] px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm text-surface-900 placeholder-surface-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
            <select name="category" class="px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm text-surface-900 focus:border-brand-500">
                <option value="">{{ __('messages.admin_all_categories') }}</option>
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
            </select>
            <select name="payment_status" class="px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm text-surface-900 focus:border-brand-500">
                <option value="">{{ __('messages.admin_all_status') }}</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>{{ __('messages.status_paid') }}</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-xl transition-colors">{{ __('messages.admin_filter') }}</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-surface-200 text-left">
                <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_bib') }}</th>
                <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_name') }}</th>
                <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_email') }}</th>
                <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_category') }}</th>
                <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_payment') }}</th>
                <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_checkin') }}</th>
                <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_date') }}</th>
            </tr></thead>
            <tbody>
                @forelse($participants as $p)
                <tr class="border-b border-surface-200/50 hover:bg-surface-50/30">
                    <td class="px-4 py-3 font-mono text-brand-500">{{ $p->bib_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-surface-900 font-medium">{{ $p->full_name }}</td>
                    <td class="px-4 py-3 text-surface-700">{{ $p->email }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-surface-100 text-gray-300">{{ $p->category->name ?? '-' }}</span></td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $p->payment_status == 'paid' ? 'bg-brand-50 text-brand-500' : 'bg-accent-50 text-accent-600' }}">{{ ucfirst($p->payment_status) }}</span></td>
                    <td class="px-4 py-3">{!! $p->checked_in ? '<span class="text-brand-500">✓</span>' : '<span class="text-gray-600">—</span>' !!}</td>
                    <td class="px-4 py-3 text-surface-700">{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-surface-700">{{ __('messages.admin_no_participants') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($participants->hasPages())
    <div class="p-4 border-t border-surface-200">{{ $participants->links() }}</div>
    @endif
</div>
@endsection
