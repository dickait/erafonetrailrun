@extends('layouts.public')
@section('title', __('messages.racecourse_title_highlight') . ' - ERA Trail Run 2026')
@section('meta_description', 'Explore the race course for ERA Trail Run 2026 at Bogor Nirwana Residence. View route maps, elevation profiles, and race details.')
@section('content')
<!-- Hero -->
<section class="pt-28 pb-16 bg-gradient-to-br from-brand-900 via-brand-800 to-brand-950 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(239,28,36,0.2),transparent_60%)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block px-4 py-1.5 bg-white/10 border border-white/20 text-white text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">{{ __('messages.racecourse_badge') }}</span>
        <h1 class="font-display font-bold text-4xl md:text-6xl text-white mb-4 leading-tight">{{ __('messages.racecourse_title') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent-300 to-accent-400">{{ __('messages.racecourse_title_highlight') }}</span></h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto">{{ __('messages.racecourse_subtitle') }}</p>
    </div>
</section>

<!-- Category Tabs -->
<section class="py-12 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tab Buttons -->
        <div class="flex flex-wrap justify-center gap-3 mb-12" id="course-tabs">
            @foreach($categories as $i => $cat)
            <button class="course-tab px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ $i === 0 ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/25' : 'bg-surface-100 text-surface-600 hover:bg-surface-200' }}" data-tab="cat-{{ $cat->id }}">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <!-- Tab Content -->
        @foreach($categories as $i => $cat)
        <div id="cat-{{ $cat->id }}" class="course-content {{ $i !== 0 ? 'hidden' : '' }}">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Route Details Card -->
                <div class="lg:col-span-1">
                    <div class="bg-surface-50 border border-surface-200 rounded-2xl p-8 sticky top-24">
                        <h3 class="font-display font-bold text-2xl text-surface-900 mb-6">{{ __('messages.racecourse_details') }}</h3>
                        <div class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-surface-500">{{ __('messages.racecourse_distance') }}</p>
                                    <p class="font-display font-bold text-surface-900">{{ $cat->distance_km ?? '-' }} KM</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-accent-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-surface-500">{{ __('messages.racecourse_elevation') }}</p>
                                    <p class="font-display font-bold text-surface-900">{{ $cat->elevation ?? 294 }} m</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-surface-500">{{ __('messages.racecourse_cot') }}</p>
                                    <p class="font-display font-bold text-surface-900">{{ $cat->cot ?? '-' }} {{ app()->getLocale() == 'id' ? 'Jam' : 'Hours' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-surface-500">{{ __('messages.racecourse_terrain') }}</p>
                                    <p class="font-display font-bold text-surface-900">{{ __('messages.racecourse_terrain_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-surface-500">{{ __('messages.racecourse_start') }}</p>
                                    <p class="font-display font-bold text-surface-900">{{ __('messages.racecourse_start_desc') }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Download GPX -->
                        <div class="mt-8 pt-6 border-t border-surface-200">
                            <button disabled class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-surface-200 text-surface-500 font-semibold rounded-xl cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ __('messages.racecourse_download_gpx') }}
                            </button>
                            <p class="text-xs text-surface-400 text-center mt-2">{{ __('messages.racecourse_gpx_coming') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Map & Elevation -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Route Map -->
                    <div class="bg-surface-50 border border-surface-200 rounded-2xl overflow-hidden">
                        <div class="p-6 border-b border-surface-200">
                            <h3 class="font-display font-bold text-xl text-surface-900">{{ __('messages.racecourse_map_title') }} — {{ $cat->name }}</h3>
                        </div>
                        <div class="aspect-video bg-gradient-to-br from-surface-100 to-surface-200 flex items-center justify-center relative">
                            <div class="text-center">
                                <svg class="w-20 h-20 mx-auto text-surface-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                <p class="text-surface-400 font-medium">{{ __('messages.racecourse_map_title') }}</p>
                                <p class="text-surface-300 text-sm">{{ $cat->name }} — {{ $cat->distance_km ?? '-' }} KM</p>
                            </div>
                            <!-- Route path overlay -->
                            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 600 338" fill="none" preserveAspectRatio="none">
                                <path d="M50,280 Q100,260 150,250 T250,200 T350,150 T450,180 T550,120" stroke="rgba(239,28,36,0.3)" stroke-width="3" fill="none" stroke-dasharray="10,5">
                                    <animate attributeName="stroke-dashoffset" values="0;-15" dur="1s" repeatCount="indefinite"/>
                                </path>
                                <circle cx="50" cy="280" r="6" fill="#EF1C24" opacity="0.8"/>
                                <circle cx="550" cy="120" r="6" fill="#22C55E" opacity="0.8"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Elevation Profile -->
                    <div class="bg-surface-50 border border-surface-200 rounded-2xl overflow-hidden">
                        <div class="p-6 border-b border-surface-200">
                            <h3 class="font-display font-bold text-xl text-surface-900">{{ __('messages.racecourse_elevation_title') }} — {{ $cat->name }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="aspect-[3/1] bg-gradient-to-t from-brand-50 to-transparent rounded-xl relative overflow-hidden border border-surface-200">
                                <!-- Elevation Chart Visualization -->
                                <svg class="w-full h-full" viewBox="0 0 600 200" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="elevGrad-{{ $cat->id }}" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#EF1C24" stop-opacity="0.3"/>
                                            <stop offset="100%" stop-color="#EF1C24" stop-opacity="0.05"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M0,180 Q50,170 100,150 T200,100 T300,60 T400,80 T500,40 L600,50 L600,200 L0,200 Z" fill="url(#elevGrad-{{ $cat->id }})"/>
                                    <path d="M0,180 Q50,170 100,150 T200,100 T300,60 T400,80 T500,40 L600,50" stroke="#EF1C24" stroke-width="2.5" fill="none"/>
                                    <!-- Grid lines -->
                                    <line x1="0" y1="50" x2="600" y2="50" stroke="#e5e7eb" stroke-width="0.5" stroke-dasharray="4,4"/>
                                    <line x1="0" y1="100" x2="600" y2="100" stroke="#e5e7eb" stroke-width="0.5" stroke-dasharray="4,4"/>
                                    <line x1="0" y1="150" x2="600" y2="150" stroke="#e5e7eb" stroke-width="0.5" stroke-dasharray="4,4"/>
                                </svg>
                                <div class="absolute bottom-2 left-4 text-xs text-surface-400">0 km</div>
                                <div class="absolute bottom-2 right-4 text-xs text-surface-400">{{ $cat->distance_km ?? '-' }} km</div>
                                <div class="absolute top-2 left-4 text-xs text-surface-400">{{ $cat->elevation ?? 294 }}m ↑</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Preparation Tips -->
<section class="py-16 md:py-20 bg-surface-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="font-display font-bold text-2xl md:text-3xl text-surface-900 mb-8 text-center">{{ __('messages.racecourse_tips_title') }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @php
            $tips = [
                ['icon' => '👟', 'text' => __('messages.racecourse_tip_1')],
                ['icon' => '💧', 'text' => __('messages.racecourse_tip_2')],
                ['icon' => '🏃', 'text' => __('messages.racecourse_tip_3')],
                ['icon' => '🌤️', 'text' => __('messages.racecourse_tip_4')],
            ];
            @endphp
            @foreach($tips as $tip)
            <div class="bg-white border border-surface-200 rounded-xl p-5 flex items-start gap-4 hover:border-brand-300 transition-colors">
                <span class="text-2xl">{{ $tip['icon'] }}</span>
                <p class="text-surface-700">{{ $tip['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-gradient-to-r from-brand-900 via-brand-800 to-brand-900 relative overflow-hidden">
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
    // Course tabs
    document.querySelectorAll('.course-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            // Update tab styles
            document.querySelectorAll('.course-tab').forEach(t => {
                t.classList.remove('bg-brand-500', 'text-white', 'shadow-lg', 'shadow-brand-500/25');
                t.classList.add('bg-surface-100', 'text-surface-600');
            });
            this.classList.remove('bg-surface-100', 'text-surface-600');
            this.classList.add('bg-brand-500', 'text-white', 'shadow-lg', 'shadow-brand-500/25');

            // Show/hide content
            const targetId = this.dataset.tab;
            document.querySelectorAll('.course-content').forEach(c => c.classList.add('hidden'));
            document.getElementById(targetId)?.classList.remove('hidden');
        });
    });
</script>
@endpush
@endsection
