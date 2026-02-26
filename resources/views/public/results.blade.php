@extends('layouts.public')
@section('title', __('messages.results_title') . ' - Erafone Trail Run 2026')
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-block px-4 py-1.5 bg-forest-900/40 text-forest-400 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.results_badge') }}</span>
        <h1 class="font-display font-bold text-3xl md:text-4xl text-white mb-2">{{ __('messages.results_title') }}</h1>
        <p class="text-gray-400 mb-12">{{ __('messages.results_subtitle') }}</p>
        <div class="bg-dark-800 border border-forest-900/30 rounded-2xl p-12">
            <svg class="w-24 h-24 mx-auto text-forest-500/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="font-display font-bold text-2xl text-white mb-3">{{ __('messages.results_coming_soon') }}</h3>
            <p class="text-gray-400">{{ __('messages.results_description', ['date' => '15 June 2026']) }}</p>
        </div>
    </div>
</section>
@endsection
