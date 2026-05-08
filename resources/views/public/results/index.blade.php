@extends('layouts.public')

@section('title', 'Race Results - ERA TRAIL RUN 2026')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-br from-brand-900 via-brand-800 to-brand-950 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(239,28,36,0.2),transparent_60%)]"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="font-display font-black text-4xl md:text-6xl text-white mb-4">
                {{ __('messages.results_title') }}
            </h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">
                {{ __('messages.results_subtitle') }}
            </p>
        </div>
    </section>

    <!-- Podium Showcase -->
    @if($podiums->isNotEmpty())
    <section class="py-16 bg-surface-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-xs font-bold rounded-full mb-4 tracking-widest uppercase">The Champions</span>
                <h2 class="font-display font-bold text-3xl text-surface-900">Podium Winners</h2>
            </div>

            <div class="space-y-16">
                @foreach($podiums as $distance => $ageCategories)
                    <div class="distance-group">
                        <h3 class="text-2xl font-display font-bold text-brand-600 mb-8 flex items-center gap-3">
                            <span class="w-8 h-8 bg-brand-500 text-white rounded-lg flex items-center justify-center text-sm italic">{{ $distance }}K</span>
                            Distance Winners
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($ageCategories as $ageCat => $genders)
                                @foreach($genders as $gender => $winners)
                                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-surface-200">
                                        <div class="flex justify-between items-center mb-6">
                                            <h4 class="font-bold text-surface-900 uppercase tracking-wide">{{ $ageCat }} {{ $gender }}</h4>
                                            <span class="text-[10px] px-2 py-0.5 bg-surface-100 text-surface-500 rounded font-bold uppercase">{{ $distance }}K</span>
                                        </div>
                                        <div class="space-y-4">
                                            @foreach($winners as $winner)
                                                <div class="flex items-center gap-4 p-3 rounded-xl {{ $winner->rank_group == 1 ? 'bg-amber-50 border border-amber-100' : 'bg-surface-50' }}">
                                                    <div class="w-10 h-10 shrink-0 flex items-center justify-center font-display font-bold text-xl
                                                        {{ $winner->rank_group == 1 ? 'text-amber-500' : ($winner->rank_group == 2 ? 'text-slate-400' : 'text-orange-400') }}">
                                                        {{ $winner->rank_group }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-bold text-surface-900 truncate">{{ $winner->participant->full_name }}</div>
                                                        <div class="text-xs text-surface-500 font-mono">BIB: {{ $winner->bib_number }}</div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="font-mono font-bold text-brand-600">{{ $winner->gun_time }}</div>
                                                        <div class="text-[10px] text-surface-400 uppercase">Gun Time</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Results Table Section -->
    <section class="py-16 bg-white" id="all-results">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Search & Filter Bar -->
            <div class="mb-8 bg-surface-50 p-6 rounded-2xl border border-surface-200">
                <form action="{{ route('results') }}#all-results" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-bold text-surface-400 uppercase mb-1 block">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="BIB or Name..." 
                               class="w-full px-4 py-2 rounded-xl border border-surface-300 focus:border-brand-500 focus:ring-0 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-surface-400 uppercase mb-1 block">Distance</label>
                        <select name="distance" class="w-full px-4 py-2 rounded-xl border border-surface-300 focus:border-brand-500 text-sm">
                            <option value="">All Distances</option>
                            <option value="5" {{ request('distance') == '5' ? 'selected' : '' }}>5K</option>
                            <option value="10" {{ request('distance') == '10' ? 'selected' : '' }}>10K</option>
                            <option value="15" {{ request('distance') == '15' ? 'selected' : '' }}>15K</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-surface-400 uppercase mb-1 block">Category</label>
                        <select name="age_category" class="w-full px-4 py-2 rounded-xl border border-surface-300 focus:border-brand-500 text-sm">
                            <option value="">All Categories</option>
                            <option value="Open" {{ request('age_category') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Master" {{ request('age_category') == 'Master' ? 'selected' : '' }}>Master</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-brand-500 text-white font-bold py-2 rounded-xl hover:bg-brand-600 transition-colors shadow-lg shadow-brand-500/20">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-surface-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-surface-50 text-surface-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Rank</th>
                                <th class="px-6 py-4">BIB</th>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4">Gender</th>
                                <th class="px-6 py-4">Gun Time</th>
                                <th class="px-6 py-4">Net Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            @forelse($results as $result)
                                <tr class="hover:bg-surface-50 transition-colors">
                                    <td class="px-6 py-4 font-display font-bold text-surface-900">#{{ $result->rank_overall ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-brand-50 text-brand-600 rounded font-mono font-bold">{{ $result->bib_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-surface-900 uppercase">{{ $result->participant->full_name ?? 'Participant' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-surface-700">{{ $result->distance_km }}K</span>
                                            <span class="text-[10px] text-surface-400 uppercase font-bold">{{ $result->age_category }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 uppercase text-surface-600">{{ $result->gender }}</td>
                                    <td class="px-6 py-4 font-mono font-bold text-brand-500 text-base">{{ $result->gun_time ?? '-' }}</td>
                                    <td class="px-6 py-4 font-mono text-surface-400">{{ $result->net_time ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-surface-400 italic">No results found for your search criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($results->hasPages())
                    <div class="px-6 py-4 border-t border-surface-100 bg-surface-50">
                        {{ $results->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
