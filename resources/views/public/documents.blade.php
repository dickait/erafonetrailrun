@extends('layouts.public')

@section('title', __('messages.docs_title') . ' - Era Trail Run 2026')
@section('meta_description', __('messages.docs_subtitle'))

@section('content')
    <section class="pt-28 pb-24 bg-surface-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">
                    {{ __('messages.docs_badge') }}
                </span>
                <h1 class="font-display font-black text-3xl md:text-5xl text-surface-900 mb-6 uppercase tracking-tight">
                    {{ __('messages.docs_title') }}
                </h1>
                <div class="w-24 h-1.5 bg-brand-500 mx-auto mb-8 rounded-full"></div>
                <p class="text-surface-600 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                    {{ __('messages.docs_subtitle') }}
                </p>
            </div>

            <!-- Documents Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Document 1: Race Guide -->
                <div class="bg-white border border-surface-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:border-brand-500/30 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Icon & Format Badge -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h1.5m-1.5 3h4m-4 3h4" />
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-lg uppercase">
                                PDF
                            </span>
                        </div>

                        <!-- Content -->
                        <h3 class="font-display font-bold text-xl text-surface-900 mb-3 group-hover:text-brand-600 transition-colors">
                            {{ __('messages.docs_race_guide_title') }}
                        </h3>
                        <p class="text-surface-600 text-sm leading-relaxed mb-6">
                            {{ __('messages.docs_race_guide_desc') }}
                        </p>
                    </div>

                    <!-- Footer Info & Button -->
                    <div class="border-t border-surface-100 pt-6 mt-auto">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs text-surface-400 font-medium">{{ __('messages.docs_file_size') }}</span>
                            <span class="text-xs text-surface-700 font-semibold bg-surface-100 px-2.5 py-1 rounded-md">519 KB</span>
                        </div>
                        <a href="{{ asset('documents/race_guide_era_trail_run_2026.pdf') }}" target="_blank"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ __('messages.docs_download') }}
                        </a>
                    </div>
                </div>

                <!-- Document 2: Surat Kuasa -->
                <div class="bg-white border border-surface-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:border-brand-500/30 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Icon & Format Badge -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 text-xs font-bold rounded-lg uppercase">
                                PDF
                            </span>
                        </div>

                        <!-- Content -->
                        <h3 class="font-display font-bold text-xl text-surface-900 mb-3 group-hover:text-brand-600 transition-colors">
                            {{ __('messages.docs_surat_kuasa_title') }}
                        </h3>
                        <p class="text-surface-600 text-sm leading-relaxed mb-6">
                            {{ __('messages.docs_surat_kuasa_desc') }}
                        </p>
                    </div>

                    <!-- Footer Info & Button -->
                    <div class="border-t border-surface-100 pt-6 mt-auto">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs text-surface-400 font-medium">{{ __('messages.docs_file_size') }}</span>
                            <span class="text-xs text-surface-700 font-semibold bg-surface-100 px-2.5 py-1 rounded-md">239 KB</span>
                        </div>
                        <a href="{{ asset('documents/surat_kuasa_era_trail_run_2026.pdf') }}" target="_blank"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ __('messages.docs_download') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
