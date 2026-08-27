@extends('admin.layouts.app')

@section('title', 'Transaksi Berulang & Rutin')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Transaksi Berulang & Rutin</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Otomatisasi pencatatan tagihan bulanan, langganan, dan pendapatan rutin Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.recurring_transactions.create') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl font-semibold text-xs shadow-sm shadow-emerald-500/20 hover:scale-[1.02] transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Jadwal Rutin</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-3.5">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Jadwal</p>
                    <h3 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white mt-1">{{ $totalRecurring }} Jadwal</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Jadwal Aktif</p>
                    <h3 class="text-base sm:text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $activeRecurring }} Aktif</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Beban Rutin Bulanan</p>
                    <h3 class="text-base sm:text-lg font-bold text-rose-600 dark:text-rose-400 mt-1">Rp {{ number_format($monthlyExpenseEstimate, 0, ',', '.') }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- PowerGrid Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 shadow-xs overflow-hidden">
        <livewire:tables.recurring-transaction-table />
    </div>
</div>
@endsection
