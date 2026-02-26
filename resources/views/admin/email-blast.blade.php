@extends('layouts.admin')
@section('page_title', 'Email Blast')
@section('content')
<div class="max-w-2xl">
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6">
        <div class="mb-6">
            <p class="text-sm text-gray-400">Send email to all <span class="text-forest-400 font-semibold">{{ $paidCount }}</span> paid participants</p>
        </div>
        <form action="{{ route('admin.email-blast.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Subject *</label>
                <input type="text" name="subject" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500" placeholder="Email subject">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Message *</label>
                <textarea name="body" rows="8" required class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 resize-none" placeholder="Write your message here..."></textarea>
            </div>
            <button type="submit" onclick="return confirm('Send email to all paid participants?')" class="px-6 py-3 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-semibold rounded-xl shadow-lg transition-all">
                Send Email Blast
            </button>
        </form>
    </div>
</div>
@endsection
