@extends('layouts.admin')
@section('page_title', __('messages.admin_payments'))
@section('content')
<div class="bg-white border border-surface-300 rounded-xl overflow-x-auto">
    <table class="w-full text-sm">
        <thead><tr class="border-b border-surface-300 text-left">
            <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_invoice') }}</th>
            <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_participant') }}</th>
            <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_amount') }}</th>
            <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_status') }}</th>
            <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_method') }}</th>
            <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_date') }}</th>
        </tr></thead>
        <tbody>
            @forelse($payments as $pay)
            <tr class="border-b border-surface-300/50 hover:bg-surface-50/30">
                <td class="px-4 py-3 font-mono text-xs text-surface-700">{{ $pay->mayar_invoice_id ?? '-' }}</td>
                <td class="px-4 py-3 text-surface-900">{{ $pay->participant->full_name ?? '-' }}</td>
                <td class="px-4 py-3 text-surface-900 font-medium">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $pay->status == 'paid' ? 'bg-brand-50 text-brand-500' : 'bg-accent-50 text-accent-600' }}">{{ ucfirst($pay->status) }}</span></td>
                <td class="px-4 py-3 text-surface-700">{{ $pay->payment_method ?? '-' }}</td>
                <td class="px-4 py-3 text-surface-700">{{ $pay->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-surface-700">{{ __('messages.admin_no_payments') }}</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($payments->hasPages())
    <div class="p-4 border-t border-surface-300">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
