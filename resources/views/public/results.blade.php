@extends('layouts.public')
@section('title', 'Results - Erafone Trail Run 2026')
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 text-xs font-semibold text-forest-400 uppercase tracking-wider bg-forest-900/40 rounded-full mb-4">Results</span>
            <h1 class="font-display font-bold text-3xl sm:text-4xl text-white mb-2">Race Results</h1>
            <p class="text-gray-400">Official race results will be published after the event</p>
        </div>
        <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-forest-700/40 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="font-display text-2xl font-bold text-white mb-3">Coming Soon</h3>
            <p class="text-gray-400 max-w-md mx-auto">Race results will be published here after {{ $event ? $event->event_date->format('d M Y') : 'the event' }}. Check back later for full standings and finish times.</p>
        </div>
    </div>
</section>
@endsection
