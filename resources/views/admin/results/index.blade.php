@extends('layouts.admin')

@section('page_title', 'Race Results')

@section('content')
<div class="admin-main-wrapper">
    {{-- Action Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Import Card --}}
        <div class="bg-white border border-surface-300 rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-bold text-surface-900 uppercase tracking-wider mb-4">Import Results</h3>
            <form action="{{ route('admin.race-results.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs text-surface-500 block mb-1">CSV Files (You can select multiple files)</label>
                    <input type="file" name="csv_files[]" class="w-full text-sm border border-surface-200 rounded-lg p-2" multiple required>
                </div>
                <button type="submit" class="bg-brand-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-brand-600 transition-colors">
                    Upload CSV Files
                </button>
            </form>
        </div>

        {{-- Process Card --}}
        <div class="bg-white border border-surface-300 rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-bold text-surface-900 uppercase tracking-wider mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('admin.race-results.import-latest') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition-colors">
                        Import CSV Terbaru
                    </button>
                </form>

                <form action="{{ route('admin.race-results.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-600 transition-colors">
                        Calculate Rankings & Podiums
                    </button>
                </form>

                <form action="{{ route('admin.race-results.generate-certificates') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-amber-600 transition-colors">
                        Generate PDF Certificates
                    </button>
                </form>

                <form action="{{ route('admin.race-results.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all results?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-rose-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-rose-600 transition-colors">
                        Clear All Results
                    </button>
                </form>
            </div>
            <p class="text-[10px] text-surface-400 mt-4 italic">
                * Rankings are calculated based on Gun Time. Podium is assigned to Top 3 in 10k and 15k groups.
            </p>
        </div>
    </div>

    {{-- Results Table --}}
    <div class="bg-white border border-surface-300 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-50 text-surface-700 uppercase text-[10px] font-bold">
                    <tr>
                        <th class="px-4 py-3">BIB</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Gender</th>
                        <th class="px-4 py-3">Age Cat</th>
                        <th class="px-4 py-3">Gun Time</th>
                        <th class="px-4 py-3">Net Time</th>
                        <th class="px-4 py-3">Overall</th>
                        <th class="px-4 py-3">Cat Rank</th>
                        <th class="px-4 py-3">Grp Rank</th>
                        <th class="px-4 py-3">Podium</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse($results as $result)
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-brand-600">{{ $result->bib_number }}</td>
                            <td class="px-4 py-3 font-medium">{{ $result->participant->full_name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $result->distance_km }}K</td>
                            <td class="px-4 py-3 uppercase">{{ $result->gender }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $result->age_category == 'Master' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $result->age_category }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono">{{ $result->gun_time ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono">{{ $result->net_time ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $result->rank_overall ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $result->rank_category ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $result->rank_group ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($result->is_podium)
                                    <span class="text-emerald-500 font-bold">🏆 PODIUM</span>
                                @else
                                    <span class="text-surface-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-10 text-center text-surface-400 italic">No results found. Please import a CSV file.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($results->hasPages())
            <div class="p-4 border-t border-surface-100">
                {{ $results->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
