@extends('layouts.participant')
@section('title', 'Payment Status')
@section('content')
@if($participant)
<div class="max-w-2xl space-y-6">
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
        <h2 class="font-display font-semibold text-lg mb-4">Payment Status</h2>
        <div class="text-center py-4">
            @php $sc = ['pending'=>['text-yellow-400','bg-yellow-900/30','border-yellow-700/50','Pending Payment'],'paid'=>['text-green-400','bg-green-900/30','border-green-700/50','Payment Confirmed'],'failed'=>['text-red-400','bg-red-900/30','border-red-700/50','Payment Failed']]; $s=$sc[$participant->payment_status] ?? ['text-gray-400','bg-dark-700','border-dark-600', ucfirst($participant->payment_status)]; @endphp
            <div class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl border {{ $s[2] }} {{ $s[1] }}">
                <span class="w-3 h-3 rounded-full {{ str_replace('text-','bg-',$s[0]) }}"></span>
                <span class="font-semibold {{ $s[0] }}">{{ $s[3] }}</span>
            </div>
        </div>
    </div>

    @if($participant->payments->count())
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 overflow-hidden">
        <div class="p-4 border-b border-dark-700">
            <h3 class="font-semibold text-white">Payment History</h3>
        </div>
        <div class="divide-y divide-dark-700">
            @foreach($participant->payments as $payment)
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-white font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $payment->invoice_id }} • {{ $payment->created_at->format('d M Y H:i') }}</div>
                    </div>
                    @php $pc = ['pending'=>'text-yellow-400 bg-yellow-900/30','paid'=>'text-green-400 bg-green-900/30','failed'=>'text-red-400 bg-red-900/30']; @endphp
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $pc[$payment->status] ?? 'text-gray-400 bg-dark-700' }}">{{ ucfirst($payment->status) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endif
@endsection
