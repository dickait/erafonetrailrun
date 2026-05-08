<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.admin_panel') . ' - Era Trail Run')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface-100 text-surface-900 font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-56 bg-surface-900 border-r border-surface-800 transform -translate-x-full md:translate-x-0 transition-transform duration-200">
            <div class="p-4 border-b border-surface-800">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-brand-500 to-brand-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-display font-bold text-sm text-white">{{ __('messages.admin_panel') }}</span>
                </div>
            </div>
            <nav class="p-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-brand-400 bg-brand-900/30' : 'text-surface-400 hover:text-white hover:bg-surface-800' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('messages.admin_dashboard') }}
                </a>
                <a href="{{ route('admin.participants') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.participants') ? 'text-brand-400 bg-brand-900/30' : 'text-surface-400 hover:text-white hover:bg-surface-800' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __('messages.admin_participants') }}
                </a>
                <a href="{{ route('admin.payments') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.payments') ? 'text-brand-400 bg-brand-900/30' : 'text-surface-400 hover:text-white hover:bg-surface-800' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    {{ __('messages.admin_payments') }}
                </a>
                <a href="{{ route('admin.email-blast') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.email-blast') ? 'text-brand-400 bg-brand-900/30' : 'text-surface-400 hover:text-white hover:bg-surface-800' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ __('messages.admin_email_blast') }}
                </a>
                <a href="{{ route('admin.promotions.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.promotions.*') ? 'text-brand-400 bg-brand-900/30' : 'text-surface-400 hover:text-white hover:bg-surface-800' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Promotions
                </a>
                <a href="{{ route('admin.race-results.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.race-results.*') ? 'text-brand-400 bg-brand-900/30' : 'text-surface-400 hover:text-white hover:bg-surface-800' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('messages.admin_race_results') }}
                </a>
                <a href="{{ route('admin.checkin') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.checkin') ? 'text-brand-400 bg-brand-900/30' : 'text-surface-400 hover:text-white hover:bg-surface-800' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    {{ __('messages.admin_qr_checkin') }}
                </a>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-surface-800 space-y-1">
                <!-- Language -->
                <div class="flex gap-1 px-3 py-2">
                    <a href="{{ route('lang.switch', 'id') }}"
                        class="px-2 py-1 text-xs rounded {{ app()->getLocale() === 'id' ? 'bg-brand-900/40 text-brand-400' : 'text-surface-500 hover:text-white' }}">🇮🇩
                        ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="px-2 py-1 text-xs rounded {{ app()->getLocale() === 'en' ? 'bg-brand-900/40 text-brand-400' : 'text-surface-500 hover:text-white' }}">🇬🇧
                        EN</a>
                </div>
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-surface-400 hover:text-white hover:bg-surface-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    {{ __('messages.nav_view_site') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-surface-400 hover:text-brand-400 hover:bg-surface-800 transition-colors w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('messages.nav_logout') }}
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 md:ml-56">
            <!-- Mobile header -->
            <header
                class="md:hidden sticky top-0 z-40 bg-white/95 backdrop-blur-xl border-b border-surface-300 px-4 py-3 flex justify-between items-center">
                <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')"
                    class="p-2 rounded-lg hover:bg-surface-100">
                    <svg class="w-6 h-6 text-surface-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span class="font-display font-bold text-sm text-surface-900">{{ __('messages.admin_panel') }}</span>
                <span class="text-sm text-surface-500">{{ auth()->user()->name ?? 'Admin' }}</span>
            </header>
            <div class="hidden md:flex justify-between items-center px-8 py-4 border-b border-surface-300 bg-white">
                <h1 class="font-display font-bold text-xl text-surface-900">
                    @yield('page_title', __('messages.admin_dashboard'))</h1>
                <span class="text-sm text-surface-500">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
            <main class="p-4 md:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">
                        {{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"
        onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden');">
    </div>
    @stack('modals')
    @stack('scripts')
</body>

</html>