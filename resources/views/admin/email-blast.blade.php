@extends('layouts.admin')
@section('page_title', __('messages.admin_email_blast'))
@section('content')
<div class="max-w-2xl">
    <div class="bg-dark-800 border border-forest-900/30 rounded-xl p-6">
        <p class="text-gray-400 text-sm mb-6">{{ __('messages.admin_email_to') }} <span class="text-forest-400 font-bold">{{ $paidCount }}</span> {{ __('messages.admin_paid_participants') }}</p>
        <form method="POST" action="{{ route('admin.email-blast.send') }}" onsubmit="return confirm('{{ __('messages.admin_send_confirm') }}')" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.admin_subject') }}</label>
                <input type="text" name="subject" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('messages.admin_message') }}</label>
                <textarea name="body" rows="8" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors"></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold rounded-xl transition-all">{{ __('messages.admin_send') }}</button>
        </form>
    </div>
</div>
@endsection
