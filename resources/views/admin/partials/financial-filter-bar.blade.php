@props([
    'dateRange',
    'route' => request()->url(),
    'exportRoute' => route('admin.cash_transactions.export'),
    'showExport' => true,
    'showDimensions' => false,
    'allCategories' => null,
    'cashAccounts' => null,
    'selectedType' => null,
    'selectedCategory' => null,
    'selectedAccount' => null,
])

<div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/60 dark:border-zinc-800/60 shadow-xs"
     x-data="{
        showCustom: {{ $dateRange['period'] === 'custom' ? 'true' : 'false' }},
        showMonthYear: {{ $dateRange['period'] === 'specific_month' ? 'true' : 'false' }},
        showMoreFilters: {{ (request()->filled('type') || request()->filled('category_id') || request()->filled('account_id')) ? 'true' : 'false' }},
        selectedPeriod: '{{ $dateRange['period'] }}',
        startDate: '{{ $dateRange['start_date'] ?? '' }}',
        endDate: '{{ $dateRange['end_date'] ?? '' }}',
        selectedMonth: '{{ $dateRange['month'] ?? date('n') }}',
        selectedYear: '{{ $dateRange['year'] ?? date('Y') }}',
        filterType: '{{ $selectedType ?? request('type', '') }}',
        filterCategory: '{{ $selectedCategory ?? request('category_id', '') }}',
        filterAccount: '{{ $selectedAccount ?? request('account_id', '') }}',
        months: [
            { id: 1, name: 'Januari' },
            { id: 2, name: 'Februari' },
            { id: 3, name: 'Maret' },
            { id: 4, name: 'April' },
            { id: 5, name: 'Mei' },
            { id: 6, name: 'Juni' },
            { id: 7, name: 'Juli' },
            { id: 8, name: 'Agustus' },
            { id: 9, name: 'September' },
            { id: 10, name: 'Oktober' },
            { id: 11, name: 'November' },
            { id: 12, name: 'Desember' }
        ],
        years: [{{ implode(',', range(date('Y') - 3, date('Y') + 1)) }}],
        buildUrl(paramsObj) {
            let params = new URLSearchParams(window.location.search);
            for (const [k, v] of Object.entries(paramsObj)) {
                if (v !== null && v !== undefined && v !== '') {
                    params.set(k, v);
                } else {
                    params.delete(k);
                }
            }
            return '{{ $route }}' + (params.toString() ? '?' + params.toString() : '');
        },
        applyPreset(p) {
            let params = new URLSearchParams(window.location.search);
            params.set('period', p);
            params.delete('month');
            params.delete('year');
            params.delete('start_date');
            params.delete('end_date');
            window.location.href = '{{ $route }}?' + params.toString();
        },
        applyMonthYear() {
            let params = new URLSearchParams(window.location.search);
            params.set('month', this.selectedMonth);
            params.set('year', this.selectedYear);
            params.delete('period');
            params.delete('start_date');
            params.delete('end_date');
            window.location.href = '{{ $route }}?' + params.toString();
        },
        applyCustomDate() {
            if (this.startDate && this.endDate) {
                let params = new URLSearchParams(window.location.search);
                params.set('start_date', this.startDate);
                params.set('end_date', this.endDate);
                params.delete('period');
                params.delete('month');
                params.delete('year');
                window.location.href = '{{ $route }}?' + params.toString();
            }
        },
        applyExtraFilters() {
            let params = new URLSearchParams(window.location.search);
            if (this.filterType) params.set('type', this.filterType); else params.delete('type');
            if (this.filterCategory) params.set('category_id', this.filterCategory); else params.delete('category_id');
            if (this.filterAccount) params.set('account_id', this.filterAccount); else params.delete('account_id');
            window.location.href = '{{ $route }}' + (params.toString() ? '?' + params.toString() : '');
        },
        getExportUrl() {
            let params = new URLSearchParams(window.location.search);
            return '{{ $exportRoute }}' + (params.toString() ? '?' + params.toString() : '?period=this_month');
        }
     }">
    
    <!-- 1. MOBILE VIEW LAYOUT (100% visible grid, no hidden horizontal swipe) -->
    <div class="block lg:hidden space-y-2.5">
        <!-- Row 1: Active Period Badge & Action Buttons -->
        <div class="flex items-center justify-between gap-2">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-[11px] font-semibold text-zinc-800 dark:text-zinc-200">
                <svg class="w-3.5 h-3.5 text-zinc-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="truncate">Periode: <strong class="text-indigo-600 dark:text-indigo-400">{{ $dateRange['label'] }}</strong></span>
            </div>

            <div class="flex items-center gap-1.5">
                @if($dateRange['period'] !== 'this_month' || request()->has('start_date') || request()->has('month') || request()->filled('type') || request()->filled('category_id') || request()->filled('account_id'))
                    <a href="{{ $route }}" class="px-2 py-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Reset</span>
                    </a>
                @endif

                @if($showExport)
                <a :href="getExportUrl()" 
                   class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold rounded-xl text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition shadow-2xs gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Excel</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Row 2: Quick Presets Grid (All options clearly visible at a glance) -->
        <div class="grid grid-cols-3 gap-1 p-1 bg-zinc-100 dark:bg-zinc-800/80 rounded-xl text-[11px]">
            <button type="button" @click="applyPreset('this_month')"
                    :class="selectedPeriod === 'this_month' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="py-1.5 rounded-lg transition-all text-center">
                Bulan Ini
            </button>
            <button type="button" @click="applyPreset('last_month')"
                    :class="selectedPeriod === 'last_month' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="py-1.5 rounded-lg transition-all text-center">
                Bulan Lalu
            </button>
            <button type="button" @click="applyPreset('1_week')"
                    :class="selectedPeriod === '1_week' || selectedPeriod === '7_days' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="py-1.5 rounded-lg transition-all text-center">
                7 Hari
            </button>
            <button type="button" @click="applyPreset('30_days')"
                    :class="selectedPeriod === '30_days' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="py-1.5 rounded-lg transition-all text-center">
                30 Hari
            </button>
            <button type="button" @click="applyPreset('this_year')"
                    :class="selectedPeriod === 'this_year' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="py-1.5 rounded-lg transition-all text-center col-span-2">
                Tahun Ini
            </button>
        </div>

        <!-- Row 3: Action Buttons (Pilih Bulan, Rentang Tanggal, Filter Detail) -->
        <div class="grid grid-cols-2 {{ $showDimensions ? 'grid-cols-2' : 'grid-cols-2' }} gap-1.5">
            <button type="button" 
                    @click="showMonthYear = !showMonthYear; showCustom = false; showMoreFilters = false;"
                    :class="showMonthYear || selectedPeriod === 'specific_month' ? 'bg-indigo-50 border-indigo-300 text-indigo-700 dark:bg-indigo-950/60 dark:border-indigo-800 dark:text-indigo-300 font-bold' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700'"
                    class="px-2.5 py-1.5 text-[11px] font-semibold rounded-xl border transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Pilih Bulan</span>
            </button>

            <button type="button" 
                    @click="showCustom = !showCustom; showMonthYear = false; showMoreFilters = false;"
                    :class="showCustom || selectedPeriod === 'custom' ? 'bg-indigo-50 border-indigo-300 text-indigo-700 dark:bg-indigo-950/60 dark:border-indigo-800 dark:text-indigo-300 font-bold' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700'"
                    class="px-2.5 py-1.5 text-[11px] font-semibold rounded-xl border transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Rentang Hari</span>
            </button>

            @if($showDimensions)
            <button type="button" 
                    @click="showMoreFilters = !showMoreFilters; showCustom = false; showMonthYear = false;"
                    :class="showMoreFilters || filterType || filterCategory || filterAccount ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700'"
                    class="col-span-2 px-2.5 py-1.5 text-[11px] font-semibold rounded-xl border transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Filter Kategori & Akun</span>
                @if(request()->filled('type') || request()->filled('category_id') || request()->filled('account_id'))
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                @endif
            </button>
            @endif
        </div>
    </div>

    <!-- 2. DESKTOP VIEW LAYOUT (Inline seamless bar) -->
    <div class="hidden lg:flex items-center justify-between gap-3">
        <!-- Left: Active Period Label -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-xs font-semibold text-zinc-800 dark:text-zinc-200">
                <svg class="w-3.5 h-3.5 text-zinc-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="truncate">Periode: <strong class="text-indigo-600 dark:text-indigo-400">{{ $dateRange['label'] }}</strong></span>
            </div>

            @if($dateRange['period'] !== 'this_month' || request()->has('start_date') || request()->has('month') || request()->filled('type') || request()->filled('category_id') || request()->filled('account_id'))
                <a href="{{ $route }}" class="px-2.5 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Reset</span>
                </a>
            @endif
        </div>

        <!-- Center: Quick Presets Buttons & Picker Toggles -->
        <div class="flex items-center gap-2">
            <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-800/80 rounded-xl text-xs">
                <button type="button" @click="applyPreset('this_month')"
                        :class="selectedPeriod === 'this_month' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                    Bulan Ini
                </button>
                <button type="button" @click="applyPreset('last_month')"
                        :class="selectedPeriod === 'last_month' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                    Bulan Lalu
                </button>
                <button type="button" @click="applyPreset('1_week')"
                        :class="selectedPeriod === '1_week' || selectedPeriod === '7_days' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                    7 Hari
                </button>
                <button type="button" @click="applyPreset('30_days')"
                        :class="selectedPeriod === '30_days' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                    30 Hari
                </button>
                <button type="button" @click="applyPreset('this_year')"
                        :class="selectedPeriod === 'this_year' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                    Tahun Ini
                </button>
            </div>

            <!-- Toggle Specific Month/Year Picker -->
            <button type="button" 
                    @click="showMonthYear = !showMonthYear; showCustom = false;"
                    :class="showMonthYear || selectedPeriod === 'specific_month' ? 'bg-indigo-50 border-indigo-300 text-indigo-700 dark:bg-indigo-950/60 dark:border-indigo-800 dark:text-indigo-300' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 shadow-xs whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                Pilih Bulan
            </button>

            <!-- Toggle Custom Range -->
            <button type="button" 
                    @click="showCustom = !showCustom; showMonthYear = false; showMoreFilters = false;"
                    :class="showCustom || selectedPeriod === 'custom' ? 'bg-indigo-50 border-indigo-300 text-indigo-700 dark:bg-indigo-950/60 dark:border-indigo-800 dark:text-indigo-300' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 shadow-xs whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Rentang
            </button>

            @if($showDimensions)
            <!-- Toggle Dimension Filters (Tipe / Kategori / Dompet) -->
            <button type="button" 
                    @click="showMoreFilters = !showMoreFilters; showCustom = false; showMonthYear = false;"
                    :class="showMoreFilters || filterType || filterCategory || filterAccount ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 shadow-xs whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Filter Detail</span>
                @if(request()->filled('type') || request()->filled('category_id') || request()->filled('account_id'))
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                @endif
            </button>
            @endif
        </div>

        <!-- Right: Desktop Export Excel Button -->
        @if($showExport)
        <div class="flex items-center gap-2">
            <a :href="getExportUrl()" 
               class="inline-flex items-center px-3.5 py-2 text-xs font-bold rounded-xl text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition shadow-xs gap-1.5 whitespace-nowrap">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel (.xlsx)
            </a>
        </div>
        @endif
    </div>

    <!-- Collapsible Month & Year Selector -->
    <div x-show="showMonthYear" x-cloak x-transition class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-end">
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Pilih Bulan:</label>
                <select x-model="selectedMonth" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <template x-for="m in months" :key="m.id">
                        <option :value="m.id" x-text="m.name" :selected="selectedMonth == m.id"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Tahun:</label>
                <select x-model="selectedYear" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y" :selected="selectedYear == y"></option>
                    </template>
                </select>
            </div>

            <div>
                <button type="button" @click="applyMonthYear()" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                    Tampilkan Bulan
                </button>
            </div>
        </div>
    </div>

    <!-- Collapsible Custom Date Range Form -->
    <div x-show="showCustom" x-cloak x-transition class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-end">
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Mulai Tanggal:</label>
                <input type="date" x-model="startDate" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white" />
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Sampai Tanggal:</label>
                <input type="date" x-model="endDate" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white" />
            </div>

            <div>
                <button type="button" @click="applyCustomDate()" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                    Terapkan Rentang
                </button>
            </div>
        </div>
    </div>

    @if($showDimensions)
    <!-- Collapsible Extra Dimension Filters (Tipe, Kategori, Dompet) -->
    <div x-show="showMoreFilters" x-cloak x-transition class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5 items-end">
            <!-- Tipe -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Tipe Transaksi:</label>
                <select x-model="filterType" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua Tipe</option>
                    <option value="expense">🔴 Pengeluaran</option>
                    <option value="income">🟢 Pemasukan</option>
                    <option value="transfer">🔄 Transfer / Pindah Kas</option>
                </select>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Kategori:</label>
                <select x-model="filterCategory" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @if($allCategories)
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->type === 'income' ? '🟢' : '🔴' }} {{ $cat->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Dompet / Akun -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Dompet / Rekening:</label>
                <select x-model="filterAccount" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua Dompet</option>
                    @if($cashAccounts)
                        @foreach($cashAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->type === 'bank' ? '🏦' : ($acc->type === 'cash' ? '💵' : '📱') }} {{ $acc->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Action Button -->
            <div class="flex items-center gap-1.5">
                <button type="button" @click="applyExtraFilters()" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                    Terapkan
                </button>
                @if(request()->filled('type') || request()->filled('category_id') || request()->filled('account_id'))
                    <button type="button" @click="filterType = ''; filterCategory = ''; filterAccount = ''; applyExtraFilters();" class="px-2.5 py-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl text-xs font-semibold transition">
                        Reset
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
