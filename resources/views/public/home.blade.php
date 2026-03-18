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
                        <div
                            class="bg-surface-50 border border-surface-300 rounded-xl p-4 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-brand-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span
                                class="font-display font-bold text-surface-900 leading-tight">{{ __('messages.about_highlight_1') }}</span>
                        </div>
                        <div
                            class="bg-surface-50 border border-surface-300 rounded-xl p-4 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-brand-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span
                                class="font-display font-bold text-surface-900 leading-tight">{{ __('messages.about_highlight_2') }}</span>
                        </div>
                        <div
                            class="bg-surface-50 border border-surface-300 rounded-xl p-4 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-brand-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143z" />
                            </svg>
                            <span
                                class="font-display font-bold text-surface-900 leading-tight">{{ __('messages.about_highlight_3') }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="relative w-full aspect-square overflow-hidden rounded-3xl shadow-2xl">
                        <img src="{{ asset('storage/last-event-photos/yog01152.webp') }}" alt="Last Event - Era Trail Run"
                            style="object-position: 50% 60%;"
                            loading="lazy"
                            class="w-full h-full object-cover object-center transition-transform duration-700 hover:scale-110" />
                        {{-- Ganti 'object-center' di atas dengan 'object-top' atau 'object-bottom' untuk
                        menaikkan/menurunkan fokus gambar --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
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
                        '5k-family-fun-trail' => ['green', 'from-emerald-500 to-emerald-600', 'emerald-500', 'emerald-100', 'text-emerald-600', 'border-emerald-200', 'bg-emerald-50'],
                    ];
                @endphp
                @foreach($categories as $cat)
                    @php
                        $colors = $categoryColors[$cat->slug] ?? $categoryColors['5k-family-fun-trail'];
                        $usia = $cat->slug == '5k-family-fun-trail' ? '10+' : '17+';
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
                            @php
                                $prices = $cat->prices->sortBy('price');
                                $minPrice = $prices->first()?->price ?? 0;
                                $maxPrice = $prices->last()?->price ?? 0;

                                $earlyBird = $cat->getActivePromotion('earlybird');
                                $discount = 0;
                                if ($earlyBird) {
                                    $discount = (float) $earlyBird->discount_value;
                                }

                                $showRange = $minPrice != $maxPrice;
                            @endphp
                            @if($cat->slug == '5k-family-fun-trail')
                                @foreach($cat->prices->sortBy('pax') as $cp)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-surface-700">{{ __('messages.categories_price') }} ({{ $cp->pax }} Pax)</span>
                                        <span class="text-surface-900 font-medium">
                                            @if($earlyBird)<strike class="opacity-50">@endif
                                                Rp {{ number_format($cp->price, 0, ',', '.') }}
                                                @if($earlyBird)</strike>@endif
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex justify-between text-sm"><span
                                        class="text-surface-700">{{ __('messages.categories_price') }}</span><span
                                        class="text-surface-900 font-medium">
                                        @if($earlyBird)<strike class="opacity-50">@endif
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @if($earlyBird)</strike>@endif
                                    </span>
                                </div>
                            @endif
                            @if($earlyBird)
                                <div class="flex justify-between text-sm items-center">
                                    <span class="{{ $colors[4] }} font-bold inline-flex items-center gap-1">
                                        {{ __('messages.categories_early_bird') }}
                                    </span>
                                    <span class="{{ $colors[4] }} font-bold">
                                        - Rp {{ number_format($discount, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div
                                    class="bg-gradient-to-r {{ $colors[1] }} text-white px-3 py-2 rounded-lg font-bold text-center mt-2 shadow-md">
                                    @if($cat->slug == '5k-family-fun-trail')
                                        @foreach($cat->prices->sortBy('pax') as $cp)
                                            <div class="text-xs">{{ __('messages.categories_now') }}: Rp
                                                {{ number_format($cp->price - $discount, 0, ',', '.') }}
                                                ({{ $cp->pax }} Pax)</div>
                                        @endforeach
                                    @else
                                        <div class="text-base">{{ __('messages.categories_now') }}: Rp
                                            {{ number_format($minPrice - $discount, 0, ',', '.') }}</div>
                                    @endif
                                </div>

                                <div class="text-[10px] text-surface-500 text-center italic mt-1">
                                    {{ __('messages.categories_early_bird_ends') }}:
                                    {{ $earlyBird->end_date ? $earlyBird->end_date->format('d M') : '-' }}
                                </div>
                            @endif
                            @if($cat->slug != '5k-family-fun-trail')
                                <div class="flex justify-between text-sm"><span
                                        class="text-surface-700">{{ __('messages.cat_category') }}</span><span
                                        class="text-surface-900 font-medium">{{ __('messages.cat_category_open') }}<br>{{ __('messages.cat_category_master') }}</span>
                                </div>
                            @endif
                            <!-- If categories is not 5k, write this -->
                            @if($cat->slug != '5k-family-fun-trail')
                                <div class="flex justify-between text-sm"><span class="text-surface-700">Podium: </span><span
                                        class="text-surface-900 font-medium text-right">{{ __('messages.cat_podium') }}<br>{{ __('messages.cat_podium_open_master') }}</span>
                                </div>
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
                                    </svg>
                                    @if($cat->slug != '5k-family-fun-trail')
                                        {{ __('messages.cat_bib_chip_time') }}
                                    @else
                                        BIB
                                    @endif
                                </li>
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_refreshment') }}</li>
                                <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>{{ __('messages.categories_item_sponsor_product') }}</li>
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

    <!-- Gallery Section -->
    <section id="gallery-preview" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display font-bold text-3xl md:text-4xl text-surface-900 mb-4">
                    {{ __('messages.gallery_section_title') }}
                </h2>
                <p class="text-surface-600 text-lg">
                    {{ __('messages.gallery_section_subtitle') }}
                </p>
            </div>

            @php
                $previewPhotos = [
                    'yog01298.webp', 'yog01447.webp', 'yog01659.webp',
                    'yog00911.webp', 'yog00628.webp', 'yog01155.webp',
                    'yog01210.webp', 'yog00977.webp', 'yog01574.webp'
                ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-12">
                @foreach($previewPhotos as $photo)
                    <div class="aspect-square overflow-hidden rounded-2xl cursor-pointer group relative"
                         onclick="openGalleryModal('{{ asset('storage/last-event-photos/' . $photo) }}', this)">
                        <img src="{{ asset('storage/last-event-photos/' . $photo) }}" 
                             alt="Era Trail Run Gallery"
                             loading="lazy"
                             class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('gallery') }}" 
                   class="inline-flex items-center gap-2 text-brand-600 font-bold hover:text-brand-700 transition-colors text-lg group">
                    {{ __('messages.gallery_view_all') }}
                    <span class="transition-transform group-hover:translate-x-1">→</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="gallery-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm" onclick="closeGalleryModal()"></div>
        <div class="relative h-full w-full flex items-center justify-center p-4 md:p-8 pointer-events-none">
            <button onclick="closeGalleryModal()" 
                    class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors z-10 pointer-events-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modal-image" src="" alt="Full Screen" 
                 class="max-w-full max-h-full object-contain rounded-xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0 pointer-events-auto">
        </div>
    </div>

    @push('scripts')
        <script>
            function openGalleryModal(imgSrc) {
                const modal = document.getElementById('gallery-modal');
                const img = document.getElementById('modal-image');
                img.src = imgSrc;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    img.classList.remove('scale-95', 'opacity-0');
                }, 10);
            }

            function closeGalleryModal() {
                const modal = document.getElementById('gallery-modal');
                const img = document.getElementById('modal-image');
                img.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }

            // Close modal on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeGalleryModal();
            });

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