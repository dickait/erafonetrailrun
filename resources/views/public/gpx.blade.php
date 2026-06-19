@extends('layouts.public')

@section('title', __('messages.gpx_title') . ' - Era Trail Run 2026')
@section('meta_description', __('messages.gpx_subtitle'))

@section('content')
    <section class="pt-28 pb-24 bg-surface-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">
                    {{ __('messages.gpx_badge') }}
                </span>
                <h1 class="font-display font-black text-3xl md:text-5xl text-surface-900 mb-6 uppercase tracking-tight">
                    {{ __('messages.gpx_title') }}
                </h1>
                <div class="w-24 h-1.5 bg-brand-500 mx-auto mb-8 rounded-full"></div>
                <p class="text-surface-600 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                    {{ __('messages.gpx_subtitle') }}
                </p>
            </div>

            <!-- GPX Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- 5K Category Card -->
                <div class="bg-white border border-surface-200 border-t-4 border-t-emerald-500 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-black rounded-lg uppercase tracking-wide">
                                5K Category
                            </span>
                            <span class="px-2.5 py-1 bg-surface-100 text-surface-600 text-xs font-bold rounded-lg uppercase">
                                GPX
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="font-display font-bold text-2xl text-surface-900 mb-2 group-hover:text-emerald-600 transition-colors">
                            5K Family Fun Trail
                        </h3>
                    </div>

                    <!-- Action Area -->
                    <div class="border-t border-surface-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs text-surface-400 font-medium">{{ __('messages.docs_file_size') }}</span>
                            <span class="text-xs text-surface-600 font-semibold">450 KB</span>
                        </div>
                        <a href="{{ asset('gpx/5K_Era_Trail_Run_2026.gpx') }}" download
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ __('messages.gpx_download') }}
                        </a>
                    </div>
                </div>

                <!-- 10K Category Card -->
                <div class="bg-white border border-surface-200 border-t-4 border-t-amber-500 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-xs font-black rounded-lg uppercase tracking-wide">
                                10K Category
                            </span>
                            <span class="px-2.5 py-1 bg-surface-100 text-surface-600 text-xs font-bold rounded-lg uppercase">
                                GPX
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="font-display font-bold text-2xl text-surface-900 mb-2 group-hover:text-amber-600 transition-colors">
                            10K Trail Run
                        </h3>
                    </div>

                    <!-- Action Area -->
                    <div class="border-t border-surface-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs text-surface-400 font-medium">{{ __('messages.docs_file_size') }}</span>
                            <span class="text-xs text-surface-600 font-semibold">451 KB</span>
                        </div>
                        <a href="{{ asset('gpx/10K_Era_Trail_Run_2026.gpx') }}" download
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ __('messages.gpx_download') }}
                        </a>
                    </div>
                </div>

                <!-- 15K Category Card -->
                <div class="bg-white border border-surface-200 border-t-4 border-t-red-500 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-xs font-black rounded-lg uppercase tracking-wide">
                                15K Category
                            </span>
                            <span class="px-2.5 py-1 bg-surface-100 text-surface-600 text-xs font-bold rounded-lg uppercase">
                                GPX
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="font-display font-bold text-2xl text-surface-900 mb-2 group-hover:text-red-600 transition-colors">
                            15K Trail Challenge
                        </h3>
                    </div>

                    <!-- Action Area -->
                    <div class="border-t border-surface-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs text-surface-400 font-medium">{{ __('messages.docs_file_size') }}</span>
                            <span class="text-xs text-surface-600 font-semibold">561 KB</span>
                        </div>
                        <a href="{{ asset('gpx/15K_Era_Trail_Run_2026.gpx') }}" download
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold rounded-2xl shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ __('messages.gpx_download') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
