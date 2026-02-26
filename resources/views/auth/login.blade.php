@extends('layouts.public')
@section('title', __('messages.nav_login') . ' - Erafone Trail Run')
@section('content')
<section class="pt-28 pb-20 bg-surface-50 min-h-screen flex items-center">
    <div class="max-w-md mx-auto w-full px-4 sm:px-6">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-brand-500 to-brand-700 rounded-2xl flex items-center justify-center shadow-lg shadow-brand-500/20 mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h1 class="font-display font-bold text-2xl text-surface-900 mb-1">{{ __('messages.login_title') }}</h1>
            <p class="text-surface-700 text-sm">{{ __('messages.login_subtitle') }}</p>
        </div>
        @if(session('status'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="bg-white rounded-2xl border border-surface-300 p-6 space-y-5 shadow-sm">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.login_email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors" placeholder="your@email.com">
                @error('email')<p class="mt-1.5 text-sm text-brand-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-surface-800 mb-1.5">{{ __('messages.login_password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors" placeholder="••••••••">
                @error('password')<p class="mt-1.5 text-sm text-brand-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-surface-300 bg-surface-50 text-brand-500 focus:ring-brand-500 focus:ring-offset-0">
                    <span class="text-sm text-surface-700">{{ __('messages.login_remember') }}</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-brand-500 hover:text-brand-600 transition-colors">{{ __('messages.login_forgot') }}</a>
                @endif
            </div>
            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200">
                {{ __('messages.login_submit') }}
            </button>
        </form>
    </div>
</section>
@endsection
