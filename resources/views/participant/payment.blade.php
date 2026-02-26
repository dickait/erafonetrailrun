@extends('layouts.participant')
@section('title', __('messages.part_payment_title') . ' - Erafone Trail Run')
@section('content')
<div class="mb-8">
    <h1 class="font-display font-bold text-2xl text-white mb-2">{{ __('messages.part_payment_title') }}</h1>
</div>

@if(!$participant)
<div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-8 text-center text-gray-400">
    {{ __('messages.part_no_registration') }}
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 overflow-hidden mb-6">
            <div class="p-6 border-b border-forest-900/30 flex justify-between items-center">
                <h3 class="font-display font-semibold text-lg text-white">{{ __('messages.part_payment_status') }}</h3>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $participant->payment_status == 'paid' ? 'bg-forest-900/40 text-forest-400' : ($participant->payment_status == 'pending' ? 'bg-amber-900/40 text-amber-400' : 'bg-red-900/40 text-red-400') }}">
                    {{ ucfirst($participant->payment_status) }}
                </span>
            </div>
            <div class="p-6 md:p-8 text-center">
                @if($participant->payment_status == 'pending')
                    <svg class="w-16 h-16 mx-auto text-amber-500/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="text-xl font-display font-bold text-white mb-2">{{ __('messages.part_payment_pending') }}</h4>
                    <p class="text-gray-400 mb-6">{{ __('messages.status_complete_payment') }} Rp {{ number_format($participant->category->getCurrentPrice(), 0, ',', '.') }}</p>
                    @if($participant->payments->first())
                    <a href="{{ $participant->payments->first()->payment_link ?? '#' }}" target="_blank" class="inline-block px-8 py-3 bg-gradient-to-r from-amber-600 to-amber-500 text-white font-semibold rounded-xl hover:from-amber-500 hover:to-amber-400 transition-all shadow-lg shadow-amber-500/20">Bayar Sekarang</a>
                    @endif
                @elseif($participant->payment_status == 'paid')
                    <svg class="w-16 h-16 mx-auto text-forest-500/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="text-xl font-display font-bold text-white mb-2">{{ __('messages.part_payment_confirmed') }}</h4>
                    <p class="text-gray-400">Pembayaran Rp {{ number_format($participant->category->getCurrentPrice(), 0, ',', '.') }} berhasil.</p>
                @else
                    <svg class="w-16 h-16 mx-auto text-red-500/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="text-xl font-display font-bold text-white mb-2">{{ __('messages.part_payment_failed') }}</h4>
                @endif
            </div>
        </div>
    </div>
    
    <div>
        <h3 class="font-display font-semibold text-lg text-white mb-4">{{ __('messages.part_payment_history') }}</h3>
        <div class="space-y-4">
            @forelse($participant->payments as $pay)
            <div class="bg-dark-800 rounded-xl border border-dark-600 p-4 flex justify-between items-center group hover:border-forest-900/50 transition-colors">
                <div>
                    <p class="font-medium text-white mb-1 tracking-wide">Rp {{ number_format($pay->amount, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">{{ $pay->created_at->format('d M Y H:i') }} • {{ $pay->payment_method ?? '-' }}</p>
                </div>
                <div>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $pay->status == 'paid' ? 'bg-forest-900/40 text-forest-400' : 'bg-amber-900/40 text-amber-400' }}">
                        {{ ucfirst($pay->status) }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm italic py-4 bg-dark-800 rounded-xl px-4">{{ __('messages.admin_no_payments') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endif
@endsection
