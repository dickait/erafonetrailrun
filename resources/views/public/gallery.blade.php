@extends('layouts.public')
@section('title', __('messages.gallery_title') . ' - Erafone Trail Run 2026')
@section('content')
<section class="pt-28 pb-20 bg-surface-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.gallery_badge') }}</span>
            <h1 class="font-display font-bold text-3xl md:text-4xl text-surface-900 mb-2">{{ __('messages.gallery_title') }}</h1>
            <p class="text-surface-700">{{ __('messages.gallery_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @for($i = 1; $i <= 8; $i++)
            <div class="bg-white border border-surface-300 rounded-xl aspect-square flex flex-col items-center justify-center hover:border-brand-300 transition-colors group shadow-sm">
                <svg class="w-10 h-10 text-surface-300 group-hover:text-brand-400 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-xs text-surface-700">{{ __('messages.gallery_moment') }} {{ $i }}</span>
            </div>
            @endfor
        </div>
        <p class="text-center text-surface-700 text-sm mt-8">{{ __('messages.gallery_more') }}</p>
    </div>
</section>
@endsection
