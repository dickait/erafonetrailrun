@extends('layouts.public')

@section('title', 'Erafone Trail Run 2026 - Ultimate Trail Running Adventure')
@section('meta_description', 'Join Erafone Trail Run 2026 at Gunung Pancar, Sentul, Bogor. Choose from 5K, 10K, or 21K categories. Register now!')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-dark-900 via-dark-800 to-forest-950"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-forest-900/30 via-transparent to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-earth-900/20 via-transparent to-transparent"></div>

    <!-- Animated particles -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-forest-400/30 rounded-full animate-pulse"></div>
        <div class="absolute top-1/3 right-1/3 w-1.5 h-1.5 bg-forest-300/20 rounded-full animate-pulse" style="animation-delay: 1s"></div>
        <div class="absolute bottom-1/4 left-1/3 w-1 h-1 bg-earth-400/30 rounded-full animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-2/3 right-1/4 w-2.5 h-2.5 bg-forest-500/20 rounded-full animate-pulse" style="animation-delay: 0.5s"></div>
    </div>

    <!-- Mountain silhouette -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 320" class="w-full text-dark-800/50" preserveAspectRatio="none">
            <path fill="currentColor" d="M0,192L48,197.3C96,203,192,213,288,186.7C384,160,480,96,576,90.7C672,85,768,139,864,154.7C960,171,1056,149,1152,133.3C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <div class="relative z-10 text-center px-4 sm:px-6 max-w-5xl mx-auto pt-24">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-forest-900/40 border border-forest-700/30 text-forest-300 text-sm font-medium mb-8 backdrop-blur-sm">
            <span class="w-2 h-2 bg-forest-400 rounded-full animate-pulse"></span>
            Registration Open
        </div>

        <h1 class="font-display font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl leading-tight tracking-tight mb-6">
            <span class="bg-gradient-to-r from-white via-forest-100 to-forest-300 bg-clip-text text-transparent">ERAFONE</span>
            <br>
            <span class="bg-gradient-to-r from-forest-400 via-forest-300 to-earth-400 bg-clip-text text-transparent">TRAIL RUN</span>
            <br>
            <span class="text-3xl sm:text-4xl md:text-5xl text-earth-400/80 font-bold">2026</span>
        </h1>

        <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto mb-8">
            Conquer the trails of <span class="text-forest-400 font-semibold">Gunung Pancar, Sentul, Bogor</span>. An epic journey through tropical forests and volcanic terrains.
        </p>

        @if($event)
        <!-- Countdown -->
        <div id="countdown" class="flex justify-center gap-4 sm:gap-6 mb-10" data-target="{{ $event->event_date->toIso8601String() }}">
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-dark-700/60 backdrop-blur-sm border border-forest-800/50 rounded-2xl flex items-center justify-center mb-2">
                    <span id="countdown-days" class="font-display font-bold text-2xl sm:text-3xl text-forest-400">--</span>
                </div>
                <span class="text-xs text-gray-500 uppercase tracking-wider">Days</span>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-dark-700/60 backdrop-blur-sm border border-forest-800/50 rounded-2xl flex items-center justify-center mb-2">
                    <span id="countdown-hours" class="font-display font-bold text-2xl sm:text-3xl text-forest-400">--</span>
                </div>
                <span class="text-xs text-gray-500 uppercase tracking-wider">Hours</span>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-dark-700/60 backdrop-blur-sm border border-forest-800/50 rounded-2xl flex items-center justify-center mb-2">
                    <span id="countdown-mins" class="font-display font-bold text-2xl sm:text-3xl text-forest-400">--</span>
                </div>
                <span class="text-xs text-gray-500 uppercase tracking-wider">Minutes</span>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-dark-700/60 backdrop-blur-sm border border-forest-800/50 rounded-2xl flex items-center justify-center mb-2">
                    <span id="countdown-secs" class="font-display font-bold text-2xl sm:text-3xl text-earth-400">--</span>
                </div>
                <span class="text-xs text-gray-500 uppercase tracking-wider">Seconds</span>
            </div>
        </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register.create') }}" class="px-8 py-4 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold rounded-2xl shadow-2xl shadow-forest-500/25 hover:shadow-forest-500/40 transition-all duration-300 transform hover:-translate-y-1 text-lg">
                Register Now →
            </a>
            <a href="#about" class="px-8 py-4 bg-dark-700/60 hover:bg-dark-700 border border-forest-800/50 hover:border-forest-700/50 text-white font-semibold rounded-2xl transition-all duration-200 text-lg backdrop-blur-sm">
                Learn More
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 sm:py-28 bg-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block px-3 py-1 text-xs font-semibold text-forest-400 uppercase tracking-wider bg-forest-900/40 rounded-full mb-4">About The Event</span>
                <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-white mb-6">
                    Run Through The Heart of <span class="text-forest-400">Nature</span>
                </h2>
                @if($event)
                <p class="text-gray-400 text-lg leading-relaxed mb-8">{{ $event->description }}</p>
                @endif
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-4 bg-dark-700/50 rounded-2xl border border-forest-900/30">
                        <div class="text-3xl font-display font-bold text-forest-400 mb-1">1000+</div>
                        <div class="text-sm text-gray-400">Runners Expected</div>
                    </div>
                    <div class="p-4 bg-dark-700/50 rounded-2xl border border-forest-900/30">
                        <div class="text-3xl font-display font-bold text-earth-400 mb-1">3</div>
                        <div class="text-sm text-gray-400">Race Categories</div>
                    </div>
                    <div class="p-4 bg-dark-700/50 rounded-2xl border border-forest-900/30">
                        <div class="text-3xl font-display font-bold text-forest-400 mb-1">21K</div>
                        <div class="text-sm text-gray-400">Max Distance</div>
                    </div>
                    <div class="p-4 bg-dark-700/50 rounded-2xl border border-forest-900/30">
                        <div class="text-3xl font-display font-bold text-earth-400 mb-1">800m</div>
                        <div class="text-sm text-gray-400">Elevation Gain</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-square rounded-3xl bg-gradient-to-br from-forest-900/40 to-dark-700 border border-forest-800/30 overflow-hidden flex items-center justify-center">
                    <div class="text-center p-8">
                        <svg class="w-24 h-24 mx-auto text-forest-600/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-forest-500/70 font-display font-bold text-xl">Gunung Pancar</p>
                        <p class="text-gray-500 text-sm">Sentul, Bogor</p>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gradient-to-br from-forest-500/20 to-earth-500/20 rounded-2xl blur-xl"></div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-20 sm:py-28 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-3 py-1 text-xs font-semibold text-forest-400 uppercase tracking-wider bg-forest-900/40 rounded-full mb-4">Race Categories</span>
            <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-white mb-4">Choose Your <span class="text-forest-400">Challenge</span></h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">From scenic fun runs to intense ultra trails - there's a category for every runner.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            @foreach($categories as $category)
            <div class="group relative bg-dark-800 rounded-3xl border border-forest-900/30 overflow-hidden hover:border-forest-700/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-forest-900/20">
                <div class="absolute top-0 left-0 right-0 h-1.5" style="background-color: {{ $category->color }}"></div>
                <div class="p-8 text-center">
                    <div class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center mb-6 transition-transform duration-500 group-hover:scale-110" style="background-color: {{ $category->color }}20">
                        <span class="font-display font-black text-3xl" style="color: {{ $category->color }}">{{ $category->distance_km }}K</span>
                    </div>
                    <h3 class="font-display font-bold text-xl text-white mb-3">{{ $category->name }}</h3>
                    <p class="text-gray-400 text-sm mb-6 leading-relaxed">{{ $category->description }}</p>

                    <div class="space-y-3 mb-8">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Distance</span>
                            <span class="text-white font-semibold">{{ $category->distance_km }} km</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Quota</span>
                            <span class="text-white font-semibold">{{ $category->quota }} runners</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Price</span>
                            <span class="text-white font-semibold">Rp {{ number_format($category->getCurrentPrice(), 0, ',', '.') }}</span>
                        </div>
                        @if($category->early_bird_price && $category->early_bird_deadline && now()->lte($category->early_bird_deadline))
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-earth-400 font-medium">🐦 Early Bird</span>
                            <span class="text-earth-400 font-bold">Rp {{ number_format($category->early_bird_price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('register.create') }}" class="block w-full py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 text-white hover:shadow-lg" style="background-color: {{ $category->color }}; --tw-shadow-color: {{ $category->color }}40">
                        Register for {{ $category->name }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-forest-900 via-forest-800 to-forest-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-forest-700/20 via-transparent to-transparent"></div>
    <div class="relative z-10 max-w-4xl mx-auto text-center px-4">
        <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-white mb-6">Ready to Conquer the Trail?</h2>
        <p class="text-forest-200/80 text-lg mb-8 max-w-2xl mx-auto">Don't miss out on this incredible experience. Spots are limited. Register today and secure your place.</p>
        <a href="{{ route('register.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-forest-800 font-bold rounded-2xl shadow-2xl hover:shadow-white/20 transition-all duration-300 transform hover:-translate-y-1 text-lg">
            Register Now
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Countdown timer
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        const targetDate = new Date(countdownEl.dataset.target).getTime();
        function updateCountdown() {
            const now = new Date().getTime();
            const diff = targetDate - now;
            if (diff <= 0) {
                document.getElementById('countdown-days').textContent = '0';
                document.getElementById('countdown-hours').textContent = '0';
                document.getElementById('countdown-mins').textContent = '0';
                document.getElementById('countdown-secs').textContent = '0';
                return;
            }
            document.getElementById('countdown-days').textContent = Math.floor(diff / (1000 * 60 * 60 * 24));
            document.getElementById('countdown-hours').textContent = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            document.getElementById('countdown-mins').textContent = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            document.getElementById('countdown-secs').textContent = Math.floor((diff % (1000 * 60)) / 1000);
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
</script>
@endpush
