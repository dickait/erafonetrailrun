@extends('layouts.admin')
@section('page_title', __('messages.admin_qr_checkin'))
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-dark-800 border border-forest-900/30 rounded-xl p-6 mb-6">
        <h3 class="font-display font-semibold text-white mb-4">{{ __('messages.admin_scan_title') }}</h3>
        <div class="flex gap-3">
            <input type="text" id="participant-id" placeholder="{{ __('messages.admin_scan_placeholder') }}" class="flex-1 px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 transition-colors font-mono text-sm">
            <button onclick="doCheckin()" class="px-6 py-3 bg-gradient-to-r from-forest-600 to-forest-500 text-white font-semibold rounded-xl hover:from-forest-500 hover:to-forest-400 transition-all">{{ __('messages.admin_checkin_btn') }}</button>
        </div>
    </div>
    <div id="checkin-result" class="hidden"></div>
</div>

@push('scripts')
<script>
async function doCheckin() {
    const id = document.getElementById('participant-id').value.trim();
    if (!id) return;
    const result = document.getElementById('checkin-result');
    result.classList.remove('hidden');
    try {
        const res = await fetch('{{ route("admin.checkin.process") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ participant_id: id })
        });
        const data = await res.json();
        if (data.success) {
            result.innerHTML = `
                <div class="bg-forest-900/30 border border-forest-700/50 rounded-xl p-6 text-center animate-pulse">
                    <svg class="w-16 h-16 mx-auto text-forest-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-display font-bold text-xl text-forest-400 mb-2">{{ __('messages.admin_checkin_success') }}</h3>
                    <p class="text-white font-medium text-lg">${data.participant.full_name}</p>
                    <p class="text-gray-400 text-sm">BIB: ${data.participant.bib_number || '-'} • ${data.participant.category?.name || ''}</p>
                </div>`;
        } else {
            result.innerHTML = `
                <div class="bg-red-900/30 border border-red-700/50 rounded-xl p-6 text-center">
                    <svg class="w-16 h-16 mx-auto text-red-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-display font-bold text-xl text-red-400 mb-2">{{ __('messages.admin_checkin_failed') }}</h3>
                    <p class="text-gray-400">${data.message}</p>
                </div>`;
        }
    } catch (e) {
        result.innerHTML = `<div class="bg-red-900/30 border border-red-700/50 rounded-xl p-6 text-center"><p class="text-red-400">Error: ${e.message}</p></div>`;
    }
    document.getElementById('participant-id').value = '';
    document.getElementById('participant-id').focus();
}
document.getElementById('participant-id')?.addEventListener('keypress', e => { if (e.key === 'Enter') doCheckin(); });
</script>
@endpush
@endsection
