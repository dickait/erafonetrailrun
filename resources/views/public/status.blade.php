@extends('layouts.public')
@section('title', 'Check Registration Status')
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="inline-block px-3 py-1 text-xs font-semibold text-forest-400 uppercase tracking-wider bg-forest-900/40 rounded-full mb-4">Status Check</span>
            <h1 class="font-display font-bold text-3xl sm:text-4xl text-white mb-2">Check Registration Status</h1>
            <p class="text-gray-400">Enter your email to check your registration and payment status</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-forest-900/30 border border-forest-700/50 rounded-xl text-forest-300 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('registration.status') }}" method="GET" class="mb-8">
            <div class="flex gap-3">
                <input type="email" name="email" value="{{ request('email') }}" required placeholder="Enter your email address" class="flex-1 px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-forest-600 to-forest-500 text-white font-semibold rounded-xl hover:from-forest-500 hover:to-forest-400 transition-all">
                    Check
                </button>
            </div>
        </form>

        @if(request('email') && $participant)
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 overflow-hidden">
            <div class="p-6 border-b border-dark-700">
                <div class="flex items-center justify-between">
                    <h3 class="font-display font-semibold text-lg text-white">Registration Found</h3>
                    @php
                        $statusColors = ['pending' => 'text-yellow-400 bg-yellow-900/30 border-yellow-700/50', 'paid' => 'text-green-400 bg-green-900/30 border-green-700/50', 'failed' => 'text-red-400 bg-red-900/30 border-red-700/50'];
                    @endphp
                    <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusColors[$participant->payment_status] ?? 'text-gray-400 bg-gray-900/30 border-gray-700/50' }}">
                        {{ ucfirst($participant->payment_status) }}
                    </span>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><span class="text-xs text-gray-500 block">Name</span><span class="text-white font-medium">{{ $participant->full_name }}</span></div>
                    <div><span class="text-xs text-gray-500 block">Email</span><span class="text-white font-medium">{{ $participant->email }}</span></div>
                    <div><span class="text-xs text-gray-500 block">Category</span><span class="text-white font-medium">{{ $participant->category->name ?? '-' }}</span></div>
                    <div><span class="text-xs text-gray-500 block">Event</span><span class="text-white font-medium">{{ $participant->event->name ?? '-' }}</span></div>
                    <div><span class="text-xs text-gray-500 block">Registered</span><span class="text-white font-medium">{{ $participant->created_at->format('d M Y H:i') }}</span></div>
                    <div><span class="text-xs text-gray-500 block">BIB Number</span><span class="text-white font-medium">{{ $participant->bib_number ?? 'Not assigned yet' }}</span></div>
                </div>
                @if($participant->payment_status === 'pending' && $participant->latestPayment?->payment_link)
                <a href="{{ $participant->latestPayment->payment_link }}" class="block w-full py-3 bg-gradient-to-r from-forest-600 to-forest-500 text-white font-semibold rounded-xl text-center hover:from-forest-500 hover:to-forest-400 transition-all mt-4">
                    Complete Payment
                </a>
                @endif
            </div>
        </div>
        @elseif(request('email'))
        <div class="bg-dark-800 rounded-2xl border border-dark-600 p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <h3 class="text-lg font-semibold text-gray-300 mb-2">No Registration Found</h3>
            <p class="text-gray-500 text-sm">We couldn't find any registration with this email. Please check and try again.</p>
        </div>
        @endif
    </div>
</section>
@endsection
