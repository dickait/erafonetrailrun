@extends('layouts.public')
@section('title', 'Erafone Trail Run 2026 - Ultimate Trail Running Adventure')
@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-dark-900 via-dark-800 to-forest-950"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(34,197,94,0.08),transparent_60%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(34,197,94,0.05),transparent_40%)]"></div>
    <!-- Particles -->
    <div class="absolute inset-0" id="particles">
        @for($i = 0; $i < 6; $i++)
        <div class="absolute w-1.5 h-1.5 bg-forest-500/30 rounded-full animate-pulse" style="top: {{ rand(10,90) }}%; left: {{ rand(10,90) }}%; animation-delay: {{ $i * 0.5 }}s;"></div>
        @endfor
    </div>
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pt-20">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-forest-900/40 border border-forest-700/30 rounded-full mb-8 animate-pulse">
            <span class="w-2 h-2 rounded-full bg-forest-400"></span>
            <span class="text-sm font-medium text-forest-300">{{ __('messages.hero_badge') }}</span>
        </div>
        <h1 class="font-display font-black text-5xl md:text-7xl lg:text-8xl mb-6 leading-tight">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-100 to-gray-300">ERAFONE</span><br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-forest-300 via-forest-400 to-earth-400">TRAIL RUN</span>
        </h1>
        <p class="font-display text-3xl md:text-4xl font-bold text-earth-400/70 mb-6">{{ $event->event_date->format('Y') ?? '2026' }}</p>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto mb-10">
            {{ __('messages.hero_subtitle') }} <span class="text-forest-400 font-semibold">{{ __('messages.hero_location') }}</span>. {{ __('messages.hero_description') }}
        </p>
        <!-- Countdown -->
        @if($event && $event->event_date->isFuture())
        <div class="flex justify-center gap-4 md:gap-6 mb-10" id="countdown" data-target="{{ $event->event_date->toIso8601String() }}">
            @foreach(['days' => __('messages.countdown_days'), 'hours' => __('messages.countdown_hours'), 'minutes' => __('messages.countdown_minutes'), 'seconds' => __('messages.countdown_seconds')] as $unit => $label)
            <div class="flex flex-col items-center">
                <div class="w-18 h-18 md:w-22 md:h-22 bg-dark-800/60 border border-forest-800/40 rounded-2xl flex items-center justify-center mb-2">
                    <span id="{{ $unit }}" class="font-display text-3xl md:text-4xl font-bold text-white">00</span>
                </div>
                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ $label }}</span>
            </div>
            @endforeach
        </div>
        @endif
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#categories" class="px-8 py-4 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold rounded-2xl shadow-xl shadow-forest-500/25 hover:shadow-forest-500/40 transition-all duration-300 transform hover:-translate-y-1 text-lg">
                {{ __('messages.hero_register') }}
            </a>
            <a href="#about" class="px-8 py-4 bg-dark-800/60 border border-forest-800/40 hover:border-forest-600/60 text-white font-semibold rounded-2xl transition-all duration-300 hover:bg-dark-700/60">
                {{ __('messages.hero_learn_more') }}
            </a>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-dark-900 to-transparent"></div>
</section>

<!-- About Section -->
<section id="about" class="py-20 md:py-28 bg-dark-900 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-block px-4 py-1.5 bg-forest-900/40 text-forest-400 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.about_badge') }}</span>
                <h2 class="font-display font-bold text-3xl md:text-5xl text-white mb-6 leading-tight">{{ __('messages.about_title') }} <span class="text-forest-400">{{ __('messages.about_title_highlight') }}</span></h2>
                <p class="text-gray-400 leading-relaxed text-lg mb-8">{{ $event->description ?? '' }}</p>
                <div class="grid grid-cols-2 gap-4">
                    @php
                    $aboutStats = [
                        ['value' => '1000+', 'label' => __('messages.about_runners')],
                        ['value' => $event->categories->count(), 'label' => __('messages.about_categories')],
                        ['value' => '21K', 'label' => __('messages.about_max_distance')],
                        ['value' => '800m', 'label' => __('messages.about_elevation')],
                    ];
                    @endphp
                    @foreach($aboutStats as $stat)
                    <div class="bg-dark-800/60 border border-forest-900/30 rounded-xl p-4">
                        <p class="font-display text-2xl font-bold text-forest-400">{{ $stat['value'] }}</p>
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-forest-900/40 to-dark-800 rounded-3xl p-8 border border-forest-800/30 aspect-square flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 mx-auto text-forest-500/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="font-display text-xl font-bold text-forest-400">{{ __('messages.hero_location') }}</p>
                        <p class="text-gray-500">Sentul, Bogor</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-20 md:py-28 bg-gradient-to-b from-dark-900 to-dark-800 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-forest-900/40 text-forest-400 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.categories_badge') }}</span>
            <h2 class="font-display font-bold text-3xl md:text-5xl text-white mb-4">{{ __('messages.categories_title') }} <span class="text-forest-400">{{ __('messages.categories_title_highlight') }}</span></h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">{{ __('messages.categories_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
            $categoryColors = [
                '10k-challenge' => ['amber', 'from-amber-600 to-amber-500', 'amber-500', 'amber-900', 'text-amber-400', 'border-amber-800/30', 'bg-amber-900/30'],
                '21k-ultra-trail' => ['red', 'from-red-600 to-red-500', 'red-500', 'red-900', 'text-red-400', 'border-red-800/30', 'bg-red-900/30'],
                '5k-fun-run' => ['green', 'from-forest-600 to-forest-500', 'forest-500', 'forest-900', 'text-forest-400', 'border-forest-800/30', 'bg-forest-900/30'],
            ];
            @endphp
            @foreach($categories as $cat)
            @php
            $colors = $categoryColors[$cat->slug] ?? $categoryColors['5k-fun-run'];
            $distKm = $cat->distance_km ?? substr($cat->slug, 0, strpos($cat->slug, 'k'));
            @endphp
            <div class="bg-dark-800/60 border {{ $colors[5] }} rounded-2xl p-8 hover:border-{{ $colors[2] }}/50 transition-all duration-300 hover:-translate-y-2 group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $colors[1] }}"></div>
                <div class="w-16 h-16 {{ $colors[6] }} rounded-2xl flex items-center justify-center mb-6">
                    <span class="font-display text-xl font-bold {{ $colors[4] }}">{{ strtoupper(explode('-', $cat->slug)[0]) }}</span>
                </div>
                <h3 class="font-display text-xl font-bold text-white mb-3">{{ $cat->name }}</h3>
                <p class="text-gray-400 text-sm mb-6">{{ $cat->description }}</p>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm"><span class="text-gray-500">{{ __('messages.categories_distance') }}</span><span class="text-white font-medium">{{ $distKm }} km</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">{{ __('messages.categories_price') }}</span><span class="text-white font-medium">Rp {{ number_format($cat->getCurrentPrice(), 0, ',', '.') }}</span></div>
                    @if($cat->isEarlyBird())
                    <div class="flex justify-between text-sm"><span class="{{ $colors[4] }}">{{ __('messages.categories_early_bird') }}</span><span class="{{ $colors[4] }} font-medium">Rp {{ number_format($cat->early_bird_price, 0, ',', '.') }}</span></div>
                    @endif
                </div>
                <!-- Entitlements -->
                <div class="mb-6 pt-4 border-t border-dark-600/50">
                    <p class="text-sm font-semibold text-white mb-3">{{ __('messages.categories_entitlements') }}:</p>
                    <ul class="text-sm text-gray-400 space-y-2">
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-forest-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_jersey') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-forest-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_medal') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-forest-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_racepack') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-forest-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_refreshment') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-forest-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_cert') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-forest-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_timing') }}</li>
                        @if($cat->slug === '21k-ultra-trail')
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-earth-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-earth-400 font-medium">{{ __('messages.categories_item_finisher_tee') }}</span></li>
                        @endif
                    </ul>
                </div>
                <a href="{{ route('register.create', ['category' => $cat->id]) }}" class="block w-full py-3 text-center bg-gradient-to-r {{ $colors[1] }} hover:opacity-90 text-white font-semibold rounded-xl transition-all duration-200 mt-auto">
                    {{ __('messages.categories_register_for') }} {{ $cat->name }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-dark-800 relative">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(34,197,94,0.06),transparent_70%)]"></div>
    <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
        <h2 class="font-display font-bold text-3xl md:text-4xl text-white mb-6">{{ __('messages.cta_title') }}</h2>
        <p class="text-gray-400 text-lg mb-8">{{ __('messages.cta_subtitle') }}</p>
        <a href="{{ route('register.create') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold rounded-2xl shadow-xl shadow-forest-500/25 hover:shadow-forest-500/40 transition-all duration-300 transform hover:-translate-y-1 text-lg">
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
