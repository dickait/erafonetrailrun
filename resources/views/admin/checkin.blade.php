@extends('layouts.admin')
@section('page_title', 'QR Check-in')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-dark-800 rounded-2xl border border-forest-900/30 p-6 mb-6">
        <h3 class="font-display font-semibold text-lg text-white mb-4">Scan QR Code or Enter Participant ID</h3>
        <div class="flex gap-3">
            <input type="text" id="participant-id" placeholder="Enter or scan participant ID (UUID)" class="flex-1 px-4 py-3 bg-dark-700 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:border-forest-500 focus:ring-1 focus:ring-forest-500 font-mono text-sm" autofocus>
            <button id="checkin-btn" class="px-6 py-3 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-semibold rounded-xl transition-all">
                Check In
            </button>
        </div>
    </div>

    <div id="checkin-result" class="hidden">
        <!-- Will be populated by JS -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    const resultEl = document.getElementById('checkin-result');
    const inputEl = document.getElementById('participant-id');
    const btnEl = document.getElementById('checkin-btn');

    function doCheckin() {
        const id = inputEl.value.trim();
        if (!id) return;

        btnEl.disabled = true;
        btnEl.textContent = 'Processing...';

        fetch('{{ route("admin.checkin.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ participant_id: id })
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            resultEl.classList.remove('hidden');
            if (ok && data.success) {
                const p = data.participant;
                resultEl.innerHTML = `
                    <div class="bg-green-900/30 border border-green-700/50 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 mx-auto bg-green-900/50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="font-display font-bold text-xl text-green-400 mb-2">Check-in Successful!</h3>
                        <p class="text-white font-semibold text-lg mb-1">${p.full_name}</p>
                        <p class="text-gray-400 text-sm">${p.category?.name || ''} | BIB: ${p.bib_number || 'N/A'}</p>
                    </div>`;
            } else {
                resultEl.innerHTML = `
                    <div class="bg-red-900/30 border border-red-700/50 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 mx-auto bg-red-900/50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h3 class="font-display font-bold text-xl text-red-400 mb-2">Check-in Failed</h3>
                        <p class="text-gray-400">${data.message || 'Unknown error'}</p>
                    </div>`;
            }
            inputEl.value = '';
            inputEl.focus();
        })
        .catch(() => {
            resultEl.classList.remove('hidden');
            resultEl.innerHTML = '<div class="bg-red-900/30 border border-red-700/50 rounded-2xl p-6 text-center"><p class="text-red-400">Network error. Please try again.</p></div>';
        })
        .finally(() => {
            btnEl.disabled = false;
            btnEl.textContent = 'Check In';
        });
    }

    btnEl.addEventListener('click', doCheckin);
    inputEl.addEventListener('keypress', e => { if (e.key === 'Enter') doCheckin(); });
</script>
@endpush
