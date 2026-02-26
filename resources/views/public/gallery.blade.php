@extends('layouts.public')
@section('title', 'Gallery - Erafone Trail Run 2026')
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 text-xs font-semibold text-forest-400 uppercase tracking-wider bg-forest-900/40 rounded-full mb-4">Gallery</span>
            <h1 class="font-display font-bold text-3xl sm:text-4xl text-white mb-2">Event Gallery</h1>
            <p class="text-gray-400">Relive the moments from our trail running events</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @for($i = 1; $i <= 8; $i++)
            <div class="group relative aspect-square bg-dark-800 rounded-2xl overflow-hidden border border-forest-900/30 hover:border-forest-700/50 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-forest-900/30 to-dark-800 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto text-forest-700/50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-gray-600 text-xs">Trail Run {{ $i }}</p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-dark-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                    <span class="text-white text-sm font-medium">Trail Running Moment #{{ $i }}</span>
                </div>
            </div>
            @endfor
        </div>
        <div class="mt-12 text-center">
            <p class="text-gray-500 text-sm">More photos will be added after the event. Stay tuned!</p>
        </div>
    </div>
</section>
@endsection
