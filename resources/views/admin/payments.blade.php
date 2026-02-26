@extends('layouts.admin')
@section('page_title', __('messages.admin_payments'))
@section('content')
<div class="bg-dark-800 border border-forest-900/30 rounded-xl overflow-x-auto">
    <table class="w-full text-sm">
        <thead><tr class="border-b border-dark-700 text-left">
            <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_invoice') }}</th>
            <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_participant') }}</th>
            <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_amount') }}</th>
            <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_status') }}</th>
            <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_method') }}</th>
            <th class="px-4 py-3 text-gray-400 font-medium">{{ __('messages.admin_date') }}</th>
        </tr></thead>
        <tbody>
            @forelse($payments as $pay)
            <tr class="border-b border-dark-700/50 hover:bg-dark-700/30">
                <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $pay->mayar_invoice_id ?? '-' }}</td>
                <td class="px-4 py-3 text-white">{{ $pay->participant->full_name ?? '-' }}</td>
                <td class="px-4 py-3 text-white font-medium">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $pay->status == 'paid' ? 'bg-forest-900/40 text-forest-400' : 'bg-amber-900/40 text-amber-400' }}">{{ ucfirst($pay->status) }}</span></td>
                <td class="px-4 py-3 text-gray-400">{{ $pay->payment_method ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $pay->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">{{ __('messages.admin_no_payments') }}</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($payments->hasPages())
    <div class="p-4 border-t border-dark-700">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
