@extends('admin.layouts.app')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="budgetManager()" class="space-y-4 sm:space-y-5 pb-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <nav class="flex text-[11px] text-zinc-500 dark:text-zinc-400 font-medium mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard', ['view' => 'personal']) }}" class="hover:text-emerald-600 transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3.5 h-3.5 text-zinc-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            <span class="text-zinc-800 dark:text-zinc-200 font-semibold">Target Anggaran</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">
                    Perencanaan & Target Anggaran
                </h1>
                <span class="text-[10px] sm:text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300 font-bold border border-emerald-200/60 dark:border-emerald-800/60">
                    {{ $periodLabel }}
                </span>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Tentukan batas limit belanja per kategori agar pengeluaran bulanan terkontrol secara disiplin.</p>
        </div>

        <!-- Month & Year Period Switcher -->
        <div class="flex items-center gap-1.5 self-start sm:self-auto flex-wrap">
            <a href="{{ route('admin.category_budgets.index', ['month' => $prevDate->format('n'), 'year' => $prevDate->format('Y')]) }}"
               class="p-2 text-zinc-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-xl border border-zinc-200/80 dark:border-zinc-700 shadow-2xs transition" title="Bulan Sebelumnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>

            <!-- Form Filter Dropdown -->
            <form action="{{ route('admin.category_budgets.index') }}" method="GET" class="flex items-center gap-1.5">
                <select name="month" onchange="this.form.submit()" class="text-xs font-semibold px-2.5 py-2 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 border border-zinc-200/80 dark:border-zinc-700 shadow-2xs focus:ring-1 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                    @foreach($monthList as $mNum => $mName)
                        <option value="{{ $mNum }}" {{ $mNum == $month ? 'selected' : '' }}>{{ $mName }}</option>
                    @endforeach
                </select>

                <select name="year" onchange="this.form.submit()" class="text-xs font-semibold px-2.5 py-2 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 border border-zinc-200/80 dark:border-zinc-700 shadow-2xs focus:ring-1 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                    @foreach($yearList as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('admin.category_budgets.index', ['month' => $nextDate->format('n'), 'year' => $nextDate->format('Y')]) }}"
               class="p-2 text-zinc-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-xl border border-zinc-200/80 dark:border-zinc-700 shadow-2xs transition" title="Bulan Berikutnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- 4 Summary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5">
        <!-- Card 1: Total Target Anggaran -->
        <div class="p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Anggaran</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="text-base sm:text-lg font-extrabold text-zinc-900 dark:text-white truncate" x-text="formatRupiah(totalBudget)">
                Rp {{ number_format($totalBudget, 0, ',', '.') }}
            </div>
            <p class="text-[10px] text-zinc-400 mt-0.5">Plafon total belanja bulan ini</p>
        </div>

        <!-- Card 2: Realisasi Pengeluaran -->
        <div class="p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Realisasi Belanja</span>
                <div class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="text-base sm:text-lg font-extrabold text-rose-600 dark:text-rose-400 truncate">
                Rp {{ number_format($totalSpent, 0, ',', '.') }}
            </div>
            <p class="text-[10px] text-zinc-400 mt-0.5">Total mutasi belanja kas keluar</p>
        </div>

        <!-- Card 3: Sisa Anggaran -->
        <div class="p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Sisa Anggaran</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-base sm:text-lg font-extrabold truncate" :class="remainingBudget >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'" x-text="formatRupiah(remainingBudget)">
                Rp {{ number_format($remainingBudget, 0, ',', '.') }}
            </div>
            <p class="text-[10px] text-zinc-400 mt-0.5 truncate" x-text="totalBudget > 0 ? (remainingBudget >= 0 ? 'Dana aman untuk belanja' : 'Melebihi total rencana!') : 'Belum pasang limit'"></p>
        </div>

        <!-- Card 4: Status Penggunaan -->
        <div class="p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Penggunaan</span>
                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-md"
                      :class="overallPercentage >= 100 ? 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300' : (overallPercentage >= 80 ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300')">
                    <span x-text="overallPercentage >= 100 ? 'Over Budget' : (overallPercentage >= 80 ? 'Waspada' : 'Aman')"></span>
                </span>
            </div>
            <div class="text-base sm:text-lg font-extrabold text-zinc-900 dark:text-white flex items-baseline gap-1">
                <span x-text="overallPercentage + '%'">{{ $overallPercentage }}%</span>
                <span class="text-[10px] font-normal text-zinc-400">terpakai</span>
            </div>
            <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden mt-2">
                <div class="h-full rounded-full transition-all duration-500"
                     :class="overallPercentage >= 100 ? 'bg-rose-500' : (overallPercentage >= 80 ? 'bg-amber-500' : 'bg-emerald-500')"
                     :style="'width: ' + Math.min(100, overallPercentage) + '%'"></div>
            </div>
        </div>
    </div>

    <!-- Category Budget Management Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
        <!-- Card Header & Actions Toolbar -->
        <div class="p-3.5 sm:p-4 border-b border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 bg-zinc-50/50 dark:bg-zinc-800/30">
            <div>
                <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Daftar Kategori & Batas Limit Anggaran</span>
                </h2>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">Ketik nilai limit atau gunakan tombol preset cepat (nilai 0 = tanpa limit).</p>
            </div>

            <div class="flex items-center gap-1.5 flex-wrap">
                <!-- Copy from previous month button -->
                <button type="button" @click="copyPreviousMonth()" :disabled="copying"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg sm:rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 transition shadow-2xs cursor-pointer disabled:opacity-50">
                    <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                    <span class="text-[11px] sm:text-xs" x-text="copying ? 'Menyalin...' : 'Salin Bulan Lalu'">Salin Bulan Lalu</span>
                </button>

                <!-- Reset all to 0 button -->
                <button type="button" @click="resetAll()"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg sm:rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 transition shadow-2xs cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span class="text-[11px] sm:text-xs">Reset 0</span>
                </button>

                <!-- Save All Changes button -->
                <button type="button" @click="saveAll()" :disabled="saving"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-lg sm:rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white transition shadow-sm shadow-emerald-500/20 cursor-pointer disabled:opacity-50">
                    <svg x-show="saving" class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <svg x-show="!saving" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-[11px] sm:text-xs" x-text="saving ? 'Menyimpan...' : 'Simpan Semua'">Simpan Semua</span>
                </button>
            </div>
        </div>

        <!-- Categories List Grid / Cards -->
        <div class="p-3 sm:p-4 space-y-3">
            @if(count($categoryItems) === 0)
                <div class="p-6 text-center bg-zinc-50 dark:bg-zinc-800/40 rounded-xl sm:rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-700">
                    <div class="w-10 h-10 rounded-xl bg-zinc-100 dark:bg-zinc-700 text-zinc-400 flex items-center justify-center mx-auto mb-2.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Belum Ada Kategori Pengeluaran</h3>
                    <p class="text-[11px] text-zinc-500 mt-1 mb-3">Tambahkan kategori pengeluaran terlebih dahulu untuk mengatur batas limit anggaran.</p>
                    <a href="{{ route('admin.transaction_categories.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition">
                        + Tambah Kategori
                    </a>
                </div>
            @else
                <template x-for="(item, index) in items" :key="item.id">
                    <div class="p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border transition-all duration-200 bg-white dark:bg-zinc-900 hover:border-zinc-300 dark:hover:border-zinc-700 shadow-2xs"
                         :class="{
                             'border-rose-300 dark:border-rose-900/60 bg-rose-50/30 dark:bg-rose-950/10': item.limit > 0 && (item.spent / item.limit) >= 1,
                             'border-amber-300 dark:border-amber-900/60 bg-amber-50/30 dark:bg-amber-950/10': item.limit > 0 && (item.spent / item.limit) >= 0.8 && (item.spent / item.limit) < 1,
                             'border-zinc-200/80 dark:border-zinc-800': item.limit === 0 || (item.spent / item.limit) < 0.8
                         }">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
                            <!-- Category Info -->
                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0 text-zinc-700 dark:text-zinc-200">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white truncate" x-text="item.name"></h3>
                                        <!-- Status Pill -->
                                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded"
                                              :class="{
                                                  'bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50': item.limit > 0 && (item.spent / item.limit) >= 1,
                                                  'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50': item.limit > 0 && (item.spent / item.limit) >= 0.8 && (item.spent / item.limit) < 1,
                                                  'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50': item.limit > 0 && (item.spent / item.limit) < 0.8,
                                                  'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700': item.limit === 0
                                              }"
                                              x-text="getStatusLabel(item)">
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                                        <span>Terpakai: <strong class="text-zinc-800 dark:text-zinc-200" x-text="formatRupiah(item.spent)"></strong></span>
                                        <span>•</span>
                                        <span x-text="item.limit > 0 ? ('Sisa: ' + formatRupiah(Math.max(0, item.limit - item.spent))) : 'Tanpa limit'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Input & Quick Preset Buttons (Mobile-Optimized) -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-shrink-0 w-full sm:w-auto">
                                <!-- Currency Input with Clear Button -->
                                <div class="w-full sm:w-44 relative flex-shrink-0">
                                    <span class="absolute left-3 top-2 text-xs text-zinc-400 font-bold select-none">Rp</span>
                                    <input type="text"
                                           inputmode="numeric"
                                           :value="formatInput(item.limit)"
                                           @input="handleInput(item, $event)"
                                           placeholder="0 (Tanpa limit)"
                                           class="w-full text-xs font-bold pl-9 pr-7 py-1.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition">
                                    <button type="button" 
                                            x-show="item.limit > 0" 
                                            @click="item.limit = 0" 
                                            class="absolute right-2.5 top-2 text-zinc-400 hover:text-rose-500 text-xs font-bold transition-colors cursor-pointer" 
                                            title="Reset ke 0">✕</button>
                                </div>

                                <!-- Quick Presets Increment Chips -->
                                <div class="flex items-center gap-1 overflow-x-auto pb-0.5 sm:pb-0 scrollbar-none flex-wrap">
                                    <button type="button" @click="addAmount(item, 100000)" class="px-2 py-1 text-[10px] font-bold rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:scale-95 transition cursor-pointer">+100rb</button>
                                    <button type="button" @click="addAmount(item, 250000)" class="px-2 py-1 text-[10px] font-bold rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:scale-95 transition cursor-pointer">+250rb</button>
                                    <button type="button" @click="addAmount(item, 500000)" class="px-2 py-1 text-[10px] font-bold rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:scale-95 transition cursor-pointer">+500rb</button>
                                    <button type="button" @click="addAmount(item, 1000000)" class="px-2 py-1 text-[10px] font-bold rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:scale-95 transition cursor-pointer">+1jt</button>
                                    <button type="button" @click="addAmount(item, 2000000)" class="px-2 py-1 text-[10px] font-bold rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:scale-95 transition cursor-pointer">+2jt</button>
                                    <button type="button" @click="item.limit = 0" class="px-2 py-1 text-[10px] font-bold rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 active:scale-95 transition cursor-pointer">0</button>
                                </div>
                            </div>
                        </div>

                        <!-- Progress bar per category (if limit > 0) -->
                        <div x-show="item.limit > 0" class="mt-2.5 pt-2 border-t border-zinc-100 dark:border-zinc-800/80">
                            <div class="flex items-center justify-between text-[10px] mb-1">
                                <span class="text-zinc-500 dark:text-zinc-400">Realisasi Plafon: <strong class="text-zinc-800 dark:text-zinc-200" x-text="Math.round((item.spent / item.limit) * 100) + '%'"></strong></span>
                                <span class="font-bold"
                                      :class="{
                                          'text-rose-600 dark:text-rose-400': (item.spent / item.limit) >= 1,
                                          'text-amber-600 dark:text-amber-400': (item.spent / item.limit) >= 0.8 && (item.spent / item.limit) < 1,
                                          'text-emerald-600 dark:text-emerald-400': (item.spent / item.limit) < 0.8
                                      }"
                                      x-text="(item.spent / item.limit) >= 1 ? ('Lebih +' + formatRupiah(item.spent - item.limit)) : ('Tersisa ' + formatRupiah(item.limit - item.spent))">
                                </span>
                            </div>
                            <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-300"
                                     :class="{
                                         'bg-rose-500': (item.spent / item.limit) >= 1,
                                         'bg-amber-500': (item.spent / item.limit) >= 0.8 && (item.spent / item.limit) < 1,
                                         'bg-emerald-500': (item.spent / item.limit) < 0.8
                                     }"
                                     :style="'width: ' + Math.min(100, Math.round((item.spent / item.limit) * 100)) + '%'">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            @endif
        </div>

        <!-- Sticky / Bottom Action Bar -->
        <div class="p-3.5 sm:p-4 border-t border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 bg-zinc-50/50 dark:bg-zinc-800/40">
            <span class="text-[11px] text-zinc-500 dark:text-zinc-400">
                <span class="font-bold text-zinc-900 dark:text-white" x-text="items.filter(i => i.limit > 0).length"></span> dari <span x-text="items.length"></span> kategori telah memiliki target limit.
            </span>

            <button type="button" @click="saveAll()" :disabled="saving"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white transition shadow-md shadow-emerald-500/20 cursor-pointer disabled:opacity-50">
                <svg x-show="saving" class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <svg x-show="!saving" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span x-text="saving ? 'Menyimpan Perubahan...' : 'Simpan Semua Target Anggaran'">Simpan Semua Target Anggaran</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function budgetManager() {
    return {
        month: {{ $month }},
        year: {{ $year }},
        items: @json($categoryItems),
        saving: false,
        copying: false,

        get totalBudget() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.limit) || 0), 0);
        },

        get totalSpent() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.spent) || 0), 0);
        },

        get remainingBudget() {
            return Math.max(0, this.totalBudget - this.totalSpent);
        },

        get overallPercentage() {
            if (this.totalBudget <= 0) return 0;
            return Math.round((this.totalSpent / this.totalBudget) * 100);
        },

        formatRupiah(num) {
            num = Math.round(num || 0);
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },

        formatInput(val) {
            if (!val || val <= 0) return '';
            return Math.round(val).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },

        handleInput(item, e) {
            let raw = e.target.value.replace(/[^0-9]/g, '');
            let num = raw === '' ? 0 : parseFloat(raw);
            item.limit = num;
            e.target.value = this.formatInput(num);
        },

        addAmount(item, val) {
            item.limit = (parseFloat(item.limit) || 0) + val;
        },

        setAmount(item, val) {
            item.limit = val;
        },

        resetAll() {
            if (confirm('Yakin ingin mereset semua limit anggaran bulan ini menjadi 0?')) {
                this.items.forEach(item => item.limit = 0);
            }
        },

        getStatusLabel(item) {
            if (item.limit <= 0) return 'Tanpa Limit';
            let pct = Math.round((item.spent / item.limit) * 100);
            if (pct >= 100) return 'Over Budget (' + pct + '%)';
            if (pct >= 80) return 'Waspada (' + pct + '%)';
            return 'Aman (' + pct + '%)';
        },

        async saveAll() {
            this.saving = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            const payload = {
                month: this.month,
                year: this.year,
                budgets: this.items.map(i => ({
                    category_id: i.id,
                    amount: parseFloat(i.limit) || 0
                }))
            };

            try {
                const res = await fetch('{{ route('admin.category_budgets.batch_update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    if (typeof showToast === 'function') {
                        showToast(data.message, 'success');
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Gagal menyimpan target anggaran.');
                }
            } catch (err) {
                if (typeof showToast === 'function') {
                    showToast(err.message || 'Terjadi kesalahan saat menyimpan.', 'error');
                } else {
                    alert(err.message || 'Terjadi kesalahan saat menyimpan.');
                }
            } finally {
                this.saving = false;
            }
        },

        async copyPreviousMonth() {
            if (!confirm('Salin semua limit anggaran dari bulan lalu ke bulan ini?')) return;
            this.copying = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            try {
                const res = await fetch('{{ route('admin.category_budgets.copy_previous') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        month: this.month,
                        year: this.year
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    if (typeof showToast === 'function') {
                        showToast(data.message, 'success');
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Tidak ada target anggaran dari bulan lalu.');
                }
            } catch (err) {
                if (typeof showToast === 'function') {
                    showToast(err.message, 'error');
                } else {
                    alert(err.message);
                }
            } finally {
                this.copying = false;
            }
        }
    };
}
</script>
@endpush
