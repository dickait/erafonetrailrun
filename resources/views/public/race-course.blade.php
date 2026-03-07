@extends('layouts.public')
@section('title', __('messages.race_course_title') . ' - Erafone Trail Run 2026')
@section('meta_description', __('messages.race_course_subtitle'))

@section('content')
    <section class="pt-28 pb-20 bg-surface-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">
                    {{ __('messages.race_course_badge') }}
                </span>
                <h1 class="font-display font-bold text-4xl md:text-5xl text-surface-900 mb-6">
                    {{ __('messages.race_course_title') }}
                </h1>
                <p class="text-lg text-surface-600 max-w-2xl mx-auto">
                    {{ __('messages.race_course_subtitle') }}
                </p>
            </div>

            @if($categories->count() > 0)
                <!-- Tabs Navigation -->
                <div class="flex flex-wrap justify-center gap-2 mb-12">
                    @foreach($categories as $index => $category)
                        <button onclick="switchTab('{{ $category->slug }}')" id="tab-btn-{{ $category->slug }}"
                            class="tab-btn px-6 py-3 rounded-full font-semibold transition-all duration-300 {{ $index === 0 ? 'bg-brand-500 text-white shadow-lg' : 'bg-white text-surface-600 border border-surface-200 hover:bg-surface-50 hover:text-brand-500' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Tabs Content -->
                <div class="relative">
                    @foreach($categories as $index => $category)
                        @php
                            $usia = $category->slug == '5k-family-trail' ? '7+' : '18+';
                        @endphp
                        <div id="tab-content-{{ $category->slug }}"
                            class="tab-content {{ $index === 0 ? '' : 'hidden' }} animate-fade-in">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 items-center">
                                <!-- Image Section -->
                                <div class="lg:col-span-7 rounded-4xl overflow-hidden shadow-2xl relative group bg-surface-100 border border-surface-200 aspect-video lg:aspect-[4/3] flex items-center justify-center">
                                    @php
                                        // Handle specific folder name differences if any mapping is needed
                                        $imageFolder = str_replace('-trail', '', $category->slug); 
                                    @endphp
                                    <img src="{{ asset('images/race-course/' . $imageFolder . '/' . $imageFolder . '.webp') }}"
                                        alt="{{ $category->name }} Route"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                                        onerror="this.src='{{ asset('images/placeholders/route.jpg') }}'">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-surface-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                </div>

                                <!-- Details Section -->
                                <div class="lg:col-span-5 space-y-8">
                                    <div>
                                        <h2 class="text-3xl font-display font-bold text-surface-900 mb-4">{{ $category->name }}
                                            Route</h2>
                                        <p class="text-surface-600 text-lg leading-relaxed">
                                            {{ __('messages.cat_' . $category->slug . '_desc') }}
                                        </p>
                                    </div>

                                    <!-- Detail Cards -->
                                    <div class="grid grid-cols-2 gap-5">
                                        <!-- Distance -->
                                        <div
                                            class="bg-white p-6 rounded-2xl border border-surface-200 hover:border-brand-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                            <div
                                                class="w-10 h-10 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center mb-4 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                </svg>
                                            </div>
                                            <div class="text-surface-500 text-sm font-medium mb-1">
                                                {{ __('messages.categories_distance') }}</div>
                                            <div class="text-3xl font-display font-bold text-surface-900">
                                                {{ $category->distance_km }} <span
                                                    class="text-base font-normal text-surface-500">km</span></div>
                                        </div>

                                        <!-- Elevation -->
                                        <div
                                            class="bg-white p-6 rounded-2xl border border-surface-200 hover:border-accent-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                            <div
                                                class="w-10 h-10 rounded-full bg-accent-50 text-accent-500 flex items-center justify-center mb-4 group-hover:bg-accent-500 group-hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-surface-500 text-sm font-medium mb-1">
                                                {{ __('messages.categories_elevation') }}</div>
                                            <div class="text-3xl font-display font-bold text-accent-500">
                                                {{ $category->elevation ?? 0 }} <span
                                                    class="text-base font-normal text-surface-500">m</span></div>
                                        </div>

                                        <!-- COT -->
                                        <div
                                            class="bg-white p-6 rounded-2xl border border-surface-200 hover:border-brand-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                            <div
                                                class="w-10 h-10 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center mb-4 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-surface-500 text-sm font-medium mb-1">
                                                {{ __('messages.categories_cot') }}</div>
                                            <div class="text-3xl font-display font-bold text-surface-900">{{ $category->cot ?? 0 }}
                                                <span
                                                    class="text-base font-normal text-surface-500">{{ app()->getLocale() == 'id' ? 'Jam' : 'Hrs' }}</span>
                                            </div>
                                        </div>

                                        <!-- Age -->
                                        <div
                                            class="bg-white p-6 rounded-2xl border border-surface-200 hover:border-brand-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                            <div
                                                class="w-10 h-10 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center mb-4 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="text-surface-500 text-sm font-medium mb-1">
                                                {{ __('messages.categories_age') }}</div>
                                            <div class="text-3xl font-display font-bold text-surface-900">{{ $usia }} <span
                                                    class="text-base font-normal text-surface-500">{{ __('messages.cat_age') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CTA -->
                                    <div class="pt-6">
                                        <a href="{{ route('register.create', ['category' => $category->id]) }}"
                                            class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:-translate-y-1 transition-all duration-300">
                                            {{ __('messages.categories_register_for') ?? 'Register for' }} {{ $category->name }}
                                            &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border border-surface-200">
                    <p class="text-surface-500">{{ __('messages.admin_no_data') }}</p>
                </div>
            @endif
        </div>
    </section>

    @push('styles')
        <style>
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function switchTab(slug) {
                // Hide all contents
                document.querySelectorAll('.tab-content').forEach(el => {
                    el.classList.add('hidden');
                });

                // Reset all buttons
                document.querySelectorAll('.tab-btn').forEach(el => {
                    el.classList.remove('bg-brand-500', 'text-white', 'shadow-lg');
                    el.classList.add('bg-white', 'text-surface-600');
                });

                // Show selected content
                document.getElementById('tab-content-' + slug).classList.remove('hidden');

                // Highlight selected button
                const activeBtn = document.getElementById('tab-btn-' + slug);
                activeBtn.classList.remove('bg-white', 'text-surface-600');
                activeBtn.classList.add('bg-brand-500', 'text-white', 'shadow-lg');
            }
        </script>
    @endpush
@endsection