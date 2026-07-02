@extends('layouts.public')
@section('title', __('messages.gallery_title') . ' - Era Trail Run 2026')
@section('content')
    <section class="pt-28 pb-24 bg-surface-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-sm font-semibold rounded-full mb-6 tracking-wide uppercase">
                    {{ __('messages.gallery_badge') }}
                </span>
                <h1 class="font-display font-black text-3xl md:text-5xl text-surface-900 mb-6 uppercase tracking-tight">
                    {{ __('messages.gallery_title') }}
                </h1>
                <div class="w-24 h-1.5 bg-brand-500 mx-auto mb-8 rounded-full"></div>
                <p class="text-surface-600 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                    {{ __('messages.gallery_subtitle') }}
                </p>
            </div>

            <!-- Google Photos Albums Links (Table: 3 Rows, 5 Columns) -->
            <div class="mb-16 max-w-5xl mx-auto">
                <div class="text-center mb-8">
                    <h3 class="font-display font-bold text-xl text-surface-900 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6 text-brand-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Google Photos Albums (Era Trail Run 2026)
                    </h3>
                </div>
                <div class="bg-white border border-surface-200 rounded-3xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse table-fixed min-w-[650px] md:min-w-0">
                            <tbody class="divide-y divide-surface-200">
                                @php
                                    $albumsChunk = [
                                        [
                                            ['name' => 'HIGHLIGHT', 'url' => 'https://photos.app.goo.gl/WdeAS6d829vTuK75A'],
                                            ['name' => 'START', 'url' => 'https://photos.app.goo.gl/kb1xAXjnr86YintF6'],
                                            ['name' => 'FINISH', 'url' => 'https://photos.app.goo.gl/e1j6CvNHsBwLa3Mr9'],
                                            ['name' => 'KM 1', 'url' => 'https://photos.app.goo.gl/DE6cPhwxCPPaZ4j57'],
                                            ['name' => 'KM 3', 'url' => 'https://photos.app.goo.gl/EhUTYh385FzoefcR9']
                                        ],
                                        [
                                            ['name' => 'KM 5', 'url' => 'https://photos.app.goo.gl/r6rMBUv5qKToXvTG9'],
                                            ['name' => 'BUKIT ILALANG', 'url' => 'https://photos.app.goo.gl/YqK7WVPLBWZ3zyTx5'],
                                            ['name' => 'GANG GEMPOL', 'url' => 'https://photos.app.goo.gl/DKqcW8n2urf9kCgj8'],
                                            ['name' => 'KM 8', 'url' => 'https://photos.app.goo.gl/NykJCCXsqKCmn9s47'],
                                            ['name' => 'KM 12', 'url' => 'https://photos.app.goo.gl/G1jFxGXr4RtzNUdC6']
                                        ],
                                        [
                                            ['name' => 'MULYAHARJA', 'url' => 'https://photos.app.goo.gl/8vgUytwUUmaiNeoH9'],
                                            ['name' => 'SUNGAI', 'url' => 'https://photos.app.goo.gl/y42WZWjLuc4EsD6w6'],
                                            ['name' => 'KM AKHIR', 'url' => 'https://photos.app.goo.gl/SzehSb1N67ZuNWmj6'],
                                            ['name' => 'VENUE', 'url' => 'https://photos.app.goo.gl/1foYFVtkkS79XhS98'],
                                            ['name' => 'TIM MOBILE', 'url' => 'https://photos.app.goo.gl/6JyEsKXEupWrou3W7']
                                        ]
                                    ];
                                @endphp
                                @foreach($albumsChunk as $row)
                                    <tr class="divide-x divide-surface-200">
                                        @foreach($row as $album)
                                            <td class="p-0">
                                                <a href="{{ $album['url'] }}" target="_blank" rel="noopener noreferrer" 
                                                   class="block px-3 py-6 hover:bg-brand-50/50 text-surface-800 hover:text-brand-600 transition-all duration-150 group">
                                                    <div class="font-display font-black text-sm tracking-wider uppercase mb-1">
                                                        {{ $album['name'] }}
                                                    </div>
                                                    <span class="text-[10px] text-surface-400 font-bold inline-flex items-center gap-0.5">
                                                        Buka Album
                                                        <svg class="w-2.5 h-2.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(count($photos) > 0)
                <div id="gallery-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                    @foreach($photos as $index => $photo)
                        <div class="gallery-item {{ $index >= 12 ? 'hidden' : '' }} aspect-square overflow-hidden rounded-2xl md:rounded-3xl cursor-pointer group relative shadow-md hover:shadow-xl transition-all duration-500"
                             onclick="openGalleryModal('{{ asset('storage/eratrailrun2026-photos/' . $photo) }}')">
                            <img src="{{ asset('storage/eratrailrun2026-photos/' . $photo) }}" 
                                 alt="Era Trail Run Gallery"
                                 loading="lazy"
                                 class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <div class="bg-white/20 p-4 rounded-full backdrop-blur-md border border-white/30 transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(count($photos) > 12)
                    <div class="mt-16 text-center">
                        <button id="load-more" onclick="loadMorePhotos()"
                                class="px-10 py-4 bg-white border-2 border-brand-500 text-brand-600 font-bold rounded-2xl hover:bg-brand-500 hover:text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg cursor-pointer">
                            {{ __('messages.gallery_load_more') }}
                        </button>
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-20">
                    <div class="bg-white border border-surface-200 rounded-3xl shadow-sm px-10 py-16 max-w-lg w-full text-center">
                        <div class="mx-auto w-24 h-24 bg-brand-50 rounded-full flex items-center justify-center mb-8">
                            <svg class="w-12 h-12 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="font-display font-bold text-2xl text-surface-900 mb-4">{{ __('messages.gallery_coming_soon') }}</h2>
                        <p class="text-surface-600 leading-relaxed">{{ __('messages.gallery_coming_soon_desc') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Gallery Modal (Lightbox) -->
    <div id="gallery-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="closeGalleryModal()"></div>
        <div class="relative h-full w-full flex items-center justify-center p-4 md:p-12 pointer-events-none">
            <button onclick="closeGalleryModal()" 
                    class="absolute top-8 right-8 text-white/50 hover:text-white transition-all z-[110] pointer-events-auto hover:rotate-90">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modal-image" src="" alt="Full Screen" 
                 class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0 pointer-events-auto shadow-white/10">
        </div>
    </div>

    @push('scripts')
        <script>
            let visibleCount = 12;
            const items = document.querySelectorAll('.gallery-item');
            const loadMoreBtn = document.getElementById('load-more');

            function loadMorePhotos() {
                let nextCount = visibleCount + 12;
                items.forEach((item, index) => {
                    if (index < nextCount) {
                        item.classList.remove('hidden');
                        setTimeout(() => {
                            item.style.opacity = '1';
                        }, 50);
                    }
                });
                visibleCount = nextCount;
                if (visibleCount >= items.length) {
                    if(loadMoreBtn) loadMoreBtn.classList.add('hidden');
                }
            }

            function openGalleryModal(imgSrc) {
                const modal = document.getElementById('gallery-modal');
                const img = document.getElementById('modal-image');
                img.src = imgSrc;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    img.classList.remove('scale-95', 'opacity-0');
                    img.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeGalleryModal() {
                const modal = document.getElementById('gallery-modal');
                const img = document.getElementById('modal-image');
                img.classList.remove('scale-100', 'opacity-100');
                img.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeGalleryModal();
            });
        </script>
    @endpush
@endsection