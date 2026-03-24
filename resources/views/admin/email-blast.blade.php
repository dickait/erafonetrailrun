@extends('layouts.admin')
@section('page_title', __('messages.admin_email_blast'))
@section('content')
<div class="max-w-6xl">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Card for Bulk Email -->
        <div class="bg-white border border-surface-300 rounded-2xl p-6 shadow-sm overflow-hidden relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">{{ __('messages.admin_email_bulk') }}</h3>
            <p class="text-surface-600 text-sm mb-6">{{ __('messages.admin_email_bulk_desc', ['count' => $paidCount]) }}</p>
            
            <form method="POST" action="{{ route('admin.email-blast.send') }}" onsubmit="return confirm('{{ __('messages.admin_send_confirm') }}')" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-surface-700 mb-1.5">{{ __('messages.admin_subject') }}</label>
                    <input type="text" name="subject" required placeholder="Subject for all participants" class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 mb-1.5">{{ __('messages.admin_message') }}</label>
                    <textarea name="body" rows="8" required placeholder="Write your message here..." class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold rounded-xl shadow-lg shadow-brand-500/20 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    {{ __('messages.admin_send') }} Bulk
                </button>
            </form>
        </div>

        <!-- Card for Single Email -->
        <div class="bg-white border border-surface-300 rounded-2xl p-6 shadow-sm overflow-hidden relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">{{ __('messages.admin_email_single') }}</h3>
            <p class="text-surface-600 text-sm mb-6">{{ __('messages.admin_email_single_desc') }}</p>
            
            <form method="POST" action="{{ route('admin.email-single.send') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-surface-700 mb-1.5">{{ __('messages.admin_email_to') }}</label>
                    <input type="email" name="email" required placeholder="example@email.com" value="{{ request('email') }}" class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 mb-1.5">{{ __('messages.admin_subject') }}</label>
                    <input type="text" name="subject" required placeholder="Subject for this recipient" class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 mb-1.5">{{ __('messages.admin_message') }}</label>
                    <textarea name="body" rows="5" required placeholder="Write your message here..." class="w-full px-4 py-3 bg-surface-50 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-white border-2 border-brand-500 text-brand-600 font-bold rounded-xl hover:bg-brand-50 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    {{ __('messages.admin_send') }} Single
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
