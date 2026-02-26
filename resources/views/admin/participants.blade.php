@extends('layouts.admin')
@section('page_title', 'Participants')
@section('content')
<div class="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
    <form method="GET" class="flex flex-wrap gap-3 flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, BIB..." class="px-4 py-2.5 bg-dark-800 border border-dark-600 rounded-xl text-sm text-white placeholder-gray-500 focus:border-forest-500 w-full sm:w-64">
        <select name="category" class="px-4 py-2.5 bg-dark-800 border border-dark-600 rounded-xl text-sm text-white focus:border-forest-500">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="payment_status" class="px-4 py-2.5 bg-dark-800 border border-dark-600 rounded-xl text-sm text-white focus:border-forest-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>Pending</option>
            <option value="paid" {{ request('payment_status')=='paid'?'selected':'' }}>Paid</option>
            <option value="failed" {{ request('payment_status')=='failed'?'selected':'' }}>Failed</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-forest-700 hover:bg-forest-600 text-white text-sm font-medium rounded-xl transition-colors">Filter</button>
    </form>
    <a href="{{ route('admin.export-csv') }}" class="px-4 py-2.5 bg-dark-700 hover:bg-dark-600 border border-dark-600 rounded-xl text-sm font-medium text-white transition-colors">Export CSV</a>
</div>

<div class="bg-dark-800 rounded-2xl border border-forest-900/30 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark-700/50 border-b border-dark-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">BIB</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Payment</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Check-in</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                @forelse($participants as $p)
                <tr class="hover:bg-dark-700/30 transition-colors">
                    <td class="px-4 py-3 font-mono font-bold text-forest-400">{{ $p->bib_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-white font-medium">{{ $p->full_name }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $p->email }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-lg" style="background-color: {{ $p->category->color ?? '#333' }}20; color: {{ $p->category->color ?? '#999' }}">{{ $p->category->name ?? '-' }}</span></td>
                    <td class="px-4 py-3">
                        @php $sc = ['pending'=>'text-yellow-400 bg-yellow-900/30','paid'=>'text-green-400 bg-green-900/30','failed'=>'text-red-400 bg-red-900/30']; @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-lg {{ $sc[$p->payment_status] ?? 'text-gray-400 bg-dark-700' }}">{{ ucfirst($p->payment_status) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($p->checked_in) <span class="text-green-400 text-xs">✓ {{ $p->checked_in_at?->format('H:i') }}</span> @else <span class="text-gray-600 text-xs">—</span> @endif
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No participants found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-dark-700">{{ $participants->links() }}</div>
</div>
@endsection
