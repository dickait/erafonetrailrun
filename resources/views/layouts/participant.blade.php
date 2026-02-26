<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Participant Dashboard') - Erafone Trail Run</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-900 text-white font-sans antialiased">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-dark-800/80 backdrop-blur-xl border-b border-forest-900/30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-forest-500 to-forest-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="font-display font-bold text-lg">My Dashboard</span>
            </a>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex gap-2">
                    <a href="{{ route('participant.dashboard') }}" class="px-3 py-1.5 text-sm rounded-lg {{ request()->routeIs('participant.dashboard') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400 hover:text-white' }}">Dashboard</a>
                    <a href="{{ route('participant.profile') }}" class="px-3 py-1.5 text-sm rounded-lg {{ request()->routeIs('participant.profile') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400 hover:text-white' }}">Profile</a>
                    <a href="{{ route('participant.payment') }}" class="px-3 py-1.5 text-sm rounded-lg {{ request()->routeIs('participant.payment') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400 hover:text-white' }}">Payment</a>
                    <a href="{{ route('participant.bib') }}" class="px-3 py-1.5 text-sm rounded-lg {{ request()->routeIs('participant.bib') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400 hover:text-white' }}">BIB</a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-gray-400 hover:text-red-400">Logout</button>
                </form>
            </div>
        </div>
        <!-- Mobile nav -->
        <div class="flex sm:hidden gap-1 px-4 pb-2 overflow-x-auto">
            <a href="{{ route('participant.dashboard') }}" class="px-3 py-1.5 text-xs rounded-lg whitespace-nowrap {{ request()->routeIs('participant.dashboard') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400' }}">Dashboard</a>
            <a href="{{ route('participant.profile') }}" class="px-3 py-1.5 text-xs rounded-lg whitespace-nowrap {{ request()->routeIs('participant.profile') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400' }}">Profile</a>
            <a href="{{ route('participant.payment') }}" class="px-3 py-1.5 text-xs rounded-lg whitespace-nowrap {{ request()->routeIs('participant.payment') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400' }}">Payment</a>
            <a href="{{ route('participant.bib') }}" class="px-3 py-1.5 text-xs rounded-lg whitespace-nowrap {{ request()->routeIs('participant.bib') ? 'bg-forest-900/40 text-forest-400' : 'text-gray-400' }}">BIB</a>
        </div>
    </nav>
    <main class="pt-24 sm:pt-20 pb-12 max-w-5xl mx-auto px-4 sm:px-6">
        @if(session('success'))
        <div class="mb-6 p-4 bg-forest-900/30 border border-forest-700/50 rounded-xl text-forest-300 text-sm">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
