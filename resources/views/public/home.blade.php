@extends('layouts.public')
@section('title', 'ERA TRAIL RUN 2026 - Ultimate Trail Running Adventure')
@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-900 via-brand-800 to-brand-950"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(239,28,36,0.3),transparent_60%)]"></div>
        <!-- Particles -->
        <div class="absolute inset-0" id="particles">
            @for($i = 0; $i < 6; $i++)
                <div class="absolute w-1.5 h-1.5 bg-accent-400/40 rounded-full animate-pulse"
                    style="top: {{ rand(10, 90) }}%; left: {{ rand(10, 90) }}%; animation-delay: {{ $i * 0.5 }}s;"></div>
            @endfor
        </div>
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pt-20">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 border border-white/20 rounded-full mb-8 animate-pulse">
                <span class="w-2 h-2 rounded-full bg-accent-400"></span>
                <span class="text-sm font-medium text-white/90">{{ __('messages.hero_badge') }}</span>
            </div>
            <h1 class="font-display font-black text-5xl md:text-7xl lg:text-8xl mb-6 leading-tight">
                <span class="text-white">ERA</span><br>
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-accent-300 via-accent-400 to-accent-200">TRAIL
                    RUN</span>
            </h1>
            <p class="text-white/80 text-xl font-semibold max-w-2xl mx-auto mb-3">
                {{ __('messages.hero_tagline') }}
            </p>
            <p class="font-display text-2xl md:text-3xl font-bold text-white/80 mb-6">
                <span class="text-accent-300">{{ __('messages.hero_location') }}</span><br>
                {{ \Carbon\Carbon::parse($event->event_date)->locale(app()->getLocale())->translatedFormat('l, d F Y') }}
            </p>
            <!-- <p class="text-white/80 text-lg max-w-2xl mx-auto mb-6">
                                        {{ __('messages.hero_desc') }}
                                    </p> -->
            <!-- <p
                                class="text-white/90 text-lg max-w-2xl mx-auto mb-10 font-bold bg-white/10 inline-block px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">
                                {{ __('messages.hero_categories_label') }} 5K &bull; 10K &bull; 15K
                            </p> -->
            <!-- Countdown -->
            @if($event && $event->event_date->isFuture())
                <div class="flex justify-center gap-4 md:gap-6 mb-10" id="countdown"
                    data-target="{{ $event->event_date->toIso8601String() }}">
                    @foreach(['days' => __('messages.countdown_days'), 'hours' => __('messages.countdown_hours'), 'minutes' => __('messages.countdown_minutes'), 'seconds' => __('messages.countdown_seconds')] as $unit => $label)
                        <div class="flex flex-col items-center">
                            <div
                                class="w-18 h-18 md:w-22 md:h-22 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center mb-2 backdrop-blur-sm">
                                <span id="{{ $unit }}" class="font-display text-3xl md:text-4xl font-bold text-white">00</span>
                            </div>
                            <span class="text-xs text-white/50 uppercase tracking-wider">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#categories"
                    class="px-8 py-4 bg-white hover:bg-surface-100 text-brand-600 font-bold rounded-2xl shadow-xl shadow-black/10 hover:shadow-black/20 transition-all duration-300 transform hover:-translate-y-1 text-lg">
                    {{ __('messages.hero_register') }}
                </a>
                <a href="#about"
                    class="px-8 py-4 bg-white/10 border border-white/20 hover:border-white/40 text-white font-semibold rounded-2xl transition-all duration-300 hover:bg-white/20 backdrop-blur-sm">
                    {{ __('messages.hero_learn_more') }}
                </a>
            </div>
        </div>

    </section>

    <!-- About Section -->
    <section id="about" class="py-20 md:py-28 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span
                        class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.about_badge') }}</span>
                    <h2 class="font-display font-bold text-3xl md:text-5xl text-surface-900 mb-6 leading-tight">
                        {{ __('messages.about_title') }} <span
                            class="text-brand-500">{{ __('messages.about_title_highlight') }}</span>
                    </h2>
                    <p class="text-surface-700 leading-relaxed text-lg mb-8">{{ __('messages.about_description') }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-surface-50 border border-surface-300 rounded-xl p-4 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-brand-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="font-display font-bold text-surface-900 leading-tight">{{ __('messages.about_highlight_1') }}</span>
                        </div>
                        <div class="bg-surface-50 border border-surface-300 rounded-xl p-4 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-brand-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-display font-bold text-surface-900 leading-tight">{{ __('messages.about_highlight_2') }}</span>
                        </div>
                        <div class="bg-surface-50 border border-surface-300 rounded-xl p-4 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-brand-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143z" />
                            </svg>
                            <span class="font-display font-bold text-surface-900 leading-tight">{{ __('messages.about_highlight_3') }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="bg-surface-100 rounded-3xl p-8 border border-surface-200 aspect-square flex flex-col items-center justify-center text-surface-400">
                        <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="font-display font-medium text-lg">[Foto Trail Run / Alam]</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-20 md:py-28 bg-gradient-to-b from-white to-surface-100 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.categories_badge') }}</span>
                <h2 class="font-display font-bold text-3xl md:text-5xl text-surface-900 mb-4">
                    {{ __('messages.categories_title') }} <span
                        class="text-brand-500">{{ __('messages.categories_title_highlight') }}</span>
                </h2>
                <p class="text-surface-700 text-lg max-w-2xl mx-auto">{{ __('messages.categories_subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $categoryColors = [
                        '10k' => ['accent', 'from-accent-500 to-accent-600', 'accent-500', 'accent-100', 'text-accent-600', 'border-accent-200', 'bg-accent-50'],
                        '15k' => ['brand', 'from-brand-500 to-brand-600', 'brand-500', 'brand-100', 'text-brand-500', 'border-brand-200', 'bg-brand-50'],
                        '5k-family-trail' => ['green', 'from-emerald-500 to-emerald-600', 'emerald-500', 'emerald-100', 'text-emerald-600', 'border-emerald-200', 'bg-emerald-50'],
                    ];
                @endphp
                @foreach($categories as $cat)
                    @php
                        $colors = $categoryColors[$cat->slug] ?? $categoryColors['5k-family-trail'];
                        $usia = $cat->slug == '5k-family-trail' ? '7+' : '18+';
                    @endphp
                    <div
                        class="bg-white border {{ $colors[5] }} rounded-2xl p-8 hover:border-{{ $colors[2] }}/50 transition-all duration-300 hover:-translate-y-2 group relative overflow-hidden shadow-sm hover:shadow-xl">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $colors[1] }}"></div>
                        <div class="w-16 h-16 {{ $colors[6] }} rounded-2xl flex items-center justify-center mb-6">
                            <span
                                class="font-display text-xl font-bold {{ $colors[4] }}">{{ strtoupper(explode('-', $cat->slug)[0]) }}</span>
                        </div>
                        <h3 class="font-display text-xl font-bold text-surface-900 mb-3">{{ $cat->name }}</h3>
                        <p class="text-surface-700 text-sm mb-6">{{ __('messages.cat_' . $cat->slug . '_desc') }}</p>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm"><span
                                    class="text-surface-700">{{ __('messages.categories_distance') }}</span><span
                                    class="text-surface-900 font-medium">{{ $cat->distance_km }} km</span></div>
                            <div class="flex justify-between text-sm"><span
                                    class="text-surface-700">{{ __('messages.categories_elevation') }}</span><span
                                    class="text-surface-900 font-medium">{{ $cat->elevation ?? 0 }} m</span></div>
                            <div class="flex justify-between text-sm"><span
                                    class="text-surface-700">{{ __('messages.categories_cot') }}</span><span
                                    class="text-surface-900 font-medium">{{ $cat->cot ?? 0 }}
                                    {{ app()->getLocale() == 'id' ? 'Jam' : 'Hours' }}</span></div>
                            <div class="flex justify-between text-sm"><span
                                    class="text-surface-700">{{ __('messages.categories_age') }}</span><span
                                    class="text-surface-900 font-medium">{{ $usia }} {{ __('messages.cat_age') }}</span></div>
                            <div class="flex justify-between text-sm"><span
                                    class="text-surface-700">{{ __('messages.categories_price') }}</span><span
                                    class="text-surface-900 font-medium">Rp
                                    {{ number_format($cat->getCurrentPrice(), 0, ',', '.') }}</span></div>
                            @if($cat->isEarlyBird())
                                <div class="flex justify-between text-sm"><span
                                        class="{{ $colors[4] }}">{{ __('messages.categories_early_bird') }}</span><span
                                        class="{{ $colors[4] }} font-medium">Rp
                                        {{ number_format($cat->early_bird_price, 0, ',', '.') }}</span></div>
                            @endif
                        </div>
                        <!-- Entitlements -->
                        <div class="mb-6 pt-4 border-t border-surface-300">
                            <p class="text-sm font-semibold text-surface-900 mb-3">{{ __('messages.categories_entitlements') }}:
                            </p>
                            <ul class="text-sm text-surface-700 space-y-2">
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_jersey') }}</li>
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_medal') }}</li>
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_racepack') }}</li>
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_refreshment') }}</li>
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_cert') }}</li>
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_timing') }}</li>
                                @if($cat->slug === '15k')
                                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-accent-500 mt-0.5 flex-shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg><span
                                            class="text-accent-600 font-medium">{{ __('messages.categories_item_finisher_tee') }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <a href="{{ route('register.create', ['category' => $cat->id]) }}"
                            class="block w-full py-3 text-center bg-gradient-to-r {{ $colors[1] }} hover:opacity-90 text-white font-semibold rounded-xl transition-all duration-200 mt-auto shadow-md">
                            {{ __('messages.categories_register_for') }} {{ $cat->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-brand-900 via-brand-800 to-brand-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(245,158,11,0.12),transparent_70%)]"></div>
        <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
            <h2 class="font-display font-bold text-3xl md:text-4xl text-white mb-6">{{ __('messages.cta_title') }}</h2>
            <p class="text-white/80 text-lg mb-8">{{ __('messages.cta_subtitle') }}</p>
            <a href="{{ route('register.create') }}"
                class="inline-block px-8 py-4 bg-white hover:bg-surface-100 text-brand-600 font-bold rounded-2xl shadow-xl shadow-black/10 hover:shadow-black/20 transition-all duration-300 transform hover:-translate-y-1 text-lg">
                {{ __('messages.cta_register') }}
            </a>
        </div>
    </section>

    @push('scripts')
        <script>
            const countdown = document.getElementById('countdown');
            if (countdown) {
                const target = new Date(countdown.dataset.target).getTime();
                setInterval(() => {
                    const now = new Date().getTime();
                    const diff = target - now;
                    if (diff > 0) {
                        document.getElementById('days').textContent = Math.floor(diff / (1000 * 60 * 60 * 24));
                        document.getElementById('hours').textContent = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        document.getElementById('minutes').textContent = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        document.getElementById('seconds').textContent = Math.floor((diff % (1000 * 60)) / 1000);
                    }
                }, 1000);
            }

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection