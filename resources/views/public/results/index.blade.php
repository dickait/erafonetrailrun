@extends('layouts.public')

@section('title', 'Race Results - ERA TRAIL RUN 2026')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

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
                @if($totalCount > 0)
                    {{ app()->getLocale() === 'id' ? 'Hasil Resmi Lomba Era Trail Run 2026' : 'Official Race Results for Era Trail Run 2026' }}
                @else
                    {{ __('messages.results_subtitle') }}
                @endif
            </p>
        </div>
    </section>

    <section class="py-16 bg-white" id="all-results" x-data="{ 
        activeTab: '10', 
        search: '',
        searchInput: '',
        performSearch() {
            this.search = this.searchInput.trim();
        },
        allData: {{ json_encode($groupedResults) }},
        showAdvanced: false,
        filterGender: 'all',
        filterAgeCategory: 'all',
        filterDistance: 'all',
        sortBy: 'rank_asc',
        expandedRows: [],
        toggleRow(id) {
            if (this.expandedRows.includes(id)) {
                this.expandedRows = this.expandedRows.filter(x => x !== id);
            } else {
                this.expandedRows = [...this.expandedRows, id];
            }
        },
        isRowExpanded(id) {
            return this.expandedRows.includes(id);
        },
        capitalize(str) {
            if (!str) return '';
            return str.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
        },
        getGroupTotalCount(result) {
            if (!result) return 0;
            const distance = parseInt(result.distance_km);
            const list = this.allData[distance] || [];
            return list.filter(r => r.age_category === result.age_category && r.gender === result.gender).length;
        },
        getGenderTotalCount(result) {
            if (!result) return 0;
            const distance = parseInt(result.distance_km);
            const list = this.allData[distance] || [];
            return list.filter(r => r.gender === result.gender).length;
        },
        selectedResult: null,
        showModal: false,
        showDetailsModal(result) {
            this.selectedResult = result;
            this.showModal = true;
        },
        showCertModal: false,
        certResult: null,
        getCertificatePdfUrl(result) {
            if (!result || !result.bib_number || !result.participant) return '#';
            const nameSlug = result.participant.full_name.trim().replace(/\s+/g, '_').toLowerCase();
            return '/certificates/certificate_' + result.bib_number + '_' + nameSlug + '.pdf';
        },
        generateCertificate(result) {
            this.certResult = result;
            this.showCertModal = true;
        },
        downloadCertificate(result) {
            const canvas = document.createElement('canvas');
            canvas.width = 1200;
            canvas.height = 850;
            const ctx = canvas.getContext('2d');
            
            // Draw background gradient
            const bgGrad = ctx.createRadialGradient(600, 425, 100, 600, 425, 800);
            bgGrad.addColorStop(0, '#ffffff');
            bgGrad.addColorStop(1, '#fff5f5');
            ctx.fillStyle = bgGrad;
            ctx.fillRect(0, 0, 1200, 850);
            
            // Draw thick border
            ctx.strokeStyle = '#ef1c24';
            ctx.lineWidth = 15;
            ctx.strokeRect(20, 20, 1160, 810);
            
            // Draw inner gold border
            ctx.strokeStyle = '#d4af37';
            ctx.lineWidth = 3;
            ctx.strokeRect(35, 35, 1130, 780);
            
            // Corner ornaments helper
            const drawOrnament = (x, y, rotation) => {
                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(rotation);
                ctx.strokeStyle = '#d4af37';
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.moveTo(0, 40);
                ctx.lineTo(0, 0);
                ctx.lineTo(40, 0);
                ctx.stroke();
                ctx.restore();
            };
            drawOrnament(45, 45, 0);
            drawOrnament(1155, 45, Math.PI / 2);
            drawOrnament(1155, 805, Math.PI);
            drawOrnament(45, 805, -Math.PI / 2);
            
            // Event Title
            ctx.fillStyle = '#1c1917';
            ctx.font = 'bold 36px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('ERAFONE TRAIL RUN 2026', 600, 110);
            
            // Subtitle
            ctx.fillStyle = '#ef1c24';
            ctx.font = 'bold 18px sans-serif';
            ctx.fillText('OFFICIAL FINISHER CERTIFICATE', 600, 145);
            
            // Main Title
            ctx.fillStyle = '#d4af37';
            ctx.font = 'italic bold 56px Georgia, serif';
            ctx.fillText('Certificate of Achievement', 600, 230);
            
            // Presented to
            ctx.fillStyle = '#57534e';
            ctx.font = 'italic 20px Georgia, serif';
            ctx.fillText('This is proudly presented to', 600, 290);
            
            // Name
            ctx.fillStyle = '#1c1917';
            ctx.font = 'bold 44px sans-serif';
            ctx.fillText(result.participant ? result.participant.full_name.toUpperCase() : '-', 600, 360);
            
            // Line
            ctx.strokeStyle = '#d4af37';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(350, 385);
            ctx.lineTo(850, 385);
            ctx.stroke();
            
            // Text
            ctx.fillStyle = '#57534e';
            ctx.font = '18px sans-serif';
            ctx.fillText('for successfully completing the Erafone Trail Run 2026 in the ' + (parseFloat(result.distance_km) === 5 ? '5K Family' : parseFloat(result.distance_km) + 'K') + ' Category', 600, 425);
            
            // Stats box
            ctx.fillStyle = '#f8fafc';
            ctx.fillRect(150, 470, 900, 180);
            ctx.strokeStyle = '#e2e8f0';
            ctx.lineWidth = 1;
            ctx.strokeRect(150, 470, 900, 180);
            
            // Dividers
            ctx.beginPath();
            ctx.moveTo(375, 470); ctx.lineTo(375, 650);
            ctx.moveTo(600, 470); ctx.lineTo(600, 650);
            ctx.moveTo(825, 470); ctx.lineTo(825, 650);
            ctx.stroke();
            
            // Draw columns helper
            const drawCol = (label, value, x) => {
                ctx.fillStyle = '#64748b';
                ctx.font = 'bold 14px sans-serif';
                ctx.fillText(label.toUpperCase(), x, 515);
                
                ctx.fillStyle = '#1e293b';
                ctx.font = 'bold 36px sans-serif';
                ctx.fillText(value, x, 580);
            };
            
            drawCol('BIB Number', result.bib_number, 262.5);
            drawCol('Category Rank', result.rank_category ? '#' + result.rank_category : '-', 487.5);
            drawCol('Net Time', result.net_time || result.gun_time || '-', 712.5);
            drawCol('Avg Pace', result.pace || '-', 937.5);
            
            // Footer Info
            ctx.fillStyle = '#64748b';
            ctx.font = '14px sans-serif';
            ctx.fillText('Date: 28 June 2026', 300, 740);
            ctx.fillText('Jakarta, Indonesia', 300, 760);
            
            ctx.fillText('Race Director', 900, 740);
            ctx.strokeStyle = '#cbd5e1';
            ctx.beginPath();
            ctx.moveTo(800, 715);
            ctx.lineTo(1000, 715);
            ctx.stroke();
            
            // Decorative watermark
            ctx.fillStyle = 'rgba(239, 28, 36, 0.03)';
            ctx.font = 'bold 300px sans-serif';
            ctx.fillText('ERA', 600, 520);
            
            // Download Action
            const link = document.createElement('a');
            link.download = 'Certificate_' + (result.bib_number || 'Runner') + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        },
        get filteredResults() {
            let results = [];
            if (this.activeTab === 'all') {
                results = this.allData['all'] || [];
            } else {
                results = this.allData[this.activeTab] || [];
            }
            
            // Apply search
            if (this.search) {
                const s = this.search.toLowerCase();
                results = results.filter(r => 
                    (r.bib_number && String(r.bib_number).toLowerCase().includes(s)) || 
                    (r.participant && r.participant.full_name && String(r.participant.full_name).toLowerCase().includes(s))
                );
            }
            
            // Apply advanced filters
            if (this.filterGender !== 'all') {
                results = results.filter(r => r.gender && r.gender.toLowerCase() === this.filterGender.toLowerCase());
            }
            
            if (this.filterAgeCategory !== 'all') {
                results = results.filter(r => r.age_category && r.age_category.toLowerCase() === this.filterAgeCategory.toLowerCase());
            }
            
            if (this.activeTab === 'all' && this.filterDistance !== 'all') {
                results = results.filter(r => Math.round(r.distance_km) === parseInt(this.filterDistance));
            }
            
            // Sort results
            results = [...results];
            results.sort((a, b) => {
                let valA, valB;
                switch (this.sortBy) {
                    case 'rank_asc':
                        valA = a.rank_category ?? 999999;
                        valB = b.rank_category ?? 999999;
                        return valA - valB;
                    case 'rank_desc':
                        valA = a.rank_category ?? -1;
                        valB = b.rank_category ?? -1;
                        return valB - valA;
                    case 'bib_asc':
                        valA = a.bib_number || '';
                        valB = b.bib_number || '';
                        return valA.localeCompare(valB, undefined, {numeric: true, sensitivity: 'base'});
                    case 'bib_desc':
                        valA = a.bib_number || '';
                        valB = b.bib_number || '';
                        return valB.localeCompare(valA, undefined, {numeric: true, sensitivity: 'base'});
                    case 'name_asc':
                        valA = (a.participant && a.participant.full_name) || '';
                        valB = (b.participant && b.participant.full_name) || '';
                        return valA.localeCompare(valB);
                    case 'name_desc':
                        valA = (a.participant && a.participant.full_name) || '';
                        valB = (b.participant && b.participant.full_name) || '';
                        return valB.localeCompare(valA);
                    case 'time_asc':
                        valA = a.net_time || a.gun_time || '99:99:99';
                        valB = b.net_time || b.gun_time || '99:99:99';
                        return valA.localeCompare(valB);
                    case 'time_desc':
                        valA = a.net_time || a.gun_time || '00:00:00';
                        valB = b.net_time || b.gun_time || '00:00:00';
                        return valB.localeCompare(valA);
                    case 'pace_asc':
                        valA = a.pace || '99:99';
                        valB = b.pace || '99:99';
                        if (valA === '-') valA = '99:99';
                        if (valB === '-') valB = '99:99';
                        return valA.localeCompare(valB);
                    case 'pace_desc':
                        valA = a.pace || '00:00';
                        valB = b.pace || '00:00';
                        if (valA === '-') valA = '00:00';
                        if (valB === '-') valB = '00:00';
                        return valB.localeCompare(valA);
                    default:
                        return 0;
                }
            });
            
            return results;
        }
    }">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Tabs Navigation -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 border-b border-surface-150 pb-6">
                <div class="flex flex-wrap gap-2">
                    <template x-for="tab in [
                        {id:'10', label:'10K Overall'},
                        {id:'15', label:'15K Overall'},
                        {id:'podium', label:'Podium Winners'}
                    ]">
                        <button @click="activeTab = tab.id; filterGender = 'all'; filterAgeCategory = 'all'; if(tab.id !== 'podium') { filterDistance = tab.id; } else { filterDistance = 'all'; }" 
                                :class="activeTab === tab.id ? 'bg-brand-600 text-white shadow-md shadow-brand-600/10' : 'bg-surface-50 text-black hover:bg-surface-100 border border-surface-200'"
                                class="px-5 py-2.5 rounded-xl font-bold transition-all duration-200 flex items-center gap-1.5 text-xs">
                            <span x-text="tab.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Sub Category Filters for 10K / 15K -->
            <div x-show="activeTab === '10' || activeTab === '15'" class="mb-8 flex flex-wrap gap-2 justify-center" x-cloak>
                <button @click="filterGender = 'all'; filterAgeCategory = 'all'"
                        :class="filterGender === 'all' && filterAgeCategory === 'all' ? 'bg-brand-600 text-white shadow-sm' : 'bg-surface-50 text-black hover:bg-surface-100 border border-surface-200'"
                        class="px-4 py-2 rounded-xl font-bold transition-all duration-200 text-xs">
                    Overall
                </button>
                <button @click="filterGender = 'male'; filterAgeCategory = 'open'"
                        :class="filterGender === 'male' && filterAgeCategory === 'open' ? 'bg-brand-600 text-white shadow-sm' : 'bg-surface-50 text-black hover:bg-surface-100 border border-surface-200'"
                        class="px-4 py-2 rounded-xl font-bold transition-all duration-200 text-xs">
                    Male Open
                </button>
                <button @click="filterGender = 'male'; filterAgeCategory = 'master'"
                        :class="filterGender === 'male' && filterAgeCategory === 'master' ? 'bg-brand-600 text-white shadow-sm' : 'bg-surface-50 text-black hover:bg-surface-100 border border-surface-200'"
                        class="px-4 py-2 rounded-xl font-bold transition-all duration-200 text-xs">
                    Male Master
                </button>
                <button @click="filterGender = 'female'; filterAgeCategory = 'open'"
                        :class="filterGender === 'female' && filterAgeCategory === 'open' ? 'bg-brand-600 text-white shadow-sm' : 'bg-surface-50 text-black hover:bg-surface-100 border border-surface-200'"
                        class="px-4 py-2 rounded-xl font-bold transition-all duration-200 text-xs">
                    Female Open
                </button>
                <button @click="filterGender = 'female'; filterAgeCategory = 'master'"
                        :class="filterGender === 'female' && filterAgeCategory === 'master' ? 'bg-brand-600 text-white shadow-sm' : 'bg-surface-50 text-black hover:bg-surface-100 border border-surface-200'"
                        class="px-4 py-2 rounded-xl font-bold transition-all duration-200 text-xs">
                    Female Master
                </button>
            </div>

            <!-- Client-side Search -->
            <div class="mb-8 max-w-xl mx-auto" x-show="activeTab !== 'podium'">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <input type="text" x-model="searchInput" @keydown.enter.prevent="performSearch()" placeholder="Cari nomor BIB atau Nama..." 
                               class="w-full px-4 py-2.5 bg-white border border-surface-300 rounded-xl text-black placeholder-surface-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors outline-none text-sm">
                        
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click.prevent="searchInput = ''; performSearch();" x-show="searchInput.length > 0" x-transition class="text-surface-300 hover:text-brand-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" @click.prevent="performSearch()" class="px-6 py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-semibold rounded-xl hover:from-brand-600 hover:to-brand-700 transition-all shadow-sm text-sm whitespace-nowrap">
                        Cari
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-surface-200 overflow-hidden shadow-sm min-h-[400px]" x-show="activeTab !== 'podium'">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-surface-50 text-black uppercase text-[9px] font-bold tracking-wider border-b border-surface-200">
                            <tr>
                                <th class="px-4 py-3.5 w-10 text-center"></th>
                                <!-- Pos Column Sortable -->
                                <th class="px-4 py-3.5 cursor-pointer select-none hover:bg-surface-100 transition-colors" @click="sortBy = (sortBy === 'rank_asc' ? 'rank_desc' : 'rank_asc')">
                                    <div class="flex items-center gap-1.5">
                                        <span x-text="(filterGender !== 'all' || filterAgeCategory !== 'all') ? 'Cat Pos' : 'Pos'"></span>
                                        <span class="flex flex-col text-[8px] leading-[4px]">
                                            <span :class="sortBy === 'rank_asc' ? 'text-brand-600' : 'text-black/30'">▲</span>
                                            <span :class="sortBy === 'rank_desc' ? 'text-brand-600' : 'text-black/30'">▼</span>
                                        </span>
                                    </div>
                                </th>
                                <!-- Race No Column Sortable -->
                                <th class="px-4 py-3.5 cursor-pointer select-none hover:bg-surface-100 transition-colors" @click="sortBy = (sortBy === 'bib_asc' ? 'bib_desc' : 'bib_asc')">
                                    <div class="flex items-center gap-1.5">
                                        <span>Race No</span>
                                        <span class="flex flex-col text-[8px] leading-[4px]">
                                            <span :class="sortBy === 'bib_asc' ? 'text-brand-600' : 'text-black/30'">▲</span>
                                            <span :class="sortBy === 'bib_desc' ? 'text-brand-600' : 'text-black/30'">▼</span>
                                        </span>
                                    </div>
                                </th>
                                <!-- Name Column Sortable -->
                                <th class="px-4 py-3.5 cursor-pointer select-none hover:bg-surface-100 transition-colors" @click="sortBy = (sortBy === 'name_asc' ? 'name_desc' : 'name_asc')">
                                    <div class="flex items-center gap-1.5">
                                        <span>Name</span>
                                        <span class="flex flex-col text-[8px] leading-[4px]">
                                            <span :class="sortBy === 'name_asc' ? 'text-brand-600' : 'text-black/30'">▲</span>
                                            <span :class="sortBy === 'name_desc' ? 'text-brand-600' : 'text-black/30'">▼</span>
                                        </span>
                                    </div>
                                </th>
                                <!-- Time Column Sortable -->
                                <th class="px-4 py-3.5 cursor-pointer select-none hover:bg-surface-100 transition-colors" @click="sortBy = (sortBy === 'time_asc' ? 'time_desc' : 'time_asc')">
                                    <div class="flex items-center gap-1.5">
                                        <span>Time</span>
                                        <span class="flex flex-col text-[8px] leading-[4px]">
                                            <span :class="sortBy === 'time_asc' ? 'text-brand-600' : 'text-black/30'">▲</span>
                                            <span :class="sortBy === 'time_desc' ? 'text-brand-600' : 'text-black/30'">▼</span>
                                        </span>
                                    </div>
                                </th>
                                <th class="px-4 py-3.5">Category</th>
                                <th class="px-4 py-3.5" x-text="(filterGender !== 'all' || filterAgeCategory !== 'all') ? 'Overall Pos' : 'Cat Pos'"></th>
                                <th class="px-4 py-3.5">Gender</th>
                                <th class="px-4 py-3.5">Gen Pos</th>
                            </tr>
                        </thead>
                        <template x-for="result in filteredResults" :key="result.id">
                            <tbody class="divide-y divide-surface-100 border-b border-surface-100 last:border-b-0">
                                <tr class="hover:bg-surface-50/70 transition-colors">
                                    <!-- Expand/Collapse Button Column -->
                                    <td class="px-4 py-3.5 text-center">
                                        <button @click="toggleRow(result.id)" class="text-brand-500 hover:text-brand-700 transition-colors focus:outline-none flex items-center justify-center mx-auto" type="button">
                                            <svg x-show="!isRowExpanded(result.id)" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <svg x-show="isRowExpanded(result.id)" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" />
                                            </svg>
                                        </button>
                                    </td>
                                    
                                    <!-- Pos (Category position in that distance, swapped to rank_group when filtered) -->
                                    <td class="px-4 py-3.5 font-display font-bold text-black" x-text="(filterGender !== 'all' || filterAgeCategory !== 'all') ? (result.rank_group ? '#' + result.rank_group : '-') : (result.rank_category ? '#' + result.rank_category : '-')"></td>
                                    
                                    <!-- Race No (BIB) -->
                                    <td class="px-4 py-3.5">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand-50 text-brand-600 rounded-lg border border-brand-100">
                                            <span class="font-mono font-black text-xs" x-text="result.bib_number"></span>
                                        </div>
                                    </td>
                                    
                                    <!-- Name -->
                                    <td class="px-4 py-3.5 font-bold text-black uppercase" x-text="result.participant ? result.participant.full_name : '-'"></td>
                                    
                                    <!-- Time (Net Time preference, fallback to Gun Time) -->
                                    <td class="px-4 py-3.5 font-mono font-bold text-brand-500" x-text="result.net_time || result.gun_time || '-'"></td>
                                    
                                    <!-- Category (Distance + Age group) -->
                                    <td class="px-4 py-3.5">
                                        <div class="whitespace-nowrap font-bold text-black">
                                            <span x-text="parseFloat(result.distance_km) === 5 ? '5K Family' : parseFloat(result.distance_km) + 'K'"></span>
                                            <span x-show="parseFloat(result.distance_km) !== 5" x-text="' ' + result.age_category"></span>
                                        </div>
                                    </td>
                                    
                                    <!-- Cat Pos (Position in group, swapped to rank_category when filtered) -->
                                    <td class="px-4 py-3.5 font-mono text-black" x-text="(filterGender !== 'all' || filterAgeCategory !== 'all') ? (result.rank_category ? '#' + result.rank_category : '-') : (result.rank_group ? '#' + result.rank_group : '-')"></td>
                                    
                                    <!-- Gender -->
                                    <td class="px-4 py-3.5 uppercase text-black" x-text="result.gender"></td>
                                    
                                    <!-- Gen Pos (Position in distance + gender) -->
                                    <td class="px-4 py-3.5 font-mono text-black" x-text="result.gender_pos ? '#' + result.gender_pos : '-'"></td>
                                </tr>
                                <!-- Expanded row immediately follows parent row -->
                                <tr x-show="isRowExpanded(result.id)" x-cloak class="bg-surface-50/40 border-b border-surface-200">
                                    <td colspan="9" class="px-6 py-5">
                                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                            <!-- Quick Stats / Detailed Info -->
                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-8 gap-y-3 text-xs w-full md:w-auto">
                                                <div>
                                                    <span class="text-[9px] text-black block font-semibold uppercase">Community/Club</span>
                                                    <span class="font-bold text-black" x-text="(result.participant && result.participant.community) || '-'"></span>
                                                </div>
                                                <div>
                                                    <span class="text-[9px] text-black block font-semibold uppercase">Finish Time</span>
                                                    <span class="font-mono font-bold text-black" x-text="result.gun_time || '-'"></span>
                                                </div>
                                                <div>
                                                    <span class="text-[9px] text-black block font-semibold uppercase">Overall Rank</span>
                                                    <span class="font-mono font-bold text-black" x-text="result.rank_overall ? '#' + result.rank_overall + '/' + allData[parseInt(result.distance_km)].length : '-'"></span>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex flex-wrap gap-2 w-full md:w-auto shrink-0 mt-3 md:mt-0">
                                                <button @click="showDetailsModal(result)" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-surface-300 hover:border-brand-500 hover:text-brand-600 text-black font-semibold rounded-xl shadow-sm transition-all text-xs" type="button">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Detailed Results
                                                </button>
                                                <button @click="generateCertificate(result)" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl shadow-sm transition-all text-xs" type="button">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Download Certificate
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                        <tbody x-show="filteredResults.length === 0" x-cloak>
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-black italic">No results found for your search criteria.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4 flex justify-between items-center px-4" x-show="activeTab !== 'podium'">
                <p class="text-[9px] uppercase font-bold text-black tracking-widest">
                    Showing <span x-text="filteredResults.length"></span> runners in <span x-text="activeTab === 'all' ? 'All Categories' : activeTab + 'K'"></span>
                </p>
                <p class="text-[9px] text-black italic">Official Data Static Cache</p>
            </div>

            <!-- Podium Showcase Inside Tab -->
            @if($podiums->isNotEmpty())
            <div x-show="activeTab === 'podium'" x-cloak class="space-y-16 py-8">
                <div class="text-center mb-12">
                    <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-500 text-xs font-bold rounded-full mb-4 tracking-widest uppercase">The Champions</span>
                    <h2 class="font-display font-bold text-3xl text-black">Podium Winners</h2>
                </div>

                <div class="space-y-16">
                    @foreach($podiums as $distance => $ageCategories)
                        <div class="distance-group">
                            <h3 class="text-2xl font-display font-bold text-brand-600 mb-8 flex items-center gap-3">
                                <span class="w-8 h-8 bg-brand-500 text-white rounded-lg flex items-center justify-center text-sm italic">{{ (int)$distance }}K</span>
                                Distance Winners
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach($ageCategories as $ageCat => $genders)
                                    @foreach($genders as $gender => $winners)
                                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-surface-200">
                                            <div class="flex justify-between items-center mb-6">
                                                <h4 class="font-bold text-black uppercase tracking-wide">{{ $ageCat }} {{ $gender }}</h4>
                                                <span class="text-[10px] px-2 py-0.5 bg-surface-100 text-black rounded font-bold uppercase">{{ (int)$distance }}K</span>
                                            </div>
                                            <div class="space-y-4">
                                                @foreach($winners as $winner)
                                                    <div class="flex items-center gap-4 p-3 rounded-xl {{ $winner->rank_group == 1 ? 'bg-amber-50 border border-amber-100' : 'bg-surface-50' }}">
                                                        <div class="w-10 h-10 shrink-0 flex items-center justify-center font-display font-bold text-xl
                                                            {{ $winner->rank_group == 1 ? 'text-amber-500' : ($winner->rank_group == 2 ? 'text-black' : 'text-orange-400') }}">
                                                            {{ $winner->rank_group }}
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-bold text-black truncate uppercase">{{ $winner->participant->full_name }}</div>
                                                            <div class="text-xs text-black font-mono">BIB: {{ $winner->bib_number }}</div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="font-mono font-bold text-brand-600">{{ $winner->gun_time }}</div>
                                                            <div class="text-[10px] text-black uppercase">Finish Time</div>
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
            @endif
        </div>

        <!-- Detailed Results Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-surface-200"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                    
                    <div class="bg-gradient-to-r from-brand-800 to-brand-950 px-6 py-5 text-white flex justify-between items-center">
                        <div>
                            <h3 class="font-display font-bold text-lg">Detailed Race Results</h3>
                            <p class="text-[10px] text-white" x-text="selectedResult ? 'BIB #' + selectedResult.bib_number : ''"></p>
                        </div>
                        <button @click="showModal = false" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6" x-show="selectedResult">
                        <div class="flex items-center gap-4 border-b border-surface-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-display font-black text-sm"
                                 x-text="selectedResult && selectedResult.participant ? selectedResult.participant.full_name.charAt(0).toUpperCase() : 'R'">
                            </div>
                            <div>
                                <h4 class="font-bold text-black text-sm uppercase" x-text="selectedResult && selectedResult.participant ? selectedResult.participant.full_name : ''"></h4>
                                <p class="text-[10px] text-black font-mono" x-text="selectedResult && selectedResult.participant && selectedResult.participant.community ? 'Club: ' + selectedResult.participant.community : 'No Club / Community'"></p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-surface-50 p-3 rounded-2xl border border-surface-200/60">
                                <span class="text-[9px] font-bold text-black uppercase tracking-wider block mb-0.5">Distance Category</span>
                                <span class="font-display font-black text-lg text-black" x-text="selectedResult ? (parseFloat(selectedResult.distance_km) === 5 ? '5K Family' : parseFloat(selectedResult.distance_km) + 'K') : '-'"></span>
                            </div>
                            <div class="bg-surface-50 p-3 rounded-2xl border border-surface-200/60">
                                <span class="text-[9px] font-bold text-black uppercase tracking-wider block mb-0.5">Age Group / Gender</span>
                                <span class="font-display font-black text-lg text-black" x-text="selectedResult ? capitalize(selectedResult.age_category) + ' (' + (selectedResult.gender === 'male' ? 'M' : 'F') + ')' : '-'"></span>
                            </div>
                            <div class="bg-surface-50 p-3 rounded-2xl border border-surface-200/60 col-span-2">
                                <span class="text-[9px] font-bold text-black uppercase tracking-wider block mb-0.5">Finish Time</span>
                                <span class="font-mono font-bold text-lg text-brand-600" x-text="selectedResult ? selectedResult.gun_time || '-' : '-'"></span>
                            </div>
                        </div>
                        
                        <div class="border-t border-surface-100 pt-4 space-y-2">
                            <h5 class="text-[9px] font-bold text-black uppercase tracking-wider mb-1.5">Rankings & Standing</h5>
                            <div class="flex justify-between items-center text-xs py-1 border-b border-surface-50">
                                <span class="text-black" x-text="selectedResult ? 'Distance Position (' + (parseFloat(selectedResult.distance_km) === 5 ? '5K Family' : parseFloat(selectedResult.distance_km) + 'K') + ')' : 'Distance Position'"></span>
                                <span class="font-mono font-bold text-black" x-text="selectedResult && selectedResult.rank_category ? '#' + selectedResult.rank_category : '-'"></span>
                            </div>
                            <div class="flex justify-between items-center text-xs py-1 border-b border-surface-50">
                                <span class="text-black" x-text="selectedResult ? 'Category Position (' + (parseFloat(selectedResult.distance_km) === 5 ? '5K' : parseFloat(selectedResult.distance_km) + 'K') + ' ' + capitalize(selectedResult.age_category) + ' ' + capitalize(selectedResult.gender) + ')' : 'Category Position'"></span>
                                <span class="font-mono font-bold text-black" x-text="selectedResult && selectedResult.rank_group ? '#' + selectedResult.rank_group + '/' + getGroupTotalCount(selectedResult) : '-'"></span>
                            </div>
                            <div class="flex justify-between items-center text-xs py-1 border-b border-surface-50">
                                <span class="text-black" x-text="selectedResult ? 'Gender Position (' + (parseFloat(selectedResult.distance_km) === 5 ? '5K' : parseFloat(selectedResult.distance_km) + 'K') + ' ' + capitalize(selectedResult.gender) + ')' : 'Gender Position'"></span>
                                <span class="font-mono font-bold text-black" x-text="selectedResult && selectedResult.gender_pos ? '#' + selectedResult.gender_pos + '/' + getGenderTotalCount(selectedResult) : '-'"></span>
                            </div>
                            <div class="flex justify-between items-center text-xs py-1">
                                <span class="text-black" x-text="selectedResult ? 'Overall Event Position (' + (parseFloat(selectedResult.distance_km) === 5 ? '5K Family' : parseFloat(selectedResult.distance_km) + 'K') + ')' : 'Overall Event Position'"></span>
                                <span class="font-mono font-bold text-black" x-text="selectedResult && selectedResult.rank_overall ? '#' + selectedResult.rank_overall + '/' + allData[parseInt(selectedResult.distance_km)].length : '-'"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-surface-50 px-6 py-4 flex justify-end gap-2 border-t border-surface-150">
                        <button @click="showModal = false" class="px-4 py-2 bg-white border border-surface-300 hover:bg-surface-50 text-black font-semibold rounded-xl text-xs transition-colors">
                            Close
                        </button>
                        <button @click="showModal = false; generateCertificate(selectedResult)" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs transition-colors shadow-sm">
                            Download Certificate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Preview Modal -->
        <div x-show="showCertModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" @click="showCertModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl border border-surface-200"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                    
                    <div class="bg-gradient-to-r from-brand-800 to-brand-950 px-6 py-5 text-white flex justify-between items-center">
                        <div>
                            <h3 class="font-display font-bold text-lg">Official Finisher Certificate</h3>
                            <p class="text-[10px] text-white">Preview your official event certificate</p>
                        </div>
                        <button @click="showCertModal = false" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6 bg-surface-100 flex justify-center">
                        <!-- CSS Mockup of the Certificate -->
                        <div id="cert-mockup" class="w-full aspect-[1.414/1] max-w-xl bg-white border-[8px] border-brand-600 p-5 relative flex flex-col justify-between text-center select-none shadow-lg rounded-sm" style="background-image: radial-gradient(circle, #ffffff 0%, #fffafa 100%);">
                            <div class="absolute inset-1.5 border border-amber-500 pointer-events-none"></div>
                            
                            <div class="mt-2">
                                <h4 class="text-black font-display font-black text-sm tracking-wider">ERAFONE TRAIL RUN 2026</h4>
                                <p class="text-brand-600 font-bold text-[8px] tracking-widest uppercase">Official Finisher Certificate</p>
                            </div>
                            
                            <div class="my-2">
                                <h3 class="text-amber-500 font-serif italic font-bold text-xl">Certificate of Achievement</h3>
                                <p class="text-black font-serif italic text-[10px] mt-0.5">This is proudly presented to</p>
                            </div>
                            
                            <div class="my-1">
                                <h2 class="text-black font-bold text-lg tracking-wide uppercase border-b border-amber-500 inline-block px-6 pb-0.5" x-text="certResult && certResult.participant ? certResult.participant.full_name : '-'"></h2>
                            </div>
                            
                            <p class="text-black text-[10px] px-6" x-text="'for successfully completing the Erafone Trail Run 2026 in the ' + (certResult ? certResult.distance_km : '-') + 'K Category'"></p>
                            
                            <div class="grid grid-cols-4 bg-surface-50 border border-surface-200 py-2 mt-3 text-center divide-x divide-surface-200">
                                <div>
                                    <span class="text-[7px] text-black uppercase font-bold block">BIB Number</span>
                                    <span class="text-xs font-bold text-black" x-text="certResult ? certResult.bib_number : '-'"></span>
                                </div>
                                <div>
                                    <span class="text-[7px] text-black uppercase font-bold block">Category Rank</span>
                                    <span class="text-xs font-bold text-black" x-text="certResult && certResult.rank_category ? '#' + certResult.rank_category : '-'"></span>
                                </div>
                                <div>
                                    <span class="text-[7px] text-black uppercase font-bold block">Net Time</span>
                                    <span class="text-xs font-bold text-black" x-text="certResult ? certResult.net_time || certResult.gun_time || '-' : '-'"></span>
                                </div>
                                <div>
                                    <span class="text-[7px] text-black uppercase font-bold block">Avg Pace</span>
                                    <span class="text-xs font-bold text-black" x-text="certResult ? certResult.pace : '-'"></span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-end mt-3 text-left px-2">
                                <div>
                                    <p class="text-[8px] text-black font-mono">Date: 28 June 2026</p>
                                    <p class="text-[8px] text-black">Jakarta, Indonesia</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 border-b border-surface-300 mb-0.5"></div>
                                    <p class="text-[7px] text-black">Race Director</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-surface-50 px-6 py-4 flex justify-between items-center border-t border-surface-150">
                        <p class="text-[9px] text-black">Download your certificate in PNG or high-quality PDF format</p>
                        <div class="flex gap-2">
                            <button @click="showCertModal = false" class="px-4 py-2 bg-white border border-surface-300 hover:bg-surface-50 text-black font-semibold rounded-xl text-xs transition-colors">
                                Close
                            </button>
                            <button @click="downloadCertificate(certResult)" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs transition-colors shadow-sm flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PNG
                            </button>
                            <a :href="getCertificatePdfUrl(certResult)" download class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-xs transition-colors shadow-sm flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
