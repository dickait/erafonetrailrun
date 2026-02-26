@extends('layouts.admin')
@section('page_title', 'Payments')
@section('content')
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark-700/50 border-b border-dark-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Participant</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Method</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                @forelse($payments as $payment)
                <tr class="hover:bg-dark-700/30 transition-colors">
                    <td class="px-4 py-3 font-mono text-forest-400 text-xs">{{ $payment->invoice_id ?? '-' }}</td>
                    <td class="px-4 py-3 text-white font-medium">{{ $payment->participant->full_name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $payment->participant->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-white font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @php $sc = ['pending'=>'text-yellow-400 bg-yellow-900/30','paid'=>'text-green-400 bg-green-900/30','failed'=>'text-red-400 bg-red-900/30','expired'=>'text-gray-400 bg-dark-700']; @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-lg {{ $sc[$payment->status] ?? 'text-gray-400 bg-dark-700' }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $payment->payment_method ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $payment->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-dark-700">{{ $payments->links() }}</div>
</div>
@endsection
