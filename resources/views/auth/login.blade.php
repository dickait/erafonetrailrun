@extends('layouts.public')
@section('title', __('messages.nav_login') . ' - Erafone Trail Run')
@section('content')
<section class="pt-28 pb-20 bg-dark-900 min-h-screen flex items-center">
    <div class="max-w-md mx-auto w-full px-4 sm:px-6">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-forest-500 to-forest-700 rounded-2xl flex items-center justify-center shadow-lg shadow-forest-500/20 mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h1 class="font-display font-bold text-2xl text-white mb-1">{{ __('messages.login_title') }}</h1>
            <p class="text-gray-400 text-sm">{{ __('messages.login_subtitle') }}</p>
        </div>
        @if(session('status'))
        <div class="mb-4 p-3 bg-forest-900/30 border border-forest-700/50 rounded-xl text-forest-300 text-sm">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.login_email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="your@email.com">
                @error('email')<p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.login_password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors" placeholder="••••••••">
                @error('password')<p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-dark-600 bg-dark-700 text-forest-500 focus:ring-forest-500 focus:ring-offset-0">
                    <span class="text-sm text-gray-400">{{ __('messages.login_remember') }}</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-forest-400 hover:text-forest-300 transition-colors">{{ __('messages.login_forgot') }}</a>
                @endif
            </div>
            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold rounded-xl shadow-lg shadow-forest-500/25 hover:shadow-forest-500/40 transition-all duration-200">
                {{ __('messages.login_submit') }}
            </button>
        </form>
    </div>
</section>
@endsection
