<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Erafone Trail Run 2026')</title>
    <meta name="description" content="@yield('meta_description', 'Join the ultimate trail running adventure - Erafone Trail Run 2026 in Sentul, Bogor')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-900 text-white font-sans antialiased">
    <!-- Navigation -->
    <nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-dark-900/80 backdrop-blur-xl border-b border-forest-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-forest-500 to-forest-700 rounded-xl flex items-center justify-center shadow-lg shadow-forest-500/20 group-hover:shadow-forest-500/40 transition-shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="font-display font-bold text-xl text-white">ERAFONE<span class="text-forest-400">TRAIL</span></span>
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-300 hover:text-forest-400 transition-colors">Home</a>
                    <a href="{{ route('home') }}#categories" class="text-sm font-medium text-gray-300 hover:text-forest-400 transition-colors">Categories</a>
                    <a href="{{ route('gallery') }}" class="text-sm font-medium text-gray-300 hover:text-forest-400 transition-colors">Gallery</a>
                    <a href="{{ route('results') }}" class="text-sm font-medium text-gray-300 hover:text-forest-400 transition-colors">Results</a>
                    <a href="{{ route('registration.status') }}" class="text-sm font-medium text-gray-300 hover:text-forest-400 transition-colors">Check Status</a>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-300 hover:text-forest-400 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-forest-400 transition-colors">Login</a>
                    @endauth
                    <a href="{{ route('register.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white text-sm font-semibold rounded-xl shadow-lg shadow-forest-500/25 hover:shadow-forest-500/40 transition-all duration-200 transform hover:-translate-y-0.5">
                        Register Now
                    </a>
                </div>
                <!-- Mobile menu button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-dark-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-forest-900/50 bg-dark-900/95 backdrop-blur-xl">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-dark-700 transition-colors">Home</a>
                <a href="{{ route('gallery') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-dark-700 transition-colors">Gallery</a>
                <a href="{{ route('results') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-dark-700 transition-colors">Results</a>
                <a href="{{ route('registration.status') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-dark-700 transition-colors">Check Status</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-dark-700 transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-dark-700 transition-colors">Login</a>
                @endauth
                <a href="{{ route('register.create') }}" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-forest-600 to-forest-500 text-center">Register Now</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark-800 border-t border-forest-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-forest-500 to-forest-700 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="font-display font-bold text-xl">ERAFONE<span class="text-forest-400">TRAIL</span></span>
                    </div>
                    <p class="text-gray-400 text-sm max-w-md">Experience the ultimate trail running adventure through the stunning landscapes of Indonesia. Challenge yourself, connect with nature.</p>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-sm uppercase tracking-wider text-forest-400 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('register.create') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Register</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Gallery</a></li>
                        <li><a href="{{ route('results') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Results</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-sm uppercase tracking-wider text-forest-400 mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="text-sm text-gray-400">info@erafonetrailrun.com</li>
                        <li class="text-sm text-gray-400">+62 812-3456-7890</li>
                        <li class="text-sm text-gray-400">Sentul, Bogor, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-forest-900/30 mt-8 pt-8 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Erafone Trail Run. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
