@extends('layouts.admin')

@section('page_title', 'Data Pembayaran')

@section('content')
    <!-- DataTables & jQuery -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .admin-main-wrapper {
            max-width: 100%;
            overflow: hidden;
        }

        .table-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: block;
        }

        table#paymentsTable {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: collapse !important;
            min-width: 1000px;
        }

        table.dataTable thead th {
            background: #f9fafb !important;
            color: #374151 !important;
            font-weight: 600 !important;
            border-bottom: 1px solid #e5e7eb !important;
            padding: 12px 16px !important;
            text-transform: uppercase;
            font-size: 11px;
            white-space: nowrap;
        }

        table.dataTable tbody td {
            padding: 12px 16px !important;
            border-bottom: 1px solid #f3f4f6 !important;
            font-size: 13px;
            white-space: nowrap;
        }

        .dataTables_info, .dataTables_paginate {
            padding: 1rem;
            font-size: 11px;
            color: #6b7280;
        }
    </style>

    <div class="admin-main-wrapper">
        {{-- Filter Section --}}
        <div class="bg-white border border-surface-300 rounded-xl mb-6 shadow-sm p-4 md:p-6 overflow-hidden">
            <form id="filterForm" method="GET" class="space-y-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, BIB, atau Invoice..." class="w-full px-4 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 sm:w-48">
                            <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Dari Tanggal</label>
                            <input type="datetime-local" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500 cursor-pointer">
                        </div>
                        <div class="flex-1 sm:w-48">
                            <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Sampai Tanggal</label>
                            <input type="datetime-local" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500 cursor-pointer">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-end gap-3 pt-2 border-t border-surface-100">
                    <div class="grid grid-cols-2 lg:flex gap-3 flex-1 w-full">
                        <div class="flex-1">
                            <label class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Kategori</label>
                            <select name="category" class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm h-[38px] cursor-pointer">
                                <option value="">Semua</option>
                                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm h-[38px] cursor-pointer">
                                <option value="">Semua</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>
                        <div class="flex-1 lg:max-w-[80px]">
                            <label class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Limit</label>
                            <select name="per_page" class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm h-[38px] cursor-pointer">
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="button" onclick="exportPayments()" class="flex-1 md:flex-none px-4 py-2 bg-surface-100 border border-surface-300 hover:bg-surface-200 text-surface-900 text-xs font-bold rounded-xl transition-colors cursor-pointer h-[38px]">EXPORT</button>
                        <button type="submit" class="flex-1 md:flex-none px-6 py-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl transition-colors cursor-pointer h-[38px]">CARI</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="table-card shadow-sm">
            <div class="table-responsive">
                <table id="paymentsTable" class="display compact hover stripe">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Peserta</th>
                            <th>BIB</th>
                            <th>Kategori</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Method</th>
                            <th>Tgl Daftar</th>
                            <th>Tgl Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $pay)
                            <tr>
                                <td class="font-mono text-[11px] text-surface-700">
                                    {{ $pay->invoice_id ?? $pay->mayar_invoice_id ?? '-' }}
                                </td>
                                <td class="font-medium text-surface-900">{{ $pay->participant->full_name ?? '-' }}</td>
                                <td class="text-brand-500 font-mono font-medium">{{ $pay->participant->bib_number ?? '-' }}</td>
                                <td>
                                    <span class="px-2 py-0.5 bg-surface-100 rounded text-[11px]">{{ $pay->participant->category->name ?? '-' }}</span>
                                </td>
                                <td class="font-bold text-surface-900">
                                    Rp {{ number_format($pay->final_amount ?? $pay->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="px-2 py-1 text-[11px] font-bold rounded-full {{ $pay->status == 'paid' ? 'bg-brand-50 text-brand-500' : ($pay->status == 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                                        {{ strtoupper($pay->status) }}
                                    </span>
                                </td>
                                <td class="text-surface-600">{{ $pay->payment_method ?? '-' }}</td>
                                <td class="font-mono text-[11px]">{{ $pay->created_at->format('d/m/y H:i') }}</td>
                                <td class="font-mono text-[11px]">
                                    {{ $pay->paid_at ? $pay->paid_at->format('d/m/y H:i') : '-' }}
                                </td>
                                <td>
                                    <button type="button"
                                        onclick="openEditModal('{{ $pay->id }}', '{{ $pay->status }}', '{{ $pay->paid_at ? $pay->paid_at->format('Y-m-d') : now()->format('Y-m-d') }}', '{{ $pay->paid_at ? $pay->paid_at->format('H:i:s') : now()->format('H:i:s') }}')"
                                        class="px-3 py-1 bg-surface-100 hover:bg-surface-200 border border-surface-300 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                        EDIT
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments instanceof \Illuminate\Pagination\LengthAwarePaginator && $payments->hasPages())
            <div class="mt-4">{{ $payments->links() }}</div>
        @endif
    </div>

    {{-- Edit Modal --}}
    @push('modals')
        <div id="editModal" class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <form id="editForm" method="POST" class="p-6">
                    @csrf
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-surface-900">Edit Pembayaran</h3>
                            <p class="text-xs text-surface-500">Perbarui status dan waktu transaksi</p>
                        </div>
                        <button type="button" onclick="closeEditModal()" class="p-2 hover:bg-surface-100 rounded-full text-surface-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-surface-400 uppercase tracking-widest mb-2">Status Pembayaran</label>
                            <select name="status" id="modalStatus" class="w-full px-4 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500 cursor-pointer">
                                <option value="pending">PENDING</option>
                                <option value="paid">PAID</option>
                                <option value="failed">FAILED</option>
                                <option value="expired">EXPIRED</option>
                                <option value="refunded">REFUNDED</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-surface-400 uppercase tracking-widest mb-2">Tgl Bayar</label>
                                <input type="date" name="paid_date_raw" id="modalPaidDateRaw" class="w-full px-4 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500 cursor-pointer">
                                <input type="hidden" name="paid_date" id="modalDate">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-surface-400 uppercase tracking-widest mb-2">Jam Bayar</label>
                                <input type="time" step="1" name="paid_time_raw" id="modalPaidTimeRaw" class="w-full px-4 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500 cursor-pointer">
                                <input type="hidden" name="paid_time" id="modalTime">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2.5 border border-surface-300 text-surface-700 text-sm font-bold rounded-xl hover:bg-surface-50 transition-colors">BATAL</button>
                        <button type="submit" class="flex-[2] px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-brand-500/20">SIMPAN & KIRIM EMAIL</button>
                    </div>
                </form>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function () {
                if ($.fn.DataTable) {
                    $('#paymentsTable').DataTable({
                        paging: false, searching: true, info: true, ordering: true, autoWidth: false
                    });
                }
            });

            function exportPayments() {
                const params = $('#filterForm').serialize();
                window.location.href = "{{ route('admin.payments.export') }}?" + params;
            }

            function openEditModal(id, status, date, time) {
                document.getElementById('editForm').action = '/admin/payments/' + id + '/update-status';
                document.getElementById('modalStatus').value = status;
                
                // Using standard HTML5 inputs for better UI
                document.getElementById('modalPaidDateRaw').value = date;
                document.getElementById('modalPaidTimeRaw').value = time;
                
                syncInputs();

                document.getElementById('editModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function syncInputs() {
                document.getElementById('modalDate').value = document.getElementById('modalPaidDateRaw').value;
                document.getElementById('modalTime').value = document.getElementById('modalPaidTimeRaw').value;
            }

            document.getElementById('modalPaidDateRaw').addEventListener('change', syncInputs);
            document.getElementById('modalPaidTimeRaw').addEventListener('change', syncInputs);

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.body.style.overflow = '';
            }

            document.getElementById('editModal').addEventListener('click', function (e) {
                if (e.target === this) closeEditModal();
            });

            document.getElementById('editForm').addEventListener('submit', syncInputs);
        </script>
    @endpush
@endsection