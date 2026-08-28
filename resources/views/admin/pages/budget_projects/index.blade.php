@extends('admin.layouts.app')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="budgetProjectsIndex({
        iconDefinitions: {{ json_encode(\App\Helpers\ProjectIconHelper::getIconDefinitions()) }}
     })" 
     class="space-y-3.5 sm:space-y-5 pb-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3">
        <div>
            <nav class="flex text-[10px] sm:text-[11px] text-zinc-500 dark:text-zinc-400 font-medium mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard', ['view' => 'personal']) }}" class="hover:text-emerald-600 transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-zinc-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            <span class="text-zinc-800 dark:text-zinc-200 font-semibold">Anggaran Proyek</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-base sm:text-lg md:text-xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                    Anggaran Proyek & Acara
                </h1>
                <span class="text-[10px] sm:text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300 font-bold border border-emerald-200/60 dark:border-emerald-800/60">
                    {{ $activeProjectsCount }} Aktif
                </span>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                Kelola pagu dana terpisah untuk pernikahan, liburan, renovasi, atau target impian lainnya.
            </p>
        </div>

        <!-- Action Button -->
        <button type="button" 
                @click="openCreateModal = true"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-500/20 active:scale-95 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Buat Proyek Baru</span>
        </button>
    </div>

    <!-- 4 Summary Stat Cards (Clean 2-Col on Mobile) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3.5">
        <!-- Card 1: Total Pagu Proyek Aktif -->
        <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] sm:text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Pagu</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="text-xs sm:text-base lg:text-lg font-black text-zinc-900 dark:text-white truncate">
                Rp {{ number_format($totalTargetOverall, 0, ',', '.') }}
            </div>
            <p class="text-[9px] sm:text-[10px] text-zinc-400 mt-0.5 truncate">Pagu proyek aktif</p>
        </div>

        <!-- Card 2: Realisasi Terpakai -->
        <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] sm:text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Terpakai</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="text-xs sm:text-base lg:text-lg font-black text-rose-600 dark:text-rose-400 truncate">
                Rp {{ number_format($totalSpentOverall, 0, ',', '.') }}
            </div>
            <p class="text-[9px] sm:text-[10px] text-zinc-400 mt-0.5 truncate">Sudah dibayar</p>
        </div>

        <!-- Card 3: Sisa Pagu Anggaran -->
        <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] sm:text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Sisa Pagu</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-xs sm:text-base lg:text-lg font-black text-emerald-600 dark:text-emerald-400 truncate">
                Rp {{ number_format($totalRemainingOverall, 0, ',', '.') }}
            </div>
            <p class="text-[9px] sm:text-[10px] text-zinc-400 mt-0.5 truncate">Belum dibelanjakan</p>
        </div>

        <!-- Card 4: Status Proyek -->
        <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] sm:text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
            </div>
            <div class="text-xs sm:text-base lg:text-lg font-black text-zinc-900 dark:text-white truncate">
                {{ $activeProjectsCount }} Aktif <span class="text-[10px] sm:text-xs text-zinc-400 font-medium">• {{ $completedProjectsCount }} Selesai</span>
            </div>
            <p class="text-[9px] sm:text-[10px] text-zinc-400 mt-0.5 truncate">Total {{ $totalProjectsCount }} proyek</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center justify-between gap-2 border-b border-zinc-200/80 dark:border-zinc-800 pb-2">
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            <a href="{{ route('admin.budget_projects.index', ['status' => 'active']) }}"
               class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'active' ? 'bg-emerald-500 text-white shadow-xs' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200' }}">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Berjalan</span>
            </a>
            <a href="{{ route('admin.budget_projects.index', ['status' => 'completed']) }}"
               class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'completed' ? 'bg-emerald-500 text-white shadow-xs' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Selesai</span>
            </a>
            <a href="{{ route('admin.budget_projects.index', ['status' => 'all']) }}"
               class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'all' ? 'bg-emerald-500 text-white shadow-xs' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                <span>Semua ({{ $totalProjectsCount }})</span>
            </a>
        </div>
    </div>

    <!-- Projects Grid -->
    @if($projects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5">
            @foreach($projects as $project)
                <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                    <!-- Top Section -->
                    <div class="p-3.5 sm:p-5">
                        <!-- Icon, Name, & Status Badge -->
                        <div class="flex items-start justify-between gap-2.5 mb-2.5">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-emerald-50 to-teal-100 dark:from-emerald-950/60 dark:to-teal-900/40 border border-emerald-200/60 dark:border-emerald-800/60 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform text-emerald-600 dark:text-emerald-400">
                                    {!! \App\Helpers\ProjectIconHelper::renderSvg($project->icon, 'w-5 h-5 sm:w-6 sm:h-6') !!}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs sm:text-base font-extrabold text-zinc-900 dark:text-white truncate">
                                        {{ $project->name }}
                                    </h3>
                                    @if($project->target_date)
                                        <div class="flex items-center gap-1 text-[10px] sm:text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5 flex-wrap">
                                            <span>{{ $project->target_date->translatedFormat('d M Y') }}</span>
                                            @if($project->status === 'active' && $project->days_remaining !== null)
                                                <span class="font-bold {{ $project->days_remaining < 30 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                                    • {{ $project->days_remaining > 0 ? "Sisa {$project->days_remaining} hari" : 'Hari H tiba!' }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-[10px] sm:text-[11px] text-zinc-400 mt-0.5 block">Tanpa batas waktu</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <span class="px-2 py-0.5 text-[9px] sm:text-[10px] font-extrabold uppercase rounded-full flex-shrink-0
                                {{ $project->status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60' : ($project->status === 'completed' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 border border-blue-200/60' : 'bg-zinc-100 text-zinc-600') }}">
                                {{ $project->status === 'active' ? 'Berjalan' : ($project->status === 'completed' ? 'Selesai' : 'Batal') }}
                            </span>
                        </div>

                        <!-- Progress Bar Section -->
                        <div class="mt-3 p-2.5 sm:p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-bold text-[11px] sm:text-xs text-zinc-600 dark:text-zinc-300">Penggunaan</span>
                                <span class="font-extrabold text-[11px] sm:text-xs {{ $project->is_over_budget ? 'text-rose-600 dark:text-rose-400' : ($project->actual_spent_percentage >= 80 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                    {{ $project->actual_spent_percentage }}%
                                </span>
                            </div>
                            <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-2 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $project->is_over_budget ? 'bg-rose-500' : ($project->actual_spent_percentage >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                     style="width: {{ $project->spent_percentage }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[10px] sm:text-[11px] text-zinc-500 dark:text-zinc-400 mt-2 font-medium">
                                <span>Terpakai: <strong class="text-zinc-800 dark:text-zinc-200">{{ $project->total_spent_formatted }}</strong></span>
                                <span>Pagu: <strong class="text-zinc-800 dark:text-zinc-200">{{ $project->target_amount_formatted }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Button -->
                    <div class="p-2.5 px-3.5 sm:p-3 sm:px-5 bg-zinc-50/70 dark:bg-zinc-800/40 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div class="text-[10px] sm:text-[11px] truncate max-w-[170px] sm:max-w-none">
                            <span class="text-zinc-400">Sisa: </span>
                            <strong class="{{ $project->remaining_budget <= 0 && $project->total_spent > 0 ? 'text-rose-600' : 'text-emerald-600 dark:text-emerald-400' }}">
                                {{ $project->remaining_budget_formatted }}
                            </strong>
                        </div>
                        <a href="{{ route('admin.budget_projects.show', $project->id) }}"
                           class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition flex-shrink-0">
                            <span>Kelola Detail</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="p-6 sm:p-12 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs text-center max-w-lg mx-auto my-4 sm:my-6">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg shadow-emerald-500/25">
                <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-sm sm:text-lg font-extrabold text-zinc-900 dark:text-white">
                Belum Ada Proyek atau Rencana Acara
            </h3>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1 mb-4 sm:mb-5 leading-relaxed">
                Rencanakan anggaran khusus untuk pernikahan, liburan, renovasi, atau target besar lainnya dengan rapi.
            </p>
            <button type="button" 
                    @click="openCreateModal = true"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-500/30 active:scale-95 transition cursor-pointer">
                <span>+ Buat Proyek Pertama Sekarang</span>
            </button>
        </div>
    @endif

    <!-- ============================================================== -->
    <!-- MODAL: BUAT PROYEK BARU (Fixed Teleport Overlay) -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="openCreateModal"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="openCreateModal = false"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
             x-cloak>
            
            <div @click.away="openCreateModal = false"
                 x-show="openCreateModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-lg bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden my-auto max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="iconDefinitions[selectedIcon]?.svg || iconDefinitions['sparkles']?.svg"></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Buat Proyek & Anggaran Baru</h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Pernikahan, liburan, renovasi, atau target impian</p>
                        </div>
                    </div>
                    <button type="button" @click="openCreateModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <form action="{{ route('admin.budget_projects.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    
                    <div class="p-5 sm:p-6 overflow-y-auto space-y-4 flex-1">
                        <!-- Icon Selector -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Pilih Ikon Proyek</label>
                            <div class="flex items-center gap-2 flex-wrap">
                                <template x-for="(def, key) in iconDefinitions" :key="key">
                                    <button type="button"
                                            @click="selectedIcon = key"
                                            :title="def.label"
                                            class="w-9 h-9 rounded-xl flex items-center justify-center border transition cursor-pointer"
                                            :class="selectedIcon === key ? 'border-emerald-500 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 scale-110 shadow-xs' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 hover:border-zinc-300 dark:hover:border-zinc-600'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="def.svg"></svg>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="icon" :value="selectedIcon">
                        </div>

                        <!-- Project Name -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                Nama Proyek / Acara <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   required 
                                   placeholder="Contoh: Rencana Pernikahan 2026, Liburan Jepang, dll"
                                   class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>

                        <!-- Target Amount & Date Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                    Total Pagu Anggaran (Rp) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" 
                                       name="target_amount" 
                                       required 
                                       min="1"
                                       step="1000"
                                       placeholder="Contoh: 50000000"
                                       class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition font-semibold">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                    Target Tanggal (Deadline / Hari H)
                                </label>
                                <input type="date" 
                                       name="target_date" 
                                       class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition">
                            </div>
                        </div>

                        <!-- Initial Sub-Items (Optional) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-zinc-700 dark:text-zinc-300">
                                    Rincian Pos Belanja Awal (Opsional)
                                </label>
                                <button type="button" 
                                        @click="addSubItem()"
                                        class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 cursor-pointer">
                                    + Tambah Pos
                                </button>
                            </div>
                            
                            <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
                                <template x-for="(item, idx) in initialItems" :key="idx">
                                    <div class="flex items-center gap-2">
                                        <input type="text" 
                                               :name="`items[${idx}][name]`"
                                               x-model="item.name"
                                               placeholder="Nama pos (mis: Dekorasi)"
                                               class="flex-1 text-xs px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                        <input type="number" 
                                               :name="`items[${idx}][target_amount]`"
                                               x-model="item.target_amount"
                                               placeholder="Pagu (Rp)"
                                               min="0"
                                               step="1000"
                                               class="w-28 sm:w-36 text-xs px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none font-semibold">
                                        <button type="button" 
                                                @click="removeSubItem(idx)"
                                                class="p-2 text-zinc-400 hover:text-rose-500 transition cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Catatan / Visi Proyek</label>
                            <textarea name="note" 
                                      rows="2" 
                                      placeholder="Catatan tambahan, kesepakatan keluarga/pasangan, atau referensi..."
                                      class="w-full text-xs px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-5 py-3.5 bg-zinc-50/80 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-2 flex-shrink-0">
                        <button type="button" 
                                @click="openCreateModal = false"
                                class="px-4 py-2 rounded-xl text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition shadow-md shadow-emerald-500/20 active:scale-95 cursor-pointer">
                            Simpan & Mulai Proyek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('budgetProjectsIndex', (config = {}) => ({
        openCreateModal: false,
        selectedIcon: 'heart',
        iconDefinitions: config.iconDefinitions || {},
        initialItems: [
            { name: 'Pos Utama 1', target_amount: '' },
            { name: 'Pos Utama 2', target_amount: '' }
        ],
        addSubItem() {
            this.initialItems.push({ name: '', target_amount: '' });
        },
        removeSubItem(index) {
            if (this.initialItems.length > 1) {
                this.initialItems.splice(index, 1);
            }
        }
    }));
});
</script>
@endsection

