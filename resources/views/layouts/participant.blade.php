<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.part_dashboard') . ' - Erafone Trail Run')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-900 text-white font-sans antialiased">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-dark-800/95 backdrop-blur-xl border-b border-forest-900/30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-forest-500 to-forest-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="font-display font-bold text-sm">ERAFONE<span class="text-forest-400">TRAIL</span></span>
                </a>
                <div class="flex items-center gap-1 overflow-x-auto">
                    <a href="{{ route('participant.dashboard') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.dashboard') ? 'text-forest-400 bg-forest-900/30' : 'text-gray-400 hover:text-white' }} transition-colors whitespace-nowrap">{{ __('messages.part_dashboard') }}</a>
                    <a href="{{ route('participant.profile') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.profile') ? 'text-forest-400 bg-forest-900/30' : 'text-gray-400 hover:text-white' }} transition-colors whitespace-nowrap">{{ __('messages.part_profile') }}</a>
                    <a href="{{ route('participant.payment') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.payment') ? 'text-forest-400 bg-forest-900/30' : 'text-gray-400 hover:text-white' }} transition-colors whitespace-nowrap">{{ __('messages.part_payment') }}</a>
                    <a href="{{ route('participant.bib') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.bib') ? 'text-forest-400 bg-forest-900/30' : 'text-gray-400 hover:text-white' }} transition-colors whitespace-nowrap">{{ __('messages.part_bib') }}</a>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Language -->
                    <a href="{{ route('lang.switch', app()->getLocale() === 'id' ? 'en' : 'id') }}" class="px-2 py-1 text-xs text-gray-400 hover:text-white rounded hover:bg-dark-700">{{ app()->getLocale() === 'id' ? '🇬🇧 EN' : '🇮🇩 ID' }}</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="text-xs text-gray-400 hover:text-red-400 transition-colors">{{ __('messages.nav_logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <main class="pt-24 pb-12 px-4 sm:px-6 max-w-5xl mx-auto">
        @if(session('success'))
        <div class="mb-6 p-4 bg-forest-900/30 border border-forest-700/50 rounded-xl text-forest-300 text-sm">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
