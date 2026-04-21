@extends('layouts.admin')
@section('page_title', __('messages.admin_email_blast'))
@section('content')
<div class="w-full">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 xl:gap-6 items-stretch">
        <!-- Column 1: Bulk Email -->
        <div class="flex flex-col h-full mt-0">
            <div class="bg-white border border-surface-300 rounded-2xl p-5 shadow-sm overflow-hidden relative flex-1">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-16 h-16 text-brand-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-surface-900 mb-1">{{ __('messages.admin_email_bulk') }}</h3>
                <p class="text-surface-500 text-[11px] mb-4">{{ __('messages.admin_email_bulk_desc', ['count' => $paidCount]) }}</p>
                
                <form id="bulkForm" method="POST" action="{{ route('admin.email-blast.send') }}" onsubmit="return confirm('{{ __('messages.admin_send_confirm') }}')" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">Template</label>
                        <select name="template" class="template-selector w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-xs focus:border-brand-500 transition-colors cursor-pointer">
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl }}" {{ $tpl == 'EventBlast' ? 'selected' : '' }}>{{ $tpl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">{{ __('messages.admin_subject') }}</label>
                        <input type="text" name="subject" required placeholder="Subject..." class="subject-input w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-xs text-surface-900 focus:border-brand-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">{{ __('messages.admin_message') }}</label>
                        <textarea name="body" rows="6" required placeholder="Message..." class="body-input w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-xs text-surface-900 focus:border-brand-500 transition-colors"></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="updatePreview('bulk')" class="px-3 py-2.5 bg-surface-100 hover:bg-surface-200 text-surface-700 font-bold rounded-xl transition-all text-[10px] uppercase">
                            Preview
                        </button>
                        <button type="submit" class="flex-1 py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/20 transition-all text-[10px] uppercase">
                            {{ __('messages.admin_send') }} Bulk
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Column 2: Single Email -->
        <div class="flex flex-col h-full mt-0">
            <div class="bg-white border border-surface-300 rounded-2xl p-5 shadow-sm overflow-hidden relative flex-1">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-16 h-16 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-surface-900 mb-1">{{ __('messages.admin_email_single') }}</h3>
                <p class="text-surface-500 text-[11px] mb-4">{{ __('messages.admin_email_single_desc') }}</p>
                
                <form id="singleForm" method="POST" action="{{ route('admin.email-single.send') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">{{ __('messages.admin_email_to') }}</label>
                        <input type="email" name="email" required placeholder="example@email.com" value="{{ request('email') }}" class="w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-xs text-surface-900 focus:border-brand-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">Template</label>
                        <select name="template" class="template-selector w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-xs focus:border-brand-500 transition-colors cursor-pointer">
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl }}" {{ $tpl == 'EventBlast' ? 'selected' : '' }}>{{ $tpl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">{{ __('messages.admin_subject') }}</label>
                        <input type="text" name="subject" required placeholder="Subject..." class="subject-input w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-xs text-surface-900 focus:border-brand-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">{{ __('messages.admin_message') }}</label>
                        <textarea name="body" rows="4" required placeholder="Message..." class="body-input w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-xs text-surface-900 focus:border-brand-500 transition-colors"></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="updatePreview('single')" class="px-3 py-2.5 bg-white border border-surface-300 text-surface-700 font-bold rounded-xl hover:bg-surface-50 transition-all text-[10px] uppercase">
                            Preview
                        </button>
                        <button type="submit" class="flex-1 py-2.5 bg-white border-2 border-brand-500 text-brand-600 font-bold rounded-xl hover:bg-brand-50 transition-all text-[10px] uppercase">
                            {{ __('messages.admin_send') }} Single
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Column 3: Preview -->
        <div class="flex flex-col h-full mt-0">
            <div class="bg-surface-900 rounded-2xl p-1 shadow-2xl flex flex-col h-full min-h-[500px] border-4 border-surface-800 flex-1">
                <div class="p-4 flex items-center justify-between border-b border-surface-800">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        </div>
                        <span class="text-[9px] text-surface-400 font-mono ml-1">preview.html</span>
                    </div>
                    <span id="previewLabel" class="text-[8px] bg-brand-500/20 text-brand-400 px-1.5 py-0.5 rounded font-bold uppercase tracking-tighter">Idle</span>
                </div>
                <div class="flex-1 bg-white rounded-b-xl overflow-hidden relative">
                    <div id="loadingOverlay" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10 transition-all">
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                            <span class="mt-2 text-[9px] font-bold text-surface-700 animate-pulse uppercase tracking-widest">Syncing</span>
                        </div>
                    </div>
                    <iframe id="previewFrame" src="about:blank" class="w-full h-full border-none"></iframe>
                    <div id="idleState" class="absolute inset-0 flex items-center justify-center p-6 text-center">
                        <div class="max-w-xs space-y-3">
                            <div class="w-10 h-10 bg-surface-100 rounded-full flex items-center justify-center mx-auto text-surface-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            <p class="text-surface-500 text-[10px] italic">Klik "Preview" untuk simulasi email.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updatePreview(type) {
        const formId = type === 'bulk' ? 'bulkForm' : 'singleForm';
        const form = document.getElementById(formId);
        const template = form.querySelector('.template-selector').value;
        const subject = form.querySelector('.subject-input').value;
        const body = form.querySelector('.body-input').value;
        
        const previewFrame = document.getElementById('previewFrame');
        const overlay = document.getElementById('loadingOverlay');
        const idle = document.getElementById('idleState');
        const label = document.getElementById('previewLabel');

        // UI Updates
        overlay.classList.remove('hidden');
        idle.classList.add('hidden');
        label.innerText = 'Syncing';
        label.classList.replace('bg-brand-500/20', 'bg-emerald-500/20');
        label.classList.replace('text-brand-400', 'text-emerald-400');

        const params = new URLSearchParams({
            template: template,
            subject: subject,
            body: body
        });

        const previewUrl = "{{ route('admin.email-preview') }}?" + params.toString();
        
        previewFrame.onload = () => {
            overlay.classList.add('hidden');
            label.innerText = 'Live';
            label.classList.replace('bg-emerald-500/20', 'bg-brand-500/20');
            label.classList.replace('text-emerald-400', 'text-brand-400');
        };
        
        previewFrame.src = previewUrl;
    }
</script>
@endsection
