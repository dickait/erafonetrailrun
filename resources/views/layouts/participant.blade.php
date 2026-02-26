<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.part_dashboard') . ' - Erafone Trail Run')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-100 text-surface-900 font-sans antialiased">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-surface-300 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('logo.png') }}" class="h-8 md:h-10 w-auto" alt="Erafone Trail Run" />
                </a>
                <div class="flex items-center gap-1 overflow-x-auto">
                    <a href="{{ route('participant.dashboard') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.dashboard') ? 'text-brand-500 bg-brand-50' : 'text-surface-700 hover:text-surface-900' }} transition-colors whitespace-nowrap">{{ __('messages.part_dashboard') }}</a>
                    <a href="{{ route('participant.profile') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.profile') ? 'text-brand-500 bg-brand-50' : 'text-surface-700 hover:text-surface-900' }} transition-colors whitespace-nowrap">{{ __('messages.part_profile') }}</a>
                    <a href="{{ route('participant.payment') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.payment') ? 'text-brand-500 bg-brand-50' : 'text-surface-700 hover:text-surface-900' }} transition-colors whitespace-nowrap">{{ __('messages.part_payment') }}</a>
                    <a href="{{ route('participant.bib') }}" class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium {{ request()->routeIs('participant.bib') ? 'text-brand-500 bg-brand-50' : 'text-surface-700 hover:text-surface-900' }} transition-colors whitespace-nowrap">{{ __('messages.part_bib') }}</a>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Language -->
                    <a href="{{ route('lang.switch', app()->getLocale() === 'id' ? 'en' : 'id') }}" class="px-2 py-1 text-xs text-surface-700 hover:text-surface-900 rounded hover:bg-surface-100">{{ app()->getLocale() === 'id' ? '🇬🇧 EN' : '🇮🇩 ID' }}</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="text-xs text-surface-500 hover:text-brand-500 transition-colors">{{ __('messages.nav_logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <main class="pt-24 pb-12 px-4 sm:px-6 max-w-5xl mx-auto">
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
