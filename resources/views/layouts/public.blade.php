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
                    <!-- <img src="{{ asset('erafone-icon-00.webp') }}" class="h-10 md:h-12 w-auto" alt="ERA TRAIL RUN" /> -->
                    <img src="{{ asset('earfone-no-bg.webp') }}" class="h-10 md:h-12 w-auto" alt="ERA TRAIL RUN" />
                    <img src="{{ asset('eratrailrun-hitam.webp') }}" class="h-10 md:h-12 w-auto" alt="ERA TRAIL RUN" />
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_home') }}</a>
                    <a href="{{ route('home') }}#categories"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_categories') }}</a>
                    <!-- <a href="{{ route('race_course') }}"
                        class="text-sm font-medium text-surface-800 hover:text-brand-500 transition-colors">{{ __('messages.nav_race_course') }}</a> -->
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
                    @if(env('IS_OPEN', true))
                        <a href="{{ route('register.create') }}"
                            class="px-5 py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                            {{ __('messages.nav_register') }}
                        </a>
                    @else
                        <span
                            class="px-5 py-2.5 bg-surface-200 text-surface-500 text-sm font-semibold rounded-xl cursor-not-allowed">
                            {{ __('messages.nav_reg_closed') }}
                        </span>
                    @endif
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
                <!-- <a href="{{ route('race_course') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm font-medium text-surface-800 hover:text-brand-500 hover:bg-surface-100 transition-colors">{{ __('messages.nav_race_course') }}</a> -->
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
                @if(env('IS_OPEN', true))
                    <a href="{{ route('register.create') }}"
                        class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 text-center">{{ __('messages.nav_register') }}</a>
                @else
                    <span
                        class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-surface-500 bg-surface-100 text-center">{{ __('messages.nav_reg_closed') }}</span>
                @endif
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
                        <!-- <img src="{{ asset('erafone-icon-00.webp') }}" class="h-10 md:h-12 w-auto"
                            alt="ERA TRAIL RUN" /> -->
                        <img src="{{ asset('earfone-no-bg.webp') }}" class="h-10 md:h-12 w-auto" alt="ERA TRAIL RUN" />
                        <img src="{{ asset('eratrailrun-putih.webp') }}" class="h-10 md:h-12 w-auto"
                            alt="ERA TRAIL RUN" />
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
                        <li class="text-sm text-surface-400">
                            <a href="https://wa.me/628561310130?text={{ urlencode('Halo Panitia ERA Trail Run 2026, saya ingin bertanya tentang...') }}"
                                target="_blank"
                                class="hover:text-emerald-400 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                +628561310130
                            </a>
                        </li>
                        <li class="text-sm text-surface-400">
                            <a href="https://www.instagram.com/erafonestores_bogor/" target="_blank"
                                class="hover:text-pink-500 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.332 3.608 1.308.975.975 1.245 2.242 1.308 3.607.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.063 1.366-.333 2.633-1.308 3.608-.975.975-2.242 1.245-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.063-2.633-.333-3.608-1.308-.975-.975-1.245-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.332-2.633 1.308-3.608.975-.975 2.242-1.245 3.607-1.308 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-1.367.062-2.3.28-3.116.595-1.127.438-2.083.99-2.936 1.844-.853.852-1.405 1.808-1.843 2.936-.316.816-.534 1.749-.595 3.116-.058 1.28-.072 1.688-.072 4.947s.014 3.667.072 4.947c.061 1.367.279 2.3.595 3.116.438 1.127.99 2.083 1.843 2.936.852.853 1.808 1.405 2.936 1.843.816.316 1.749.534 3.116.595 1.28.058 1.688.072 4.947.072s3.667-.014 4.947-.072c1.367-.061 2.3-.279 3.116-.595 1.127-.438 2.083-.99 2.936-1.843.853-.852 1.405-1.808 1.843-2.936.316-.816.534-1.749.595-3.116.058-1.28.072-1.688.072-4.947s-.014-3.667-.072-4.947c-.062-1.367-.28-2.3-.595-3.116-.438-1.127-.99-2.083-1.843-2.936-.853-.853-1.808-1.405-2.936-1.843-.816-.316-1.749-.534-3.116-.595-1.28-.058-1.688-.072-4.947-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                                Erafone Store Bogor
                            </a>
                        </li>
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