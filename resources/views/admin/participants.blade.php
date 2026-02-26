@extends('layouts.admin')
@section('page_title', __('messages.admin_participants'))
@section('content')
<div class="bg-dark-800 border border-forest-900/30 rounded-xl">
    <div class="p-4 border-b border-dark-700">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.admin_search_placeholder') }}" class="flex-1 min-w-[200px] px-4 py-2.5 bg-dark-700 border border-dark-600 rounded-xl text-sm text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500">
            <select name="category" class="px-4 py-2.5 bg-dark-700 border border-dark-600 rounded-xl text-sm text-white focus:border-forest-500">
                <option value="">{{ __('messages.admin_all_categories') }}</option>
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
            </select>
            <select name="payment_status" class="px-4 py-2.5 bg-dark-700 border border-dark-600 rounded-xl text-sm text-white focus:border-forest-500">
                <option value="">{{ __('messages.admin_all_status') }}</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>{{ __('messages.status_paid') }}</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-forest-700 hover:bg-forest-600 text-white text-sm font-medium rounded-xl transition-colors">{{ __('messages.admin_filter') }}</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-dark-700 text-left">
                <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_bib') }}</th>
                <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_name') }}</th>
                <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_email') }}</th>
                <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_category') }}</th>
                <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_payment') }}</th>
                <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_checkin') }}</th>
                <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_date') }}</th>
            </tr></thead>
            <tbody>
                @forelse($participants as $p)
                <tr class="border-b border-dark-700/50 hover:bg-dark-700/30">
                    <td class="px-4 py-3 font-mono text-forest-400">{{ $p->bib_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-white font-medium">{{ $p->full_name }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $p->email }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-dark-600 text-gray-300">{{ $p->category->name ?? '-' }}</span></td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $p->payment_status == 'paid' ? 'bg-forest-900/40 text-forest-400' : 'bg-amber-900/40 text-amber-400' }}">{{ ucfirst($p->payment_status) }}</span></td>
                    <td class="px-4 py-3">{!! $p->checked_in ? '<span class="text-forest-400">✓</span>' : '<span class="text-gray-600">—</span>' !!}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">{{ __('messages.admin_no_participants') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($participants->hasPages())
    <div class="p-4 border-t border-dark-700">{{ $participants->links() }}</div>
    @endif
</div>
@endsection
