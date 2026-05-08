@extends('layouts.public')

@section('title', 'Race Results - ERA TRAIL RUN 2026')

@section('content')
    <!-- Header Spacer -->
    <div class="h-16 md:h-20 bg-brand-950"></div>

    <!-- Hero Section -->
    <section class="relative py-16 md:py-24 bg-gradient-to-br from-brand-900 via-brand-800 to-brand-950 overflow-hidden">
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

    <!-- Results Table Section with Alpine.js -->
    <section class="py-16 bg-white" id="all-results" x-data="{ 
        activeTab: '10', 
        search: '',
        allData: {{ json_encode($groupedResults) }},
        get filteredResults() {
            let results = this.allData[this.activeTab] || [];
            if (!this.search) return results;
            const s = this.search.toLowerCase();
            return results.filter(r => 
                (r.bib_number && r.bib_number.toLowerCase().includes(s)) || 
                (r.participant && r.participant.full_name && r.participant.full_name.toLowerCase().includes(s))
            );
        }
    }">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Tabs Navigation -->
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <template x-for="tab in [{id:'5', label:'5K Family'}, {id:'10', label:'10K'}, {id:'15', label:'15K'}]">
                    <button @click="activeTab = tab.id" 
                            :class="activeTab === tab.id ? 'bg-brand-500 text-white shadow-xl shadow-brand-500/30' : 'bg-surface-50 text-surface-600 hover:bg-surface-100 border border-transparent'"
                            class="px-8 py-4 rounded-2xl font-bold transition-all duration-300 flex items-center gap-2">
                        <span class="text-base" x-text="tab.label"></span>
                    </button>
                </template>
            </div>

            <!-- Client-side Search -->
            <div class="mb-12 max-w-xl mx-auto">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <input type="text" x-model="search" placeholder="Cari nomor BIB atau Nama..." 
                               class="w-full px-4 py-3 bg-white border border-surface-300 rounded-xl text-surface-900 placeholder-surface-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors outline-none">
                        
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button @click="search = ''" x-show="search.length > 0" x-transition class="text-surface-300 hover:text-brand-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button class="px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-semibold rounded-xl hover:from-brand-600 hover:to-brand-700 transition-all shadow-md whitespace-nowrap">
                        Cari
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-surface-200 overflow-hidden shadow-sm min-h-[400px]">
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
                            <template x-for="result in filteredResults" :key="result.id">
                                <tr class="hover:bg-surface-50 transition-colors">
                                    <td class="px-6 py-4 font-display font-bold text-surface-900" x-text="'#' + (result.rank_overall || '-')"></td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-brand-50 text-brand-600 rounded-lg border border-brand-100">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                            </svg>
                                            <span class="font-mono font-black text-sm" x-text="result.bib_number"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-surface-900 uppercase" x-text="result.participant ? result.participant.full_name : '-'"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-surface-700" x-text="result.distance_km + 'K'"></span>
                                            <span class="text-[10px] text-surface-400 uppercase font-bold" x-text="result.age_category"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 uppercase text-surface-600" x-text="result.gender"></td>
                                    <td class="px-6 py-4 font-mono font-bold text-brand-500 text-base" x-text="result.gun_time || '-'"></td>
                                    <td class="px-6 py-4 font-mono text-surface-400" x-text="result.net_time || '-'"></td>
                                </tr>
                            </template>
                            <template x-if="filteredResults.length === 0">
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-surface-400 italic">No results found for your search criteria.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4 flex justify-between items-center px-4">
                <p class="text-[10px] uppercase font-bold text-surface-400 tracking-widest">
                    Showing <span x-text="filteredResults.length"></span> runners in <span x-text="activeTab + 'K'"></span>
                </p>
                <p class="text-[10px] text-surface-300 italic">Official Data Static Cache</p>
            </div>
        </div>
    </section>
@endsection
