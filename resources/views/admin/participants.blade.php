@extends('layouts.admin')

@section('page_title', 'Data Peserta')

@section('content')
    <!-- DataTables & jQuery -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        /* 
               FIXED LAYOUT ATTEMPTS 
               Ensuring the main container never exceeds screen width
            */
        .admin-main-wrapper {
            max-width: 100%;
            overflow: hidden;
            /* Prevent page-level horizontal scroll */
        }

        .table-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            width: 100%;
            overflow: hidden;
            /* Clip everything inside borders */
            display: flex;
            flex-direction: column;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            /* Enable horizontal scroll */
            -webkit-overflow-scrolling: touch;
            display: block;
        }

        table#participantsTable {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: collapse !important;
            min-width: 800px;
            /* Force scroll on mobile by ensuring content is wider than screen */
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

        .dataTables_info,
        .dataTables_paginate {
            padding: 1rem;
            font-size: 11px;
            color: #6b7280;
        }
    </style>

    @php
        $labelMap = [
            'id' => 'ID',
            'bib_number' => 'BIB',
            'full_name' => 'Nama',
            'email' => 'Email',
            'phone' => 'No. Telp',
            'gender' => 'L/P',
            'age' => 'Umur',
            'category_id' => 'Kategori',
            'payment_status' => 'Status',
            'checked_in' => 'Check-in',
            'created_at' => 'Tgl Daftar',
            'shirt_size' => 'Ukuran Kaos',
            'blood_type' => 'Gol. Darah',
        ];
        $displayCols = $requestedCols ?? ['full_name', 'email', 'phone', 'age', 'shirt_size', 'payment_status', 'created_at'];
    @endphp

    <div class="admin-main-wrapper">
        {{-- Custom Filter Section --}}
        <div class="bg-white border border-surface-300 rounded-xl mb-6 shadow-sm p-4 md:p-6 overflow-hidden">
            <form id="filterForm" method="GET" class="space-y-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label
                            class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, email, atau BIB..."
                            class="w-full px-4 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 sm:w-48">
                            <label
                                class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Mulai</label>
                            <input type="datetime-local" name="start_date" value="{{ $startDate }}"
                                class="w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500">
                        </div>
                        <div class="flex-1 sm:w-48">
                            <label
                                class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1.5 ml-1">Selesai</label>
                            <input type="datetime-local" name="end_date" value="{{ $endDate }}"
                                class="w-full px-3 py-2 bg-surface-50 border border-surface-300 rounded-xl text-sm focus:border-brand-500">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-end gap-3 pt-2 border-t border-surface-100">
                    <div class="grid grid-cols-2 lg:flex gap-3 flex-1 w-full">
                        {{-- Dropdowns --}}
                        <div class="flex-1">
                            <label
                                class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Kategori</label>
                            <select name="category"
                                class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm h-[38px] cursor-pointer">
                                <option value="">Semua</option>
                                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label
                                class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Status</label>
                            <select name="payment_status"
                                class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm h-[38px] cursor-pointer">
                                <option value="">Semua</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas
                                </option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                            </select>
                        </div>
                        <div class="flex-1 lg:max-w-[80px]">
                            <label
                                class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Limit</label>
                            <select name="per_page"
                                class="w-full px-3 py-2 bg-white border border-surface-300 rounded-xl text-sm h-[38px] cursor-pointer">
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                            </select>
                        </div>
                        {{-- Column Toggler --}}
                        <div class="relative flex-1 md:flex-none self-end">
                            <label
                                class="text-[9px] font-bold text-surface-400 uppercase tracking-tight block mb-1 ml-1">Tampilan</label>
                            <button type="button"
                                onclick="document.getElementById('colDropdown').classList.toggle('hidden')"
                                class="w-full px-4 py-2 bg-white border border-surface-300 hover:bg-surface-50 text-sm font-medium rounded-xl h-[38px] flex items-center justify-center gap-2 cursor-pointer">
                                Kolom
                            </button>
                            <div id="colDropdown"
                                class="hidden absolute right-0 top-full mt-2 w-max min-w-[250px] bg-white border border-surface-300 rounded-xl shadow-xl z-50 p-3">
                                <div class="space-y-1 max-h-[300px] overflow-y-auto">
                                    @foreach($allColumns as $col)
                                        @if(!in_array($col, ['id', 'event_id', 'user_id']))
                                            <label
                                                class="flex items-center gap-3 px-2 py-1 hover:bg-surface-50 rounded italic cursor-pointer">
                                                <input type="checkbox" name="cols[]" value="{{ $col }}" {{ in_array($col, $displayCols) ? 'checked' : '' }} class="col-checkbox cursor-pointer">
                                                <span class="text-xs">{{ $labelMap[$col] ?? $col }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="mt-3 pt-2 border-t flex justify-between px-2">
                                    <button type="button" onclick="resetColumns()"
                                        class="text-[10px] text-surface-400 underline cursor-pointer">Reset</button>
                                    <button type="submit"
                                        class="text-[10px] text-brand-500 font-bold cursor-pointer">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="button" onclick="exportData()"
                            class="px-4 py-2 bg-surface-100 border border-surface-300 text-xs font-bold rounded-xl h-[38px] cursor-pointer">EXPORT</button>
                        <button type="submit"
                            class="px-6 py-2 bg-brand-500 text-white text-xs font-bold rounded-xl h-[38px] cursor-pointer">CARI</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- SCROLLABLE TABLE CARD --}}
        <div class="table-card shadow-sm">
            <div class="table-responsive">
                <table id="participantsTable" class="display compact hover stripe">
                    <thead>
                        <tr>
                            @foreach($displayCols as $col)
                                <th>{{ $labelMap[$col] ?? $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $p)
                            <tr>
                                @foreach($displayCols as $col)
                                    <td>
                                        @if($col == 'full_name') {{ $p->full_name }}
                                        @elseif($col == 'bib_number') <span
                                            class="text-brand-500 font-mono">{{ $p->bib_number ?? '-' }}</span>
                                        @elseif($col == 'category_id') <span>{{ $p->category->name ?? '-' }}</span>
                                        @elseif($col == 'payment_status') <span
                                            class="{{ $p->payment_status == 'paid' ? 'text-brand-500' : 'text-red-600' }}">{{ $p->payment_status == 'paid' ? 'Lunas' : 'Pending' }}</span>
                                        @elseif($col == 'checked_in') {!! $p->checked_in ? '✓' : '—' !!}
                                        @elseif($col == 'created_at') {{ $p->created_at->format('d/m/y H:i') }}
                                        @else {{ $p->{$col} ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($participants instanceof \Illuminate\Pagination\LengthAwarePaginator && $participants->hasPages())
            <div class="mt-4 p-4">{{ $participants->links() }}</div>
        @endif
    </div>

    {{-- JS Utilities --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function () {
            if ($.fn.DataTable) {
                $('#participantsTable').DataTable({
                    paging: false, searching: true, info: true, ordering: true, autoWidth: false
                });
            }
        });

        function resetColumns() { $('.col-checkbox').prop('checked', false); }
        function exportData() {
            const params = $('#filterForm').serialize();
            window.location.href = "{{ route('admin.participants.export') }}?" + params;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('cols[]')) {
                const saved = localStorage.getItem('admin_participants_cols_v2');
                if (saved) {
                    const cols = JSON.parse(saved);
                    if (cols.length > 0) {
                        cols.forEach(c => urlParams.append('cols[]', c));
                        window.location.search = urlParams.toString();
                    }
                }
            }
        });
        $('#filterForm').on('submit', function () {
            const cols = $('.col-checkbox:checked').map(function () { return $(this).val(); }).get();
            localStorage.setItem('admin_participants_cols_v2', JSON.stringify(cols));
        });
    </script>
@endsection