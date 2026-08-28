@extends('admin.layouts.app')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="budgetProjectDetail({
        projectId: {{ $budgetProject->id }},
        refreshAiUrl: '{{ route('admin.budget_projects.refresh_ai', $budgetProject->id) }}',
        initialAi: {{ json_encode($aiInsights) }}
     })" 
     class="space-y-3.5 sm:space-y-5 pb-8">

    <!-- Top Bar: Navigation & Action Header -->
    <div class="space-y-3">
        <!-- Breadcrumb / Back Link & Settings -->
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('admin.budget_projects.index') }}" 
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition group">
                <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                <span>Anggaran Proyek</span>
            </a>

            <button type="button" 
                    @click="openEditProjectModal = true"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 border border-zinc-200/80 dark:border-zinc-700 text-xs font-semibold shadow-2xs transition cursor-pointer" 
                    title="Pengaturan Proyek">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="hidden sm:inline">Pengaturan</span>
            </button>
        </div>

        <!-- Project Main Identity & Action Row -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white dark:bg-zinc-900 p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
            <!-- Left: Icon & Project Info -->
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 sm:w-13 sm:h-13 rounded-2xl bg-gradient-to-tr from-emerald-50 to-teal-100 dark:from-emerald-950/70 dark:to-teal-900/40 border border-emerald-200/60 dark:border-emerald-800/60 flex items-center justify-center flex-shrink-0 shadow-xs text-emerald-600 dark:text-emerald-400">
                    {!! \App\Helpers\ProjectIconHelper::renderSvg($budgetProject->icon, 'w-5 h-5 sm:w-6 sm:h-6') !!}
                </div>
                <div class="min-w-0">
                    <!-- Status & Target Pill Line -->
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[9px] sm:text-[10px] font-extrabold uppercase rounded-full
                            {{ $budgetProject->status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60' : ($budgetProject->status === 'completed' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 border border-blue-200/60' : 'bg-zinc-100 text-zinc-600') }}">
                            @if($budgetProject->status === 'active')
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Berjalan</span>
                            @elseif($budgetProject->status === 'completed')
                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Selesai</span>
                            @else
                                <svg class="w-3 h-3 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Dibatalkan</span>
                            @endif
                        </span>

                        @if($budgetProject->target_date)
                            <span class="text-[10px] sm:text-xs text-zinc-500 dark:text-zinc-400">
                                Target: <strong class="text-zinc-700 dark:text-zinc-300 font-semibold">{{ $budgetProject->target_date->translatedFormat('d M Y') }}</strong>
                                @if($budgetProject->status === 'active' && $budgetProject->days_remaining !== null)
                                    • <span class="font-bold {{ $budgetProject->days_remaining < 30 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        {{ $budgetProject->days_remaining > 0 ? "Sisa {$budgetProject->days_remaining} hari" : 'Hari H tiba!' }}
                                    </span>
                                @endif
                            </span>
                        @endif
                    </div>

                    <!-- Project Title -->
                    <h1 class="text-base sm:text-xl font-black text-zinc-900 dark:text-white tracking-tight truncate">
                        {{ $budgetProject->name }}
                    </h1>
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2 w-full md:w-auto pt-2 md:pt-0 border-t md:border-t-0 border-zinc-100 dark:border-zinc-800">
                <button type="button" 
                        @click="openExpenseModal = true"
                        class="flex-1 md:flex-initial inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-500/20 active:scale-95 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Catat Pengeluaran</span>
                </button>

                <button type="button" 
                        @click="openAddItemModal = true"
                        class="inline-flex items-center justify-center gap-1 px-3.5 py-2.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 text-xs sm:text-sm font-bold transition cursor-pointer">
                    <span>+ Pos</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Progress & Financial Overview Card -->
    <div class="p-3.5 sm:p-6 rounded-2xl sm:rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 dark:bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row items-stretch gap-4 sm:gap-6 relative z-10">
            <!-- Left: Big Metric Overview & Progress Gauge -->
            <div class="w-full lg:w-7/12 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-1.5 sm:mb-2">
                        <span class="text-[10px] sm:text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Realisasi Penggunaan Anggaran
                        </span>
                        <span class="text-[10px] sm:text-xs font-extrabold px-2 sm:px-2.5 py-0.5 rounded-full {{ $budgetProject->is_over_budget ? 'bg-rose-100 text-rose-700' : ($budgetProject->actual_spent_percentage >= 80 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $budgetProject->actual_spent_percentage }}% Terpakai
                        </span>
                    </div>

                    <!-- Numbers Header -->
                    <div class="flex items-baseline gap-1.5 sm:gap-2 mb-2 sm:mb-3 flex-wrap">
                        <span class="text-xl sm:text-3xl md:text-4xl font-black text-zinc-900 dark:text-white">
                            {{ $budgetProject->total_spent_formatted }}
                        </span>
                        <span class="text-xs sm:text-sm font-semibold text-zinc-400">
                            dari {{ $budgetProject->target_amount_formatted }}
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2.5 sm:h-4 rounded-full overflow-hidden p-0.5 shadow-inner">
                        <div class="h-full rounded-full transition-all duration-700 {{ $budgetProject->is_over_budget ? 'bg-rose-500' : ($budgetProject->actual_spent_percentage >= 80 ? 'bg-amber-500' : 'bg-gradient-to-r from-emerald-500 to-teal-500') }}"
                             style="width: {{ $budgetProject->spent_percentage }}%"></div>
                    </div>
                </div>

                <!-- 3 Sub Metrics Grid -->
                <div class="grid grid-cols-3 gap-1.5 sm:gap-3 mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <div>
                        <span class="text-[9px] sm:text-[11px] text-zinc-400 font-medium block truncate">Total Pagu</span>
                        <span class="text-[11px] sm:text-sm font-bold text-zinc-900 dark:text-white truncate block">
                            {{ $budgetProject->target_amount_formatted }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[11px] text-zinc-400 font-medium block truncate">Sisa Pagu</span>
                        <span class="text-[11px] sm:text-sm font-bold {{ $budgetProject->remaining_budget <= 0 && $budgetProject->total_spent > 0 ? 'text-rose-600' : 'text-emerald-600 dark:text-emerald-400' }} truncate block">
                            {{ $budgetProject->remaining_budget_formatted }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[11px] text-zinc-400 font-medium block truncate">Alokasi Pos</span>
                        <span class="text-[11px] sm:text-sm font-bold text-zinc-900 dark:text-white truncate block">
                            Rp {{ number_format($budgetProject->total_allocated, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Note / Summary Card -->
            <div class="w-full lg:w-5/12 p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-zinc-50/70 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800 flex flex-col justify-between">
                <div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">
                        Catatan & Rencana
                    </span>
                    <p class="text-[11px] sm:text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed italic">
                        {{ $budgetProject->note ?: 'Belum ada catatan khusus untuk proyek ini. Klik tombol pengaturan untuk menambahkan catatan.' }}
                    </p>
                </div>

                <div class="mt-2.5 pt-2.5 sm:mt-3 sm:pt-3 border-t border-zinc-200/60 dark:border-zinc-700/60 flex items-center justify-between text-[11px] sm:text-xs">
                    <span class="text-zinc-500 dark:text-zinc-400">Pagu Belum Dialokasikan:</span>
                    <strong class="{{ $budgetProject->unallocated_amount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        {{ $budgetProject->unallocated_amount > 0 ? 'Rp ' . number_format($budgetProject->unallocated_amount, 0, ',', '.') : 'Pas 100%' }}
                    </strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Gemini AI Project Advisor Card -->
    <div class="p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-white via-zinc-50/60 to-emerald-50/40 dark:from-zinc-900 dark:via-zinc-900/90 dark:to-emerald-950/25 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between gap-2.5 mb-2.5 sm:mb-3">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center text-xs sm:text-sm shadow-xs flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-xs sm:text-sm font-extrabold text-zinc-900 dark:text-white truncate">
                            Gemini AI Advisor
                        </h3>
                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                            Smart
                        </span>
                    </div>
                    <p class="text-[9px] sm:text-[10px] text-zinc-500 dark:text-zinc-400 truncate">Evaluasi pace tabungan & alokasi pos</p>
                </div>
            </div>

            <button type="button" 
                    @click="refreshAi()" 
                    :disabled="loadingAi"
                    class="inline-flex items-center gap-1 px-2.5 py-1 sm:py-1.5 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:text-emerald-600 text-[10px] sm:text-[11px] font-bold transition shadow-2xs cursor-pointer disabled:opacity-50 flex-shrink-0">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" :class="{'animate-spin text-emerald-600': loadingAi}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span x-text="loadingAi ? 'Menganalisis...' : 'Refresh AI'"></span>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 text-xs">
            <!-- 1. Ringkasan -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white/80 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60 shadow-2xs space-y-1">
                <span class="text-[9px] sm:text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Status Kesiapan</span>
                <p class="text-[11px] sm:text-xs text-zinc-700 dark:text-zinc-300 font-medium leading-snug" x-text="ai.summary"></p>
            </div>

            <!-- 2. Timeline & Pace Tabungan -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white/80 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60 shadow-2xs space-y-1">
                <span class="text-[9px] sm:text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Pace & Timeline</span>
                <p class="text-[11px] sm:text-xs text-zinc-700 dark:text-zinc-300 font-medium leading-snug" x-text="ai.pace_insight"></p>
            </div>

            <!-- 3. Warning Alokasi Pos -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white/80 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60 shadow-2xs space-y-1">
                <span class="text-[9px] sm:text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Alokasi Rincian</span>
                <p class="text-[11px] sm:text-xs text-zinc-700 dark:text-zinc-300 font-medium leading-snug" x-text="ai.item_warning"></p>
            </div>

            <!-- 4. Tips Aksi Konkret -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white/80 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60 shadow-2xs space-y-1">
                <span class="text-[9px] sm:text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">Saran Gemini</span>
                <p class="text-[11px] sm:text-xs text-zinc-700 dark:text-zinc-300 font-medium leading-snug" x-text="ai.actionable_tip"></p>
            </div>
        </div>
    </div>

    <!-- Pos Rincian Sub-Budget Checklist -->
    <div class="p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
        <div class="flex items-center justify-between gap-2.5 mb-3.5 pb-2.5 sm:pb-3 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-xs sm:text-base font-bold text-zinc-900 dark:text-white truncate">
                        Pos Rincian Anggaran
                    </h2>
                    <p class="text-[10px] sm:text-xs text-zinc-500 truncate">Pagu belanja per pos kebutuhan</p>
                </div>
            </div>

            <button type="button" 
                    @click="openAddItemModal = true"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 text-[11px] sm:text-xs font-bold hover:bg-emerald-100 transition cursor-pointer flex-shrink-0">
                <span>+ Tambah Pos</span>
            </button>
        </div>

        @if($budgetProject->items->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3">
                @foreach($budgetProject->items as $item)
                    <div class="p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border transition-all duration-200 {{ $item->status === 'completed' ? 'bg-emerald-50/30 dark:bg-emerald-950/15 border-emerald-200/70 dark:border-emerald-800/50' : ($item->is_over_budget ? 'bg-rose-50/30 dark:bg-rose-950/15 border-rose-200/70 dark:border-rose-800/50' : 'bg-white dark:bg-zinc-900 border-zinc-200/80 dark:border-zinc-800') }} shadow-2xs flex flex-col justify-between">
                        
                        <div>
                            <!-- Item Header: Name & Status Checkbox -->
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    <form action="{{ route('admin.budget_projects.items.toggle_status', [$budgetProject->id, $item->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-4 h-4 sm:w-5 sm:h-5 rounded-md sm:rounded-lg border flex items-center justify-center transition cursor-pointer {{ $item->status === 'completed' ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-zinc-300 dark:border-zinc-600 hover:border-emerald-500' }}" 
                                                title="{{ $item->status === 'completed' ? 'Tandai Belum Selesai' : 'Tandai Lunas / Selesai' }}">
                                            @if($item->status === 'completed')
                                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 stroke-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <h4 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white truncate {{ $item->status === 'completed' ? 'line-through text-zinc-400 dark:text-zinc-500' : '' }}">
                                        {{ $item->name }}
                                    </h4>
                                </div>

                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase
                                        {{ $item->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : ($item->is_over_budget ? 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300') }}">
                                        @if($item->status === 'completed')
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Lunas</span>
                                        @elseif($item->is_over_budget)
                                            <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            <span>Overbudget</span>
                                        @elseif($item->spent_amount > 0)
                                            <span>Diproses</span>
                                        @else
                                            <span>Belum Mulai</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Item Progress Bar -->
                            <div class="my-2">
                                <div class="flex items-center justify-between text-[10px] sm:text-[11px] mb-1 font-semibold">
                                    <span class="text-zinc-500 dark:text-zinc-400">
                                        Terpakai: <strong class="text-zinc-800 dark:text-zinc-200">{{ $item->total_spent_formatted }}</strong>
                                    </span>
                                    <span class="{{ $item->is_over_budget ? 'text-rose-600 font-bold' : 'text-zinc-600 dark:text-zinc-300' }}">
                                        Pagu: {{ $item->target_amount_formatted }} ({{ $item->actual_spent_percentage }}%)
                                    </span>
                                </div>
                                <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 sm:h-2 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $item->is_over_budget ? 'bg-rose-500' : ($item->actual_spent_percentage >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                         style="width: {{ $item->spent_percentage }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Item Footer Actions -->
                        <div class="flex items-center justify-between pt-1.5 sm:pt-2 border-t border-zinc-100 dark:border-zinc-800/80 text-[10px] sm:text-[11px] text-zinc-400">
                            <div>
                                @if($item->is_over_budget)
                                    <span class="text-rose-600 font-bold">Melebihi +{{ $item->over_amount_formatted }}</span>
                                @else
                                    <span>Sisa: <strong class="text-emerald-600 dark:text-emerald-400">{{ $item->remaining_amount_formatted }}</strong></span>
                                @endif
                            </div>

                            <div class="flex items-center gap-1">
                                <button type="button" 
                                        @click="prepareEditItem({{ json_encode($item) }})"
                                        class="p-1 text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition cursor-pointer" title="Edit Pos">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('admin.budget_projects.items.destroy', [$budgetProject->id, $item->id]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Hapus pos rincian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-zinc-400 hover:text-rose-500 transition cursor-pointer" title="Hapus Pos">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center border border-dashed border-zinc-200 dark:border-zinc-800 rounded-2xl">
                <p class="text-xs text-zinc-400">Belum ada pos rincian anggaran.</p>
                <button type="button" 
                        @click="openAddItemModal = true"
                        class="mt-2 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    + Tambah Pos Rincian Sekarang
                </button>
            </div>
        @endif
    </div>

    <!-- Riwayat Transaksi Pengeluaran Proyek -->
    <div class="p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
        <div class="flex items-center justify-between gap-2.5 mb-3.5 pb-2.5 sm:pb-3 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-xs sm:text-base font-bold text-zinc-900 dark:text-white truncate">
                        Pengeluaran Proyek
                    </h2>
                    <p class="text-[10px] sm:text-xs text-zinc-500 truncate">Mutasi kas khusus untuk proyek ini</p>
                </div>
            </div>

            <button type="button" 
                    @click="openExpenseModal = true"
                    class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-[11px] sm:text-xs font-bold hover:bg-emerald-700 transition shadow-xs cursor-pointer flex-shrink-0">
                <span>+ Catat</span>
            </button>
        </div>

        @if($budgetProject->transactions->count() > 0)
            <!-- Mobile Card List View -->
            <div class="sm:hidden space-y-2">
                @foreach($budgetProject->transactions as $trx)
                    <div class="p-3 rounded-xl bg-zinc-50/80 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-2.5">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-0.5 text-[10px]">
                                <span class="text-zinc-400 font-medium">{{ $trx->transaction_date->format('d/m/Y') }}</span>
                                <span class="text-zinc-300 dark:text-zinc-600">•</span>
                                <span class="text-zinc-500 dark:text-zinc-400 truncate">{{ $trx->account->name ?? 'Kas' }}</span>
                            </div>
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $trx->note }}</p>
                            @if($trx->projectItem)
                                <span class="inline-block mt-1 px-1.5 py-0.2 rounded bg-zinc-200/80 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-[9px]">
                                    {{ $trx->projectItem->name }}
                                </span>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-xs font-black text-rose-600 dark:text-rose-400 whitespace-nowrap">
                                -Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-100 dark:border-zinc-800 text-[10px] font-bold text-zinc-400 uppercase tracking-wider">
                            <th class="pb-2.5">Tanggal</th>
                            <th class="pb-2.5">Keterangan</th>
                            <th class="pb-2.5">Pos Rincian</th>
                            <th class="pb-2.5">Rekening Dompet</th>
                            <th class="pb-2.5 text-right">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($budgetProject->transactions as $trx)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                                <td class="py-3 text-zinc-500 dark:text-zinc-400 whitespace-nowrap font-medium">
                                    {{ $trx->transaction_date->format('d/m/Y') }}
                                </td>
                                <td class="py-3 font-semibold text-zinc-900 dark:text-white">
                                    {{ $trx->note }}
                                </td>
                                <td class="py-3 whitespace-nowrap">
                                    @if($trx->projectItem)
                                        <span class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-[10px]">
                                            {{ $trx->projectItem->name }}
                                        </span>
                                    @else
                                        <span class="text-zinc-400 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="py-3 whitespace-nowrap text-zinc-600 dark:text-zinc-300">
                                    {{ $trx->account->name ?? 'Kas' }}
                                </td>
                                <td class="py-3 text-right font-extrabold text-rose-600 whitespace-nowrap">
                                    -Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center border border-dashed border-zinc-200 dark:border-zinc-800 rounded-2xl">
                <p class="text-xs text-zinc-400">Belum ada catatan pengeluaran untuk proyek ini.</p>
                <button type="button" 
                        @click="openExpenseModal = true"
                        class="mt-2 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    + Catat Pengeluaran Pertama Sekarang
                </button>
            </div>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 1: CATAT PENGELUARAN PROYEK (Fixed Teleport Overlay) -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="openExpenseModal"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="openExpenseModal = false"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
             x-cloak>
            
            <div @click.away="openExpenseModal = false"
                 x-show="openExpenseModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-md bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden my-auto max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-sm shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Catat Pengeluaran Proyek</h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ $budgetProject->name }}</p>
                        </div>
                    </div>
                    <button type="button" @click="openExpenseModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('admin.budget_projects.transactions.store', $budgetProject->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                    @csrf

                    <div class="p-5 sm:p-6 overflow-y-auto space-y-3.5 flex-1">
                        <!-- Nominal Amount -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                Nominal Pengeluaran (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   name="amount" 
                                   required 
                                   min="1"
                                   step="1000"
                                   placeholder="Contoh: 5000000"
                                   class="w-full text-sm font-extrabold px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>

                        <!-- Sub-Item Allocation -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                Alokasikan ke Pos Rincian
                            </label>
                            <select name="budget_project_item_id" 
                                    class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                                <option value="">-- Umum / Tanpa Pos Khusus --</option>
                                @foreach($budgetProject->items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} (Sisa: {{ $item->remaining_amount_formatted }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Source Cash Account & Date -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                    Sumber Rekening / Dompet <span class="text-rose-500">*</span>
                                </label>
                                <select name="account_id" 
                                        required
                                        class="w-full text-xs px-3 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->balance_formatted }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                    Tanggal Transaksi <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" 
                                       name="transaction_date" 
                                       required 
                                       value="{{ now()->format('Y-m-d') }}"
                                       class="w-full text-xs px-3 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                        </div>

                        <!-- Note -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                                Keterangan Pembayaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="note" 
                                   required 
                                   placeholder="Contoh: DP 50% Gedung & Dekorasi"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <!-- Proof File Upload -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Foto Bukti / Struk Transfer (Opsional)</label>
                            <input type="file" 
                                    name="proof" 
                                    accept="image/*,.pdf"
                                    class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-5 py-3.5 bg-zinc-50/80 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-2 flex-shrink-0">
                        <button type="button" @click="openExpenseModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-rose-600 to-amber-600 shadow-md transition cursor-pointer">
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- ============================================================== -->
    <!-- MODAL 2: TAMBAH POS RINCIAN (Fixed Teleport Overlay) -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="openAddItemModal"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="openAddItemModal = false"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
             x-cloak>
            
            <div @click.away="openAddItemModal = false"
                 x-show="openAddItemModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-sm bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden my-auto max-h-[90vh] flex flex-col">
                
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Tambah Pos Rincian Anggaran</h3>
                    <button type="button" @click="openAddItemModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('admin.budget_projects.items.store', $budgetProject->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="p-5 overflow-y-auto space-y-3 flex-1">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Nama Pos Kebutuhan <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: Katering, Dekorasi, Tiket" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Target Pagu Pos (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="target_amount" required min="0" step="1000" placeholder="Contoh: 15000000" class="w-full text-xs font-bold px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Catatan Tambahan</label>
                            <input type="text" name="note" placeholder="Vendor pilihan, perkiraan porsi, dll" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-zinc-50/80 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-2 flex-shrink-0">
                        <button type="button" @click="openAddItemModal = false" class="px-3.5 py-2 text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl transition cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs cursor-pointer transition">Tambah Pos</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- ============================================================== -->
    <!-- MODAL 3: EDIT POS RINCIAN (Fixed Teleport Overlay) -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="openEditItemModal"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="openEditItemModal = false"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
             x-cloak>
            
            <div @click.away="openEditItemModal = false"
                 x-show="openEditItemModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-sm bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden my-auto max-h-[90vh] flex flex-col">
                
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Edit Pos Rincian</h3>
                    <button type="button" @click="openEditItemModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="editItemActionUrl" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    @method('PUT')
                    <div class="p-5 overflow-y-auto space-y-3 flex-1">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Nama Pos</label>
                            <input type="text" name="name" x-model="editItemData.name" required class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Target Pagu Pos (Rp)</label>
                            <input type="number" name="target_amount" x-model="editItemData.target_amount" required min="0" step="1000" class="w-full text-xs font-bold px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Status Pengerjaan</label>
                            <select name="status" x-model="editItemData.status" class="w-full text-xs px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                <option value="pending">Belum Mulai (Pending)</option>
                                <option value="in_progress">Dalam Proses (DP/Cicil)</option>
                                <option value="completed">Selesai / Lunas (Completed)</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-zinc-50/80 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-2 flex-shrink-0">
                        <button type="button" @click="openEditItemModal = false" class="px-3.5 py-2 text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl transition cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs cursor-pointer transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- ============================================================== -->
    <!-- MODAL 4: EDIT DATA PROYEK (Fixed Teleport Overlay) -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="openEditProjectModal"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="openEditProjectModal = false"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
             x-cloak>
            
            <div @click.away="openEditProjectModal = false"
                 x-show="openEditProjectModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-lg bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden my-auto max-h-[90vh] flex flex-col">
                
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0">
                    <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white">Pengaturan & Edit Proyek</h3>
                    <button type="button" @click="openEditProjectModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('admin.budget_projects.update', $budgetProject->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    @method('PUT')

                    <div class="p-5 sm:p-6 overflow-y-auto space-y-3.5 flex-1">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Nama Proyek</label>
                            <input type="text" name="name" value="{{ $budgetProject->name }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Total Pagu Anggaran (Rp)</label>
                                <input type="number" name="target_amount" value="{{ (int)$budgetProject->target_amount }}" required min="1" step="1000" class="w-full text-xs font-bold px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Target Tanggal</label>
                                <input type="date" name="target_date" value="{{ $budgetProject->target_date ? $budgetProject->target_date->format('Y-m-d') : '' }}" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Status Proyek</label>
                            <select name="status" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                <option value="active" {{ $budgetProject->status === 'active' ? 'selected' : '' }}>Berjalan (Aktif)</option>
                                <option value="completed" {{ $budgetProject->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $budgetProject->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1">Catatan</label>
                            <textarea name="note" rows="2" class="w-full text-xs px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:outline-none">{{ $budgetProject->note }}</textarea>
                        </div>
                    </div>

                    <div class="px-5 py-3.5 bg-zinc-50/80 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0">
                        <form action="{{ route('admin.budget_projects.destroy', $budgetProject->id) }}" method="POST" onsubmit="return confirm('Hapus seluruh proyek ini beserta pos rinciannya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-rose-500 hover:underline cursor-pointer">Hapus Proyek</button>
                        </form>

                        <div class="flex items-center gap-2">
                            <button type="button" @click="openEditProjectModal = false" class="px-3.5 py-2 text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl transition cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs cursor-pointer transition">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('budgetProjectDetail', (config) => ({
        projectId: config.projectId,
        refreshAiUrl: config.refreshAiUrl,
        ai: config.initialAi,
        loadingAi: false,
        openExpenseModal: false,
        openAddItemModal: false,
        openEditItemModal: false,
        openEditProjectModal: false,
        editItemData: { id: null, name: '', target_amount: 0, status: 'pending' },
        get editItemActionUrl() {
            return `/admin/budget_projects/${this.projectId}/items/${this.editItemData.id || 0}`;
        },
        prepareEditItem(item) {
            this.editItemData = {
                id: item.id,
                name: item.name,
                target_amount: item.target_amount,
                status: item.status,
            };
            this.openEditItemModal = true;
        },
        async refreshAi() {
            this.loadingAi = true;
            try {
                const response = await fetch(this.refreshAiUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });
                if (response.ok) {
                    this.ai = await response.json();
                }
            } catch (e) {
                console.error('Failed to refresh project AI', e);
            } finally {
                this.loadingAi = false;
            }
        }
    }));
});
</script>
@endsection
