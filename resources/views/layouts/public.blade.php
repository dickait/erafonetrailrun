<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ERA TRAIL RUN 2026')</title>
    <meta name="description"
        content="@yield('meta_description', 'Join the ultimate trail running adventure - ERA TRAIL RUN 2026 in Bogor Nirwana Residence, Bogor')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-white text-surface-900 font-sans antialiased">
    <!-- Navigation -->
    <nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-surface-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('eratrailrun.webp') }}" class="h-10 md:h-12 w-auto" alt="ERA TRAIL RUN" />
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_home') }}</a>
                    <a href="{{ route('home') }}#categories"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_categories') }}</a>
                    <a href="{{ route('race_course') }}"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_race_course') }}</a>
                    <a href="{{ route('gallery') }}"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_gallery') }}</a>
                    <a href="{{ route('results') }}"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_results') }}</a>
                    <a href="{{ route('registration.status') }}"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_check_status') }}</a>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <!-- Language Switcher -->
                    <div class="relative group">
                        <button
                            class="flex items-center gap-1.5 text-sm font-medium text-surface-800 hover:text-surface-900 transition-colors px-2 py-1 rounded-lg hover:bg-surface-100">
                            {{ app()->getLocale() === 'id' ? '🇮🇩' : '🇬🇧' }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            class="absolute right-0 top-full mt-1 w-36 bg-white border border-surface-300 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 overflow-hidden z-50">
                            <a href="{{ route('lang.switch', 'id') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm {{ app()->getLocale() === 'id' ? 'text-brand-500 bg-brand-50' : 'text-surface-600 hover:bg-surface-100' }} transition-colors">
                                🇮🇩 Indonesia
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm {{ app()->getLocale() === 'en' ? 'text-brand-500 bg-brand-50' : 'text-surface-600 hover:bg-surface-100' }} transition-colors">
                                🇬🇧 English
                            </a>
                        </div>
                    </div>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_dashboard') }}</a>
                    @endauth
                    <a href="{{ route('register.create') }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                        {{ __('messages.nav_register') }}
                    </a>
                </div>
                <!-- Mobile menu button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-surface-100 transition-colors">
                    <svg class="w-6 h-6 text-surface-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-surface-300 bg-white/95 backdrop-blur-xl">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('home') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm font-medium text-surface-800 hover:text-brand-500 hover:bg-surface-100 transition-colors">{{ __('messages.nav_home') }}</a>
                <a href="{{ route('race_course') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm font-medium text-surface-800 hover:text-brand-500 hover:bg-surface-100 transition-colors">{{ __('messages.nav_race_course') }}</a>
                <a href="{{ route('gallery') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm font-medium text-surface-800 hover:text-brand-500 hover:bg-surface-100 transition-colors">{{ __('messages.nav_gallery') }}</a>
                <a href="{{ route('results') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm font-medium text-surface-800 hover:text-brand-500 hover:bg-surface-100 transition-colors">{{ __('messages.nav_results') }}</a>
                <a href="{{ route('registration.status') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm font-medium text-surface-800 hover:text-brand-500 hover:bg-surface-100 transition-colors">{{ __('messages.nav_check_status') }}</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block px-4 py-2.5 rounded-lg text-sm font-medium text-surface-800 hover:text-brand-500 hover:bg-surface-100 transition-colors">{{ __('messages.nav_dashboard') }}</a>
                @endauth
                <!-- Mobile Language Switcher -->
                <div class="flex gap-2 px-4 py-2">
                    <a href="{{ route('lang.switch', 'id') }}"
                        class="px-3 py-1.5 text-xs rounded-lg {{ app()->getLocale() === 'id' ? 'bg-brand-50 text-brand-500' : 'text-surface-500 hover:bg-surface-100' }}">🇮🇩
                        ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="px-3 py-1.5 text-xs rounded-lg {{ app()->getLocale() === 'en' ? 'bg-brand-50 text-brand-500' : 'text-surface-500 hover:bg-surface-100' }}">🇬🇧
                        EN</a>
                </div>
                <a href="{{ route('register.create') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 text-center">{{ __('messages.nav_register') }}</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-900 border-t border-surface-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('eratrailrun.webp') }}" class="h-10 md:h-12 w-auto" alt="ERA TRAIL RUN" />
                    </div>
                    <p class="text-surface-400 text-sm max-w-md">{{ __('messages.footer_description') }}</p>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-sm uppercase tracking-wider text-accent-400 mb-4">
                        {{ __('messages.footer_quick_links') }}
                    </h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}"
                                class="text-sm text-surface-400 hover:text-white transition-colors">{{ __('messages.nav_home') }}</a>
                        </li>
                        <li><a href="{{ route('register.create') }}"
                                class="text-sm text-surface-400 hover:text-white transition-colors">{{ __('messages.footer_register') }}</a>
                        </li>
                        <li><a href="{{ route('gallery') }}"
                                class="text-sm text-surface-400 hover:text-white transition-colors">{{ __('messages.nav_gallery') }}</a>
                        </li>
                        <li><a href="{{ route('results') }}"
                                class="text-sm text-surface-400 hover:text-white transition-colors">{{ __('messages.nav_results') }}</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-sm uppercase tracking-wider text-accent-400 mb-4">
                        {{ __('messages.footer_contact') }}
                    </h4>
                    <ul class="space-y-2">
                        <li class="text-sm text-surface-400">contact@eratrailrun.id</li>
                        <li class="text-sm text-surface-400">+62 812-3456-7890</li>
                        <li class="text-sm text-surface-400">Bogor Nirwana Residence, Bogor, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-surface-800 mt-8 pt-8 text-center">
                <p class="text-sm text-surface-500">&copy; {{ date('Y') }} ERA TRAIL RUN.
                    {{ __('messages.footer_rights') }}
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>

</html>