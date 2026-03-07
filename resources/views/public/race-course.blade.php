@extends('layouts.public')
@section('title', __('messages.race_course_title') . ' - Erafone Trail Run 2026')
@section('meta_description', __('messages.race_course_subtitle'))

@section('content')
    <!-- Main Content -->
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
                @php
                    $categoryColors = [
                        '10k' => ['from-accent-500 to-accent-600', 'accent-500', 'bg-accent-50', 'text-accent-600', 'border-accent-200', 'shadow-accent-500/20'],
                        '15k' => ['from-brand-500 to-brand-600', 'brand-500', 'bg-brand-50', 'text-brand-500', 'border-brand-200', 'shadow-brand-500/20'],
                        '5k-family-trail' => ['from-emerald-500 to-emerald-600', 'emerald-500', 'bg-emerald-50', 'text-emerald-600', 'border-emerald-200', 'shadow-emerald-500/20'],
                    ];
                @endphp

                <!-- Tab Navigation -->
                <div class="flex justify-center mb-16">
                    <div class="inline-flex bg-surface-100 rounded-2xl p-1.5 border border-surface-200">
                        @foreach($categories as $index => $category)
                            <button onclick="switchTab('{{ $category->slug }}')" id="tab-btn-{{ $category->slug }}"
                                class="tab-btn cursor-pointer relative px-6 sm:px-8 py-3 rounded-xl text-sm sm:text-base font-semibold transition-all duration-300 {{ $index === 0 ? 'bg-white text-surface-900 shadow-md' : 'text-surface-500 hover:text-surface-700' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Contents -->
                @foreach($categories as $index => $category)
                    @php
                        $colors = $categoryColors[$category->slug] ?? $categoryColors['5k-family-trail'];
                        $usia = $category->slug == '5k-family-trail' ? '7+' : '18+';
                        $imageFolder = str_replace('-trail', '', $category->slug);
                    @endphp
                    <div id="tab-content-{{ $category->slug }}" class="tab-content {{ $index === 0 ? '' : 'hidden' }}">

                        <!-- Route Image - Full Width -->
                        <div class="mb-16 rounded-3xl overflow-hidden shadow-2xl relative group border border-surface-200">
                            <div class="aspect-[21/9] bg-surface-100">
                                <img src="{{ asset('images/race-course/' . $imageFolder . '/' . $imageFolder . '.webp') }}"
                                    alt="{{ $category->name }} Route Map"
                                    class="w-full h-full object-cover transform group-hover:scale-[1.02] transition-transform duration-700"
                                    onerror="this.src='{{ asset('images/placeholders/route.jpg') }}'">
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-surface-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                            <!-- Route name overlay -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-1 h-8 rounded-full bg-gradient-to-b {{ $colors[0] }}"></div>
                                    <h2 class="font-display font-bold text-xl md:text-2xl text-white drop-shadow-lg">
                                        {{ $category->name }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-16">
                            <!-- Distance -->
                            <div
                                class="bg-white rounded-2xl border border-surface-200 p-6 md:p-8 hover:shadow-lg hover:border-surface-300 transition-all duration-300 group">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-10 h-10 rounded-xl {{ $colors[2] }} {{ $colors[3] }} flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                                            </path>
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium text-surface-700">{{ __('messages.categories_distance') }}</span>
                                </div>
                                <div class="font-display">
                                    <span
                                        class="text-3xl md:text-4xl font-bold text-surface-900">{{ $category->distance_km }}</span>
                                    <span class="text-base text-surface-600 ml-1">km</span>
                                </div>
                            </div>

                            <!-- Elevation -->
                            <div
                                class="bg-white rounded-2xl border border-surface-200 p-6 md:p-8 hover:shadow-lg hover:border-surface-300 transition-all duration-300 group">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-10 h-10 rounded-xl {{ $colors[2] }} {{ $colors[3] }} flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12">
                                            </path>
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium text-surface-700">{{ __('messages.categories_elevation') }}</span>
                                </div>
                                <div class="font-display">
                                    <span
                                        class="text-3xl md:text-4xl font-bold text-surface-900">{{ $category->elevation ?? 0 }}</span>
                                    <span class="text-base text-surface-600 ml-1">m</span>
                                </div>
                            </div>

                            <!-- Cut-Off Time -->
                            <div
                                class="bg-white rounded-2xl border border-surface-200 p-6 md:p-8 hover:shadow-lg hover:border-surface-300 transition-all duration-300 group">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-10 h-10 rounded-xl {{ $colors[2] }} {{ $colors[3] }} flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-surface-700">{{ __('messages.categories_cot') }}</span>
                                </div>
                                <div class="font-display">
                                    <span class="text-3xl md:text-4xl font-bold text-surface-900">{{ $category->cot ?? 0 }}</span>
                                    <span
                                        class="text-base text-surface-600 ml-1">{{ app()->getLocale() == 'id' ? 'Jam' : 'Hrs' }}</span>
                                </div>
                            </div>

                            <!-- Age -->
                            <div
                                class="bg-white rounded-2xl border border-surface-200 p-6 md:p-8 hover:shadow-lg hover:border-surface-300 transition-all duration-300 group">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-10 h-10 rounded-xl {{ $colors[2] }} {{ $colors[3] }} flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-surface-700">{{ __('messages.categories_age') }}</span>
                                </div>
                                <div class="font-display">
                                    <span class="text-3xl md:text-4xl font-bold text-surface-900">{{ $usia }}</span>
                                    <span class="text-base text-surface-600 ml-1">{{ __('messages.cat_age') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description + CTA -->
                        <div class="max-w-3xl mx-auto text-center">
                            <p class="text-surface-600 text-lg leading-relaxed mb-10">
                                {{ __('messages.cat_' . $category->slug . '_desc') }}
                            </p>
                            <a href="{{ route('register.create', ['category' => $category->id]) }}"
                                class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r {{ $colors[0] }} text-white font-bold rounded-2xl shadow-lg {{ $colors[5] }} hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-lg">
                                {{ __('messages.categories_register_for') }} {{ $category->name }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3">
                                    </path>
                                </svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            @else
                <div class="text-center py-20 bg-surface-50 rounded-3xl border border-surface-200">
                    <svg class="w-16 h-16 mx-auto text-surface-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                        </path>
                    </svg>
                    <p class="text-surface-500 text-lg">{{ __('messages.admin_no_data') }}</p>
                </div>
            @endif
        </div>
    </section>

    @push('styles')
        <style>
            .tab-content:not(.hidden) {
                animation: rcFadeIn 0.4s ease-out forwards;
            }

            @keyframes rcFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(12px);
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
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

                document.querySelectorAll('.tab-btn').forEach(el => {
                    el.classList.remove('bg-white', 'text-surface-900', 'shadow-md');
                    el.classList.add('text-surface-500');
                });

                const content = document.getElementById('tab-content-' + slug);
                content.classList.remove('hidden');

                const btn = document.getElementById('tab-btn-' + slug);
                btn.classList.remove('text-surface-500');
                btn.classList.add('bg-white', 'text-surface-900', 'shadow-md');
            }
        </script>
    @endpush
@endsection