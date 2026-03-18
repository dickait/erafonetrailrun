@extends('layouts.admin')
@section('page_title', __('messages.admin_payments'))
@section('content')
    <div class="bg-white border border-surface-300 rounded-xl">
        <div class="p-4 border-b border-surface-300">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('messages.admin_search_placeholder') }}"
                    class="flex-1 min-w-[200px] px-4 py-2.5 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                <select name="category"
                    class="px-4 py-2.5 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500">
                    <option value="">{{ __('messages.admin_all_categories') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status"
                    class="px-4 py-2.5 bg-surface-50 border border-surface-300 rounded-xl text-sm text-surface-900 focus:border-brand-500">
                    <option value="">{{ __('messages.admin_all_status') }}</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                        {{ __('messages.status_paid') }}
                    </option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        {{ __('messages.status_pending') }}
                    </option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                        {{ __('messages.status_failed') }}
                    </option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
                <button type="submit"
                    class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-xl transition-colors">{{ __('messages.admin_filter') }}</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-300 text-left">
                        <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_invoice') }}</th>
                        <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_participant') }}</th>
                        <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_amount') }}</th>
                        <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_status') }}</th>
                        <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_method') }}</th>
                        <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_registration_date') }}</th>
                        <th class="px-4 py-3 text-surface-700 font-medium">{{ __('messages.admin_payment_date') }}</th>
                        <th class="px-4 py-3 text-surface-700 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                        <tr class="border-b border-surface-300/50 hover:bg-surface-50/30">
                            <td class="px-4 py-3 font-mono text-xs text-surface-700">
                                {{ $pay->invoice_id ?? $pay->mayar_invoice_id ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-surface-900">{{ $pay->participant->full_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-surface-900 font-medium">
                                @if($pay->final_amount)
                                    Rp {{ number_format($pay->final_amount, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($pay->amount, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $pay->status == 'paid' ? 'bg-emerald-50 text-emerald-600' : ($pay->status == 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }}">
                                    {{ ucfirst($pay->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-surface-700">{{ $pay->payment_method ?? '-' }}</td>
                            <td class="px-4 py-3 text-surface-700">{{ $pay->created_at->format('d/m/Y H:i') }} WIB</td>
                            <td class="px-4 py-3 text-surface-700">
                                {{ $pay->paid_at ? $pay->paid_at->format('d/m/Y H:i') : '-' }} WIB</td>
                            <td class="px-4 py-3">
                                <button type="button"
                                    onclick="openEditModal('{{ $pay->id }}', '{{ $pay->status }}', '{{ $pay->paid_at ? $pay->paid_at->format('Y-m-d') : now()->format('Y-m-d') }}', '{{ $pay->paid_at ? $pay->paid_at->format('H:i') : now()->format('H:i') }}')"
                                    class="text-brand-600 hover:text-brand-700 font-medium">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-surface-700">
                                {{ __('messages.admin_no_payments') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($payments->hasPages())
                <div class="p-4 border-t border-surface-300">{{ $payments->links() }}</div>
            @endif
        </div>
@endsection

    @push('modals')
        <div id="editModal"
            style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; background:rgba(0,0,0,0.5);">
            <div
                style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:16px; padding:32px; width:90%; max-width:480px; box-shadow:0 25px 50px rgba(0,0,0,0.25);">
                <form id="editForm" method="POST">
                    @csrf
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                        <div>
                            <h3 style="margin:0; font-size:20px; font-weight:700; color:#1a1a1a;">Edit Pembayaran</h3>
                            <p style="margin:4px 0 0; font-size:13px; color:#888;">Perbarui status dan waktu pembayaran</p>
                        </div>
                        <button type="button" onclick="closeEditModal()"
                            style="background:none; border:none; cursor:pointer; padding:8px; font-size:20px; color:#999;">&times;</button>
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:14px; font-weight:600; color:#333; margin-bottom:6px;">Status
                            Pembayaran</label>
                        <select name="status" id="modalStatus"
                            style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; box-sizing:border-box;">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="expired">Expired</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>

                    <div style="display:flex; gap:12px; margin-bottom:16px;">
                        <div style="flex:1;">
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#333; margin-bottom:6px;">Tanggal
                                Bayar</label>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <select id="modalDay"
                                    style="flex:1; padding:10px 6px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; box-sizing:border-box;">
                                    @for($d = 1; $d <= 31; $d++)
                                        <option value="{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}">
                                            {{ str_pad($d, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <span style="font-weight:700; color:#555;">/</span>
                                <select id="modalMonth"
                                    style="flex:1.2; padding:10px 6px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; box-sizing:border-box;">
                                    @foreach(['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'] as $mv => $ml)
                                        <option value="{{ $mv }}">{{ $ml }}</option>
                                    @endforeach
                                </select>
                                <span style="font-weight:700; color:#555;">/</span>
                                <select id="modalYear"
                                    style="flex:1.4; padding:10px 6px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; box-sizing:border-box;">
                                    @for($y = 2024; $y <= 2030; $y++)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" name="paid_date" id="modalDate">
                        </div>
                        <div style="flex:1;">
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#333; margin-bottom:6px;">Jam
                                Bayar (24 jam) WIB</label>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <select id="modalHour"
                                    style="flex:1; padding:10px 8px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; box-sizing:border-box;">
                                    @for($h = 0; $h < 24; $h++)
                                        <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}">
                                            {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                                <span style="font-weight:700; color:#555;">:</span>
                                <select id="modalMinute"
                                    style="flex:1; padding:10px 8px; border:1px solid #d1d5db; border-radius:10px; font-size:14px; box-sizing:border-box;">
                                    @foreach(['00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'] as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="paid_time" id="modalTime">
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; margin-top:28px;">
                        <button type="button" onclick="closeEditModal()"
                            style="flex:1; padding:12px; border:1px solid #d1d5db; border-radius:10px; background:#f9fafb; color:#555; font-size:14px; font-weight:600; cursor:pointer;">Batal</button>
                        <button type="submit"
                            style="flex:2; padding:12px; border:none; border-radius:10px; background:#E02534; color:#fff; font-size:14px; font-weight:700; cursor:pointer;">Simpan
                            & Kirim Email</button>
                    </div>
                </form>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            function openEditModal(id, status, date, time) {
                document.getElementById('editForm').action = '/admin/payments/' + id + '/update-status';
                document.getElementById('modalStatus').value = status;

                // Parse date YYYY-MM-DD into day/month/year selects
                var dp = date ? date.split('-') : ['{{ now()->year }}', '{{ now()->format("m") }}', '{{ now()->format("d") }}'];
                document.getElementById('modalYear').value = dp[0] || '{{ now()->year }}';
                document.getElementById('modalMonth').value = dp[1] || '{{ now()->format("m") }}';
                document.getElementById('modalDay').value = dp[2] || '{{ now()->format("d") }}';
                syncDate();

                // Parse time HH:mm into hour and minute selects
                var parts = time ? time.split(':') : ['00', '00'];
                document.getElementById('modalHour').value = parts[0] || '00';
                var min = parseInt(parts[1] || 0);
                var rounded = String(Math.round(min / 5) * 5).padStart(2, '0');
                if (rounded === '60') rounded = '55';
                document.getElementById('modalMinute').value = rounded;
                syncTime();

                document.getElementById('editModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
            function syncDate() {
                var y = document.getElementById('modalYear').value;
                var mo = document.getElementById('modalMonth').value;
                var d = document.getElementById('modalDay').value;
                document.getElementById('modalDate').value = y + '-' + mo + '-' + d;
            }
            function syncTime() {
                var h = document.getElementById('modalHour').value;
                var m = document.getElementById('modalMinute').value;
                document.getElementById('modalTime').value = h + ':' + m;
            }
            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
                document.body.style.overflow = '';
            }
            document.getElementById('editModal').addEventListener('click', function (e) {
                if (e.target === this) closeEditModal();
            });
            document.getElementById('modalDay').addEventListener('change', syncDate);
            document.getElementById('modalMonth').addEventListener('change', syncDate);
            document.getElementById('modalYear').addEventListener('change', syncDate);
            document.getElementById('modalHour').addEventListener('change', syncTime);
            document.getElementById('modalMinute').addEventListener('change', syncTime);
            document.getElementById('editForm').addEventListener('submit', function () { syncDate(); syncTime(); });
        </script>
    @endpush