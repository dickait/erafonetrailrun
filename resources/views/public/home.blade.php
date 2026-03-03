@extends('layouts.public')
@section('title', 'ERA Trail Run 2026 - Trail Run with Smart Experience')
@section('meta_description', 'ERA Trail Run 2026 di Bogor Nirwana Residence. 2000 Runners, 3 Categories, 1 Epic Trail Experience. Bagian dari rangkaian Jelajah Era.')
@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-brand-900 via-brand-800 to-brand-950"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(239,28,36,0.3),transparent_60%)]"></div>
    <!-- Particles -->
    <div class="absolute inset-0" id="particles">
        @for($i = 0; $i < 6; $i++)
        <div class="absolute w-1.5 h-1.5 bg-accent-400/40 rounded-full animate-pulse" style="top: {{ rand(10,90) }}%; left: {{ rand(10,90) }}%; animation-delay: {{ $i * 0.5 }}s;"></div>
        @endfor
    </div>
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pt-20">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 border border-white/20 rounded-full mb-4 animate-pulse">
            <span class="w-2 h-2 rounded-full bg-accent-400"></span>
            <span class="text-sm font-medium text-white/90">{{ __('messages.hero_badge') }}</span>
        </div>
        <!-- <p class="text-accent-300/80 text-sm font-medium tracking-widest uppercase mb-6">{{ __('messages.hero_tagline') }}</p> -->
        <h1 class="font-display font-black text-5xl md:text-7xl lg:text-8xl mb-6 leading-tight">
            <span class="text-white">ERA</span><br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent-300 via-accent-400 to-accent-200">TRAIL RUN</span>
        </h1>
        <p class="font-display text-3xl md:text-4xl font-bold text-white/60 mb-6">{{ $event->event_date->format('Y') ?? '2026' }}</p>
        <p class="text-white/80 text-lg max-w-2xl mx-auto mb-4">
            {{ __('messages.hero_subtitle') }} <span class="text-accent-300 font-semibold">{{ __('messages.hero_location') }}</span>. {{ __('messages.hero_description') }}
        </p>
        <!-- Highlight Stats -->
        <!-- <p class="text-white/60 text-sm font-medium tracking-wider mb-10">{{ __('messages.hero_highlight') }}</p> -->
        <!-- Countdown -->
        @if($event && $event->event_date->isFuture())
        <div class="flex justify-center gap-4 md:gap-6 mb-10" id="countdown" data-target="{{ $event->event_date->toIso8601String() }}">
            @foreach(['days' => __('messages.countdown_days'), 'hours' => __('messages.countdown_hours'), 'minutes' => __('messages.countdown_minutes'), 'seconds' => __('messages.countdown_seconds')] as $unit => $label)
            <div class="flex flex-col items-center">
                <div class="w-18 h-18 md:w-22 md:h-22 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center mb-2 backdrop-blur-sm">
                    <span id="{{ $unit }}" class="font-display text-3xl md:text-4xl font-bold text-white">00</span>
                </div>
                <span class="text-xs text-white/50 uppercase tracking-wider">{{ $label }}</span>
            </div>
            @endforeach
        </div>
        @endif
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#categories" class="px-8 py-4 bg-white hover:bg-surface-100 text-brand-600 font-bold rounded-2xl shadow-xl shadow-black/10 hover:shadow-black/20 transition-all duration-300 transform hover:-translate-y-1 text-lg">
                {{ __('messages.hero_register') }}
            </a>
            <a href="#about" class="px-8 py-4 bg-white/10 border border-white/20 hover:border-white/40 text-white font-semibold rounded-2xl transition-all duration-300 hover:bg-white/20 backdrop-blur-sm">
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
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.about_badge') }}</span>
                <h2 class="font-display font-bold text-3xl md:text-5xl text-surface-900 mb-6 leading-tight">{{ __('messages.about_title') }} <span class="text-brand-500">{{ __('messages.about_title_highlight') }}</span></h2>
                <p class="text-surface-700 leading-relaxed text-lg mb-4">{{ __('messages.about_description') }}</p>
                <p class="text-surface-600 leading-relaxed mb-8">{{ __('messages.about_description_2') }}</p>
                <div class="grid grid-cols-2 gap-4">
                    @php
                    $aboutStats = [
                        ['value' => '2000', 'label' => __('messages.about_runners')],
                        ['value' => '3', 'label' => __('messages.about_categories')],
                        ['value' => '15K', 'label' => __('messages.about_max_distance')],
                        ['value' => '294m', 'label' => __('messages.about_elevation')],
                    ];
                    @endphp
                    @foreach($aboutStats as $stat)
                    <div class="bg-surface-50 border border-surface-300 rounded-xl p-4">
                        <p class="font-display text-2xl font-bold text-brand-500">{{ $stat['value'] }}</p>
                        <p class="text-sm text-surface-700">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-brand-50 to-accent-50 rounded-3xl p-8 border border-brand-100 aspect-square flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 mx-auto text-brand-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="font-display text-xl font-bold text-brand-500">{{ __('messages.hero_location') }}</p>
                        <p class="text-surface-700">Bogor, Indonesia</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-20 md:py-28 bg-gradient-to-b from-white to-surface-100 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.categories_badge') }}</span>
            <h2 class="font-display font-bold text-3xl md:text-5xl text-surface-900 mb-4">{{ __('messages.categories_title') }} <span class="text-brand-500">{{ __('messages.categories_title_highlight') }}</span></h2>
            <p class="text-surface-700 text-lg max-w-2xl mx-auto">{{ __('messages.categories_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
            $categoryColors = [
                '10k-challenge' => ['accent', 'from-accent-500 to-accent-600', 'accent-500', 'accent-100', 'text-accent-600', 'border-accent-200', 'bg-accent-50'],
                '21k-ultra-trail' => ['brand', 'from-brand-500 to-brand-600', 'brand-500', 'brand-100', 'text-brand-500', 'border-brand-200', 'bg-brand-50'],
                '5k-fun-run' => ['green', 'from-emerald-500 to-emerald-600', 'emerald-500', 'emerald-100', 'text-emerald-600', 'border-emerald-200', 'bg-emerald-50'],
                '5k-family-trail-run' => ['green', 'from-emerald-500 to-emerald-600', 'emerald-500', 'emerald-100', 'text-emerald-600', 'border-emerald-200', 'bg-emerald-50'],
                '10k' => ['accent', 'from-accent-500 to-accent-600', 'accent-500', 'accent-100', 'text-accent-600', 'border-accent-200', 'bg-accent-50'],
                '15k' => ['brand', 'from-brand-500 to-brand-600', 'brand-500', 'brand-100', 'text-brand-500', 'border-brand-200', 'bg-brand-50'],
            ];
            @endphp
            @foreach($categories as $cat)
            @php
            $colors = $categoryColors[$cat->slug] ?? $categoryColors['5k-fun-run'];
            $distKm = $cat->distance_km ?? substr($cat->slug, 0, strpos($cat->slug, 'k'));
            @endphp
            <div class="bg-white border {{ $colors[5] }} rounded-2xl p-8 hover:border-{{ $colors[2] }}/50 transition-all duration-300 hover:-translate-y-2 group relative overflow-hidden shadow-sm hover:shadow-xl">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $colors[1] }}"></div>
                <div class="w-16 h-16 {{ $colors[6] }} rounded-2xl flex items-center justify-center mb-6">
                    <span class="font-display text-xl font-bold {{ $colors[4] }}">{{ strtoupper(explode('-', $cat->slug)[0]) }}</span>
                </div>
                <h3 class="font-display text-xl font-bold text-surface-900 mb-3">{{ $cat->name }}</h3>
                <p class="text-surface-700 text-sm mb-6">{{ $cat->description }}</p>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm"><span class="text-surface-700">{{ __('messages.categories_distance') }}</span><span class="text-surface-900 font-medium">{{ $distKm }} km</span></div>
                    <div class="flex justify-between text-sm"><span class="text-surface-700">{{ __('messages.categories_elevation') }}</span><span class="text-surface-900 font-medium">{{ $cat->elevation ?? 294 }} m</span></div>
                    <div class="flex justify-between text-sm"><span class="text-surface-700">{{ __('messages.categories_cot') }}</span><span class="text-surface-900 font-medium">{{ $cat->cot ?? 0 }} {{ app()->getLocale() == 'id' ? 'Jam' : 'Hours' }}</span></div>
                    @if($cat->age_range)
                    <div class="flex justify-between text-sm"><span class="text-surface-700">{{ __('messages.categories_age_range') }}</span><span class="text-surface-900 font-medium">{{ $cat->age_range }}</span></div>
                    @endif
                    <div class="flex justify-between text-sm"><span class="text-surface-700">{{ __('messages.categories_price') }}</span><span class="text-surface-900 font-medium">Rp {{ number_format($cat->getCurrentPrice(), 0, ',', '.') }}</span></div>
                    @if($cat->isEarlyBird())
                    <div class="flex justify-between text-sm"><span class="{{ $colors[4] }}">{{ __('messages.categories_early_bird') }}</span><span class="{{ $colors[4] }} font-medium">Rp {{ number_format($cat->early_bird_price, 0, ',', '.') }}</span></div>
                    @endif
                </div>
                <!-- Entitlements -->
                <div class="mb-6 pt-4 border-t border-surface-300">
                    <p class="text-sm font-semibold text-surface-900 mb-3">{{ __('messages.categories_entitlements') }}:</p>
                    <ul class="text-sm text-surface-700 space-y-2">
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_jersey') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_medal') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_racepack') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_refreshment') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_cert') }}</li>
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('messages.categories_item_timing') }}</li>
                    </ul>
                </div>
                <a href="{{ route('register.create', ['category' => $cat->id]) }}" class="block w-full py-3 text-center bg-gradient-to-r {{ $colors[1] }} hover:opacity-90 text-white font-semibold rounded-xl transition-all duration-200 mt-auto shadow-md">
                    {{ __('messages.categories_register_for') }} {{ $cat->name }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Entertainment Section -->
<section id="entertainment" class="py-20 md:py-28 bg-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(239,28,36,0.05),transparent_60%)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.entertainment_badge') }}</span>
            <h2 class="font-display font-bold text-3xl md:text-5xl text-surface-900 mb-2">{{ __('messages.entertainment_title') }} <span class="text-brand-500">{{ __('messages.entertainment_title_highlight') }}</span></h2>
            <p class="text-surface-700 text-lg max-w-2xl mx-auto mt-4">{{ __('messages.entertainment_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $entertainments = [
                ['icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>', 'title' => __('messages.entertainment_dj'), 'desc' => __('messages.entertainment_dj_desc'), 'color' => 'brand'],
                ['icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 'title' => __('messages.entertainment_dance'), 'desc' => __('messages.entertainment_dance_desc'), 'color' => 'accent'],
                ['icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>', 'title' => __('messages.entertainment_band'), 'desc' => __('messages.entertainment_band_desc'), 'color' => 'emerald'],
                ['icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>', 'title' => __('messages.entertainment_doorprize'), 'desc' => __('messages.entertainment_doorprize_desc'), 'color' => 'amber'],
            ];
            @endphp
            @foreach($entertainments as $ent)
            <div class="bg-surface-50 border border-surface-200 rounded-2xl p-6 text-center hover:border-brand-300 hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-{{ $ent['color'] }}-50 border border-{{ $ent['color'] }}-200 rounded-2xl flex items-center justify-center mx-auto mb-4 text-{{ $ent['color'] }}-500 group-hover:scale-110 transition-transform duration-300">
                    {!! $ent['icon'] !!}
                </div>
                <h3 class="font-display font-bold text-lg text-surface-900 mb-2">{{ $ent['title'] }}</h3>
                <p class="text-surface-600 text-sm">{{ $ent['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Event Objectives Section -->
<section class="py-20 md:py-28 bg-gradient-to-b from-surface-50 to-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.objectives_badge') }}</span>
                <h2 class="font-display font-bold text-3xl md:text-5xl text-surface-900 mb-8 leading-tight">{{ __('messages.objectives_title') }} <span class="text-brand-500">{{ __('messages.objectives_title_highlight') }}</span></h2>
                <div class="space-y-5">
                    @php
                    $objectives = [
                        __('messages.objectives_1'),
                        __('messages.objectives_2'),
                        __('messages.objectives_3'),
                        __('messages.objectives_4'),
                        __('messages.objectives_5'),
                    ];
                    @endphp
                    @foreach($objectives as $i => $obj)
                    <div class="flex items-start gap-4 group">
                        <div class="w-10 h-10 bg-brand-50 border border-brand-200 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-brand-500 transition-colors duration-300">
                            <span class="font-display font-bold text-sm text-brand-500 group-hover:text-white transition-colors duration-300">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <p class="text-surface-700 text-lg leading-relaxed pt-1.5">{{ $obj }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-brand-500 to-brand-700 rounded-3xl p-10 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_20%,rgba(255,255,255,0.15),transparent_60%)]"></div>
                    <div class="relative z-10">
                        <p class="text-white/60 text-sm font-medium tracking-widest uppercase mb-4">Positioning</p>
                        <h3 class="font-display font-black text-3xl md:text-4xl mb-6 leading-tight">"Trail Run with<br>Smart Experience"</h3>
                        <div class="space-y-3 text-white/80">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-accent-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Outdoor Sports</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-accent-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Active Lifestyle</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-accent-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Technology Integration</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-accent-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Performance & Safety</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section id="partners" class="py-20 md:py-28 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.partners_badge') }}</span>
            <h2 class="font-display font-bold text-3xl md:text-5xl text-surface-900 mb-4">{{ __('messages.partners_title') }} <span class="text-brand-500">{{ __('messages.partners_title_highlight') }}</span></h2>
            <p class="text-surface-700 text-lg max-w-2xl mx-auto">{{ __('messages.partners_subtitle') }}</p>
        </div>

        <!-- Partner Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-2 mb-12" id="partner-tabs">
            <button class="partner-tab active px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-brand-500 text-white" data-filter="all">{{ __('messages.partners_all') }}</button>
            <button class="partner-tab px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-surface-100 text-surface-600 hover:bg-surface-200" data-filter="sports">{{ __('messages.partners_sports') }}</button>
            <button class="partner-tab px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-surface-100 text-surface-600 hover:bg-surface-200" data-filter="nutrition">{{ __('messages.partners_nutrition') }}</button>
            <button class="partner-tab px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-surface-100 text-surface-600 hover:bg-surface-200" data-filter="gadget">{{ __('messages.partners_gadget') }}</button>
            <button class="partner-tab px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-surface-100 text-surface-600 hover:bg-surface-200" data-filter="insurance">{{ __('messages.partners_insurance') }}</button>
            <button class="partner-tab px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-surface-100 text-surface-600 hover:bg-surface-200" data-filter="medical">{{ __('messages.partners_medical') }}</button>
            <button class="partner-tab px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-surface-100 text-surface-600 hover:bg-surface-200" data-filter="media">{{ __('messages.partners_media') }}</button>
            <button class="partner-tab px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-surface-100 text-surface-600 hover:bg-surface-200" data-filter="community">{{ __('messages.partners_community') }}</button>
        </div>

        <!-- Partner Grid -->
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4" id="partner-grid">
            @php
            $partners = [
                // Sports Partners
                ['name' => 'Salomon', 'cat' => 'sports'],
                ['name' => 'Ortus', 'cat' => 'sports'],
                ['name' => 'Duraking', 'cat' => 'sports'],
                ['name' => 'Mills', 'cat' => 'sports'],
                ['name' => 'Eiger', 'cat' => 'sports'],
                ['name' => 'JD.ID', 'cat' => 'sports'],
                ['name' => 'Antarestar', 'cat' => 'sports'],
                // Nutrition Partners
                ['name' => 'Fitbar', 'cat' => 'nutrition'],
                ['name' => 'Pocky', 'cat' => 'nutrition'],
                ['name' => 'EJ Sports', 'cat' => 'nutrition'],
                ['name' => 'Extra Joss', 'cat' => 'nutrition'],
                ['name' => 'Coca-Cola', 'cat' => 'nutrition'],
                ['name' => 'Hydro Coco', 'cat' => 'nutrition'],
                ['name' => 'Le Minerale', 'cat' => 'nutrition'],
                ['name' => 'Iso Plus', 'cat' => 'nutrition'],
                // Gadget Partners
                ['name' => 'Huawei', 'cat' => 'gadget'],
                ['name' => 'Garmin', 'cat' => 'gadget'],
                ['name' => 'Telkomsel', 'cat' => 'gadget'],
                ['name' => 'XL', 'cat' => 'gadget'],
                ['name' => 'IM3', 'cat' => 'gadget'],
                ['name' => 'Marshall', 'cat' => 'gadget'],
                // Insurance Partners
                ['name' => 'North Sky', 'cat' => 'insurance'],
                ['name' => 'Cussons', 'cat' => 'insurance'],
                ['name' => 'Pro Guard', 'cat' => 'insurance'],
                ['name' => 'Larilari.id', 'cat' => 'insurance'],
                ['name' => 'Chubb', 'cat' => 'insurance'],
                // Medical Partners
                ['name' => 'Siloam', 'cat' => 'medical'],
                ['name' => 'Brawijaya Hospital', 'cat' => 'medical'],
                ['name' => 'Apotek Wellings', 'cat' => 'medical'],
                // Media Partners
                ['name' => 'Prambors', 'cat' => 'media'],
                ['name' => 'Bogor Today', 'cat' => 'media'],
                ['name' => 'Nat Geo', 'cat' => 'media'],
                ['name' => 'Gofit', 'cat' => 'media'],
                ['name' => 'WeTV', 'cat' => 'media'],
                ['name' => 'Megaswara', 'cat' => 'media'],
                ['name' => 'Katadata', 'cat' => 'media'],
                // Community Partners
                ['name' => 'Funtrail Indonesia', 'cat' => 'community'],
            ];
            $catColors = [
                'sports' => 'brand', 'nutrition' => 'emerald', 'gadget' => 'blue',
                'insurance' => 'amber', 'medical' => 'rose', 'media' => 'violet', 'community' => 'teal',
            ];
            @endphp
            @foreach($partners as $partner)
            <div class="partner-item bg-surface-50 border border-surface-200 rounded-xl p-4 flex items-center justify-center aspect-square hover:border-brand-300 hover:shadow-md transition-all duration-300 group" data-category="{{ $partner['cat'] }}">
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto mb-2 bg-surface-200 rounded-lg flex items-center justify-center group-hover:bg-brand-100 transition-colors">
                        <span class="font-display font-bold text-xs text-surface-500 group-hover:text-brand-600 transition-colors">{{ strtoupper(substr($partner['name'], 0, 2)) }}</span>
                    </div>
                    <p class="text-xs text-surface-600 font-medium leading-tight">{{ $partner['name'] }}</p>
                </div>
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
        <a href="{{ route('register.create') }}" class="inline-block px-8 py-4 bg-white hover:bg-surface-100 text-brand-600 font-bold rounded-2xl shadow-xl shadow-black/10 hover:shadow-black/20 transition-all duration-300 transform hover:-translate-y-1 text-lg">
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

    // Partner filter tabs
    document.querySelectorAll('.partner-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            // Update tab styles
            document.querySelectorAll('.partner-tab').forEach(t => {
                t.classList.remove('bg-brand-500', 'text-white', 'active');
                t.classList.add('bg-surface-100', 'text-surface-600');
            });
            this.classList.remove('bg-surface-100', 'text-surface-600');
            this.classList.add('bg-brand-500', 'text-white', 'active');

            const filter = this.dataset.filter;
            document.querySelectorAll('.partner-item').forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = '';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    requestAnimationFrame(() => {
                        item.style.transition = 'opacity 0.3s, transform 0.3s';
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    });
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection
