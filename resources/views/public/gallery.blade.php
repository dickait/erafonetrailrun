@extends('layouts.public')
@section('title', __('messages.gallery_title') . ' - Erafone Trail Run 2026')
@section('content')
    <section class="pt-28 pb-20 bg-surface-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span
                    class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.gallery_badge') }}</span>
                <h1 class="font-display font-bold text-3xl md:text-4xl text-surface-900 mb-2">
                    {{ __('messages.gallery_title') }}</h1>
                <p class="text-surface-700">{{ __('messages.gallery_subtitle') }}</p>
            </div>
            <div class="flex flex-col items-center justify-center py-16">
                <div
                    class="bg-white border border-surface-200 rounded-2xl shadow-sm px-8 py-12 max-w-lg w-full text-center">
                    <div class="mx-auto w-20 h-20 bg-brand-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="font-display font-bold text-2xl text-surface-900 mb-3">
                        {{ __('messages.gallery_coming_soon') }}</h2>
                    <p class="text-surface-600 leading-relaxed">{{ __('messages.gallery_coming_soon_desc') }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection