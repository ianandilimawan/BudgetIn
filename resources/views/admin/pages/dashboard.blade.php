@extends('admin.layouts.app')

@section('title', 'Dashboard Keuangan')

@push('styles')
<style>
    /* Instant ApexCharts Theme Text Styling without delay or reload */
    .apexcharts-canvas text,
    .apexcharts-text,
    .apexcharts-legend-text {
        fill: #52525b !important;
        color: #52525b !important;
    }
    .dark .apexcharts-canvas text,
    .dark .apexcharts-text,
    .dark .apexcharts-legend-text {
        fill: #a1a1aa !important;
        color: #a1a1aa !important;
    }
    .apexcharts-datalabel-label {
        fill: #71717a !important;
    }
    .dark .apexcharts-datalabel-label {
        fill: #a1a1aa !important;
    }
    .apexcharts-datalabel-value {
        fill: #18181b !important;
        font-weight: 800 !important;
    }
    .dark .apexcharts-datalabel-value {
        fill: #ffffff !important;
        font-weight: 800 !important;
    }
    .apexcharts-grid line {
        stroke: #f4f4f5 !important;
    }
    .dark .apexcharts-grid line {
        stroke: #27272a !important;
    }
</style>
@endpush

@section('content')
<div @open-quick-modal.window="quickModal = true" x-data="{ 
    activeTab: '{{ request('view', ($isSuperAdmin ? 'system' : 'personal')) }}',
    quickModal: false, 
    quickType: 'expense', 
    quickProofName: '', 
    quickProofPreview: '',
    quickIsPdf: false,
    handleProofChange(e) {
        const file = e.target.files[0];
        if (!file) return;
        this.quickProofName = file.name;
        this.quickIsPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
        if (!this.quickIsPdf && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.quickProofPreview = event.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            this.quickProofPreview = '';
        }
    },
    removeProof() {
        this.quickProofPreview = '';
        this.quickProofName = '';
        this.quickIsPdf = false;
        const fileInput = document.getElementById('quick_proof_input');
        if (fileInput) fileInput.value = '';
    }
}" class="space-y-4 sm:space-y-5 pb-24 sm:pb-16 md:pb-6">

    @if($isSuperAdmin)
    <!-- Top Role Tab Switcher for Super Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 pb-3 border-b border-zinc-200/80 dark:border-zinc-800">
        <div class="inline-flex items-center p-1 bg-zinc-100 dark:bg-zinc-800/80 rounded-xl border border-zinc-200/80 dark:border-zinc-700/60 w-full sm:w-auto">
            <button type="button" @click="activeTab = 'system'"
                :class="activeTab === 'system' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 font-medium'"
                class="flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer flex-1 sm:flex-initial whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span>Dashboard Platform</span>
            </button>
            <button type="button" @click="activeTab = 'personal'"
                :class="activeTab === 'personal' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 font-medium'"
                class="flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer flex-1 sm:flex-initial whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Keuangan Pribadi</span>
            </button>
        </div>
        <div class="flex items-center">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Super Admin Mode
            </span>
        </div>
    </div>

    <!-- Super Admin System & Platform View -->
    <div x-show="activeTab === 'system'" x-cloak class="space-y-4 sm:space-y-6">
        <!-- Super Admin Header & Shortcuts -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Dashboard Super Admin
                    </h1>
                    <span class="text-[10px] sm:text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold border border-emerald-200 dark:border-emerald-800">
                        Sistem & Platform
                    </span>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Pantau pertumbuhan pengguna, volume transaksi platform, status server, dan log aktivitas sistem.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.finance_users.index') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-sm shadow-emerald-600/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Kelola Pengguna Finance</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 transition shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Pengaturan</span>
                </a>
                <a href="{{ route('admin.laravel-logs.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 transition shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Log Server</span>
                </a>
            </div>
        </div>

        <!-- 4 Platform Metric Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- Card 1: Total Finance Users -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden group hover:border-zinc-300 dark:hover:border-zinc-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $systemStats['active_finance_users'] }} Aktif
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($systemStats['finance_users_count']) }}
                </div>
                <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400 font-medium mt-1">
                    <span>Pengguna Finance</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">+{{ $systemStats['new_finance_users_this_month'] }} bln ini</span>
                </div>
            </div>

            <!-- Card 2: Cash Accounts -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden group hover:border-zinc-300 dark:hover:border-zinc-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 border border-blue-200/50">
                        Multi-Wallet
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($systemStats['total_platform_accounts']) }}
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mt-1">
                    Dompet & Rekening Kas Terdaftar
                </p>
            </div>

            <!-- Card 3: Total Transactions -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden group hover:border-zinc-300 dark:hover:border-zinc-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 dark:bg-purple-950/80 dark:text-purple-300 border border-purple-200/50">
                        {{ $systemStats['active_recurring_schedules'] }} Rutin Aktif
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($systemStats['total_platform_transactions']) }}
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mt-1">
                    Total Mutasi Transaksi Kas Tercatat
                </p>
            </div>

            <!-- Card 4: Platform Cash Flow Volume -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden group hover:border-zinc-300 dark:hover:border-zinc-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200/50">
                        Volume Kas
                    </span>
                </div>
                <div class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-white tracking-tight">
                    Rp {{ number_format($systemStats['total_platform_income'] + $systemStats['total_platform_expense'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400 font-medium mt-1">
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">In: {{ number_format($systemStats['total_platform_income'], 0, ',', '.') }}</span> • <span class="text-rose-600 dark:text-rose-400 font-bold">Out: {{ number_format($systemStats['total_platform_expense'], 0, ',', '.') }}</span>
                </p>
            </div>
        </div>

        <!-- User Registration Growth Analytics Chart & Growth Highlights -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
            <!-- Main Registration Chart -->
            <div class="lg:col-span-2 p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <span>Tren Pertumbuhan Pengguna Baru (6 Bulan Terakhir)</span>
                        </h2>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">Statistik pendaftaran akun pengguna finance per bulan</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border border-indigo-200/50">
                        +{{ $systemStats['new_finance_users_this_month'] }} Bulan Ini
                    </span>
                </div>
                <div class="h-52 w-full">
                    <div id="userRegistrationChart" class="h-full w-full"></div>
                </div>
            </div>

            <!-- User Growth Highlights Card -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex flex-col justify-between">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Aktivitas & Pertumbuhan Pengguna</span>
                    </h2>
                    <div class="space-y-3">
                        <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-zinc-400 font-medium block">Pengguna Baru (7 Hari)</span>
                                <span class="text-base font-black text-zinc-900 dark:text-white">+{{ $systemStats['new_finance_users_this_week'] }} Akun</span>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                                7D
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-zinc-400 font-medium block">Tingkat Akun Aktif</span>
                                <span class="text-base font-black text-emerald-600 dark:text-emerald-400">
                                    {{ $systemStats['finance_users_count'] > 0 ? round(($systemStats['active_finance_users'] / $systemStats['finance_users_count']) * 100, 1) : 100 }}%
                                </span>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs">
                                {{ $systemStats['active_finance_users'] }}/{{ $systemStats['finance_users_count'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                    <span class="text-zinc-500 dark:text-zinc-400 text-[11px]">Akun Nonaktif / Ditangguhkan:</span>
                    <span class="font-bold text-rose-600 dark:text-rose-400">{{ $systemStats['inactive_finance_users'] }} User</span>
                </div>
            </div>
        </div>

        <!-- Quick Management Hub -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
            <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Pusat Administrasi & Kontrol Cepat</span>
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3">
                <a href="{{ route('admin.finance_users.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 border border-zinc-200/60 dark:border-zinc-700/60 hover:border-emerald-200 dark:hover:border-emerald-800 transition group">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">User Finance</h3>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400">Aktivasi akun</p>
                    </div>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 border border-zinc-200/60 dark:border-zinc-700/60 hover:border-indigo-200 dark:hover:border-indigo-800 transition group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Roles & Akses</h3>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400">Hak permission</p>
                    </div>
                </a>
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 hover:bg-blue-50 dark:hover:bg-blue-950/40 border border-zinc-200/60 dark:border-zinc-700/60 hover:border-blue-200 dark:hover:border-blue-800 transition group">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Activity Logs</h3>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400">Audit trail user</p>
                    </div>
                </a>
                <a href="{{ route('admin.laravel-logs.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 hover:bg-amber-50 dark:hover:bg-amber-950/40 border border-zinc-200/60 dark:border-zinc-700/60 hover:border-amber-200 dark:hover:border-amber-800 transition group">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">Server Logs</h3>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400">Monitor error</p>
                    </div>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 hover:bg-purple-50 dark:hover:bg-purple-950/40 border border-zinc-200/60 dark:border-zinc-700/60 hover:border-purple-200 dark:hover:border-purple-800 transition group">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/60 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Settings</h3>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400">Branding & mail</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Two-Column Split: Users & Server vs Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
            <!-- Left: Recent Registered Users & Server Telemetry -->
            <div class="space-y-4">
                <!-- Recent Users -->
                <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Pengguna Terdaftar Terbaru</span>
                        </h2>
                        <a href="{{ route('admin.finance_users.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Lihat Semua &rarr;</a>
                    </div>
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($systemStats['recent_users'] as $recentUser)
                            <div class="py-2.5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($recentUser->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $recentUser->name }}</h4>
                                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400 truncate">{{ $recentUser->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $recentUser->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300' }}">
                                        {{ $recentUser->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span class="text-[10px] text-zinc-400">
                                        {{ $recentUser->created_at ? $recentUser->created_at->diffForHumans(null, true) : '-' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-center text-xs text-zinc-400">Belum ada pengguna terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Server & Environment Telemetry -->
                <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        <span>Server & Framework Telemetry</span>
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
                        <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50">
                            <span class="text-[10px] text-zinc-400 block font-medium">PHP Version</span>
                            <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ $systemStats['server_info']['php_version'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50">
                            <span class="text-[10px] text-zinc-400 block font-medium">Laravel Engine</span>
                            <span class="font-bold text-zinc-800 dark:text-zinc-200">v{{ $systemStats['server_info']['laravel_version'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50">
                            <span class="text-[10px] text-zinc-400 block font-medium">Environment</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">{{ $systemStats['server_info']['environment'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50">
                            <span class="text-[10px] text-zinc-400 block font-medium">Database Driver</span>
                            <span class="font-bold text-zinc-800 dark:text-zinc-200 uppercase">{{ $systemStats['server_info']['db_driver'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50">
                            <span class="text-[10px] text-zinc-400 block font-medium">Cache Store</span>
                            <span class="font-bold text-zinc-800 dark:text-zinc-200 uppercase">{{ $systemStats['server_info']['cache_driver'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50">
                            <span class="text-[10px] text-zinc-400 block font-medium">Queue Driver</span>
                            <span class="font-bold text-zinc-800 dark:text-zinc-200 uppercase">{{ $systemStats['server_info']['queue_driver'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Recent Activity Feed & Security Posture -->
            <div class="space-y-4">
                <!-- Activity Logs Feed -->
                <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Audit Log Aktivitas Terkini</span>
                        </h2>
                        <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Semua Log &rarr;</a>
                    </div>
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($systemStats['recent_activities'] as $act)
                            <div class="py-2.5 flex items-start gap-2.5">
                                <div class="w-6 h-6 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">
                                    {{ strtoupper(substr($act->action ?? 'A', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-zinc-900 dark:text-white truncate">
                                            {{ $act->user->name ?? 'System' }}
                                        </span>
                                        <span class="text-[10px] text-zinc-400 flex-shrink-0">
                                            {{ $act->created_at ? $act->created_at->diffForHumans(null, true) : '-' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400 line-clamp-1 mt-0.5">
                                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">[{{ $act->action }}]</span> {{ $act->description }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-center text-xs text-zinc-400">Belum ada catatan aktivitas sistem.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Platform Security Posture Card -->
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-emerald-900/10 via-teal-900/10 to-zinc-900/10 dark:from-emerald-950/40 dark:via-teal-950/40 dark:to-zinc-900/60 border border-emerald-500/20 dark:border-emerald-500/30">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">
                            Status Keamanan & Isolasi Multi-Tenant
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-3 leading-relaxed">
                        Sistem berjalan dengan isolasi data tingkat pengguna (<span class="font-semibold text-zinc-800 dark:text-zinc-200">Strict Tenant Scoping</span>) dan perlindungan proteksi brute-force login.
                    </p>
                    <div class="grid grid-cols-3 gap-2 text-center text-[10px]">
                        <div class="p-2 rounded-xl bg-white/70 dark:bg-zinc-900/70 border border-emerald-200/50 dark:border-emerald-900/50 font-bold text-emerald-700 dark:text-emerald-300">
                            ✓ IDOR Protected
                        </div>
                        <div class="p-2 rounded-xl bg-white/70 dark:bg-zinc-900/70 border border-emerald-200/50 dark:border-emerald-900/50 font-bold text-emerald-700 dark:text-emerald-300">
                            ✓ 2FA OTP Active
                        </div>
                        <div class="p-2 rounded-xl bg-white/70 dark:bg-zinc-900/70 border border-emerald-200/50 dark:border-emerald-900/50 font-bold text-emerald-700 dark:text-emerald-300">
                            ✓ Session Sealed
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Personal Finance View Container (Always for Finance users, or when Personal tab is active for Super Admin) -->
    <div x-show="activeTab === 'personal'" class="space-y-4 sm:space-y-5">
    <!-- 1. Header & Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Dashboard Keuangan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Ringkasan kondisi finansial, saldo dompet, tren arus kas, dan kontrol anggaran.</p>
        </div>
        <div class="flex items-center gap-2 overflow-x-auto pb-0.5 sm:pb-0 scrollbar-none">
            @if(auth()->user() && auth()->user()->hasPermission('create-cash_transactions'))
            <button type="button" @click="quickModal = true"
                class="inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-semibold transition shadow-md shadow-emerald-500/20 whitespace-nowrap flex-shrink-0 cursor-pointer">
                + Catat Cepat
            </button>
            <a href="{{ route('admin.recurring_transactions.index') }}"
                class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition shadow-xs whitespace-nowrap flex-shrink-0">
                <svg class="w-3.5 h-3.5 mr-1.5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Rutin
            </a>
            @endif
            @if(auth()->user() && auth()->user()->hasPermission('view-cash_accounts'))
            <a href="{{ route('admin.cash_accounts.index') }}"
                class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition shadow-xs whitespace-nowrap flex-shrink-0">
                <svg class="w-3.5 h-3.5 mr-1.5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Dompet
            </a>
            @endif
            <a href="{{ route('admin.transaction_categories.index') }}"
                class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition shadow-xs whitespace-nowrap flex-shrink-0">
                <svg class="w-3.5 h-3.5 mr-1.5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Kategori
            </a>
        </div>
    </div>

    <!-- 2. Saldo Dompet & Rekening (Full Width Multi-Account Cards) -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Saldo Dompet & Rekening
            </h2>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-zinc-900 dark:text-white bg-indigo-50 dark:bg-indigo-950/60 px-2.5 py-0.5 rounded-lg border border-indigo-100 dark:border-indigo-900/50">
                <span class="text-zinc-500 dark:text-zinc-400 font-normal text-[11px]">Total Kas:</span>
                <span class="text-indigo-600 dark:text-indigo-400 font-bold text-xs">Rp {{ number_format($accountBalances['total_wealth'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3">
            @foreach($accountBalances['accounts'] as $acc)
                <div class="p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs relative overflow-hidden group hover:border-zinc-300 dark:hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 {{ $acc['type'] === 'bank' ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400' : ($acc['type'] === 'cash' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400') }}">
                            @if($acc['type'] === 'bank')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            @elseif($acc['type'] === 'cash')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                            {{ $acc['type'] === 'cash' ? 'Tunai' : ($acc['type'] === 'bank' ? 'Bank' : 'E-Wallet') }}
                        </span>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 truncate">{{ $acc['name'] }}</p>
                    <h3 class="text-xs sm:text-base font-extrabold tracking-tight mt-0.5 {{ $acc['current_balance'] >= 0 ? 'text-zinc-900 dark:text-white' : 'text-rose-600 dark:text-rose-400' }}">
                        Rp {{ number_format($acc['current_balance'], 0, ',', '.') }}
                    </h3>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. Date Range & Preset Filter Bar with Excel Export -->
    @include('admin.partials.financial-filter-bar', [
        'dateRange' => $dateRange,
        'route' => route('admin.dashboard'),
        'exportRoute' => route('admin.cash_transactions.export'),
        'showExport' => true
    ])

    <!-- 4. 4 Core Stat Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3">
        <!-- Stat 1: Total Saldo Kas (All-time) -->
        <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Total Arus Kas</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xs sm:text-lg font-extrabold tracking-tight {{ $balance['net_balance'] >= 0 ? 'text-zinc-900 dark:text-white' : 'text-rose-600 dark:text-rose-400' }}">
                    Rp {{ number_format($balance['net_balance'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                <span class="px-1.5 py-0.5 text-[9px] rounded-full font-semibold {{ $balance['net_balance'] >= 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' }}">
                    {{ $balance['net_balance'] >= 0 ? 'Surplus' : 'Defisit' }}
                </span>
                <span class="text-zinc-400 text-[10px]">{{ $totalTransactions }} Trx</span>
            </div>
        </div>

        <!-- Stat 2: Net Tabungan Periode Terpilih -->
        <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 truncate max-w-[100px] sm:max-w-[140px]" title="Arus Kas Bersih ({{ $filteredSummary['label'] }})">Net Tabungan</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xs sm:text-lg font-extrabold tracking-tight {{ $filteredSummary['net_savings'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    {{ $filteredSummary['net_savings'] >= 0 ? '+' : '' }}Rp {{ number_format($filteredSummary['net_savings'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                <span class="text-[10px] truncate max-w-[60px] sm:max-w-none">{{ $filteredSummary['label'] }}</span>
                <span class="text-[10px] font-semibold">{{ $filteredSummary['transaction_count'] }} Trx</span>
            </div>
        </div>

        <!-- Stat 3: Pemasukan Periode Terpilih -->
        <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Pemasukan</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xs sm:text-lg font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400">
                    +Rp {{ number_format($filteredSummary['total_income'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                <span class="text-[10px]">Total:</span>
                <span class="text-[10px] font-semibold text-zinc-700 dark:text-zinc-300 truncate">Rp {{ number_format($balance['total_income'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Stat 4: Pengeluaran Periode Terpilih -->
        <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Pengeluaran</span>
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xs sm:text-lg font-extrabold tracking-tight text-rose-600 dark:text-rose-400">
                    -Rp {{ number_format($filteredSummary['total_expense'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                <span class="text-[10px]">Total:</span>
                <span class="text-[10px] font-semibold text-zinc-700 dark:text-zinc-300 truncate">Rp {{ number_format($balance['total_expense'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- 5. Charts Row: Tren 6 Bulan (Left 7/12) & Komposisi Pengeluaran Donut (Right 5/12) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5 items-stretch">
        <!-- Tren Keuangan 6 Bulan -->
        <div class="lg:col-span-7 bg-white dark:bg-zinc-900 rounded-2xl shadow-xs border border-zinc-200/80 dark:border-zinc-800 p-4 sm:p-5 flex flex-col justify-between overflow-hidden">
            <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3 mb-2 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Tren Keuangan 6 Bulan Terakhir</h2>
                    <p class="text-xs text-zinc-500">Perbandingan pemasukan vs pengeluaran per bulan</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold">
                    <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pemasukan
                    </span>
                    <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 text-xs">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Pengeluaran
                    </span>
                </div>
            </div>
            <div class="pt-1">
                <div id="financialTrendChart" class="w-full h-56 sm:h-64"></div>
            </div>
        </div>

        <!-- Komposisi Pengeluaran Donut Chart -->
        <div class="lg:col-span-5 bg-white dark:bg-zinc-900 rounded-2xl shadow-xs border border-zinc-200/80 dark:border-zinc-800 p-4 sm:p-5 flex flex-col justify-between overflow-hidden">
            <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3 mb-2">
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Komposisi Pengeluaran</h2>
                <p class="text-xs text-zinc-500">Proporsi biaya {{ strtolower($filteredSummary['label']) }}</p>
            </div>
            <div class="flex flex-col items-center justify-center flex-1 py-1">
                @if(count($expenseBreakdown) > 0)
                    <div id="categoryDonutChart" class="w-full h-56 sm:h-64 flex justify-center"></div>
                @else
                    <div class="py-12 text-center text-zinc-400 text-xs">
                        <div class="w-9 h-9 mx-auto mb-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        </div>
                        Belum ada data pengeluaran pada periode ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 6. Target Anggaran (Left 7/12) & Pengeluaran Terbesar (Right 5/12) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5 items-stretch">
        <!-- Target Anggaran per Kategori (Budget Planner) -->
        <div class="lg:col-span-7 bg-white dark:bg-zinc-900 rounded-2xl shadow-xs border border-zinc-200/80 dark:border-zinc-800 p-4 sm:p-5 flex flex-col justify-between overflow-hidden">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-3.5 pb-2.5 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Target Anggaran Bulan Ini</h2>
                            <p class="text-xs text-zinc-500">Monitoring batas limit pengeluaran per kategori</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($budgetProgress['has_budgets'])
                            <div class="flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                                <span class="{{ $budgetProgress['actual_overall_percentage'] >= 100 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} font-bold">
                                    {{ $budgetProgress['total_spent_formatted'] }} / {{ $budgetProgress['total_budget_formatted'] }} ({{ $budgetProgress['actual_overall_percentage'] }}%)
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($budgetProgress['has_budgets'])
                    <!-- Overall Progress bar -->
                    <div class="mb-3 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Total Penggunaan Anggaran</span>
                            <span class="font-bold {{ $budgetProgress['actual_overall_percentage'] >= 100 ? 'text-rose-600 dark:text-rose-400' : ($budgetProgress['actual_overall_percentage'] >= 80 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                {{ $budgetProgress['total_spent_formatted'] }} dari {{ $budgetProgress['total_budget_formatted'] }} ({{ $budgetProgress['actual_overall_percentage'] }}%)
                            </span>
                        </div>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-2 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $budgetProgress['actual_overall_percentage'] >= 100 ? 'bg-rose-500' : ($budgetProgress['actual_overall_percentage'] >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                 style="width: {{ $budgetProgress['overall_percentage'] ?? $budgetProgress['percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Categories Progress Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-56 overflow-y-auto pr-0.5">
                        @foreach($budgetProgress['categories'] as $item)
                            <div class="p-2.5 rounded-xl border {{ $item['status'] === 'over' ? 'border-rose-200 dark:border-rose-900/50 bg-rose-50/40 dark:bg-rose-950/20' : ($item['status'] === 'warning' ? 'border-amber-200 dark:border-amber-900/50 bg-amber-50/40 dark:bg-amber-950/20' : 'border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900/90') }} shadow-xs">
                                <div class="flex items-center justify-between gap-1.5 mb-1.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        {!! \App\Helpers\CategoryIconHelper::renderBadge($item['category_icon'], 'expense', 'w-6 h-6') !!}
                                        <span class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $item['category_name'] }}</span>
                                    </div>
                                    @if($item['status'] === 'over')
                                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200/50 whitespace-nowrap">
                                            +{{ $item['over_amount_formatted'] }}
                                        </span>
                                    @else
                                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded {{ $item['status'] === 'warning' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300' }} whitespace-nowrap">
                                            {{ $item['actual_percentage'] }}%
                                        </span>
                                    @endif
                                </div>

                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full overflow-hidden mb-1.5">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $item['status'] === 'over' ? 'bg-rose-500' : ($item['status'] === 'warning' ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                         style="width: {{ $item['percentage'] }}%"></div>
                                </div>

                                <div class="flex items-center justify-between text-[10px] text-zinc-500 dark:text-zinc-400">
                                    <span>Pakai: <strong class="text-zinc-800 dark:text-zinc-200">{{ $item['spent_formatted'] }}</strong></span>
                                    <span>Limit: <strong class="text-zinc-800 dark:text-zinc-200">{{ $item['budget_limit_formatted'] }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center rounded-xl bg-zinc-50/60 dark:bg-zinc-800/30 border border-dashed border-zinc-200 dark:border-zinc-700/80">
                        <div class="w-8 h-8 mx-auto mb-1 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h4 class="text-xs font-bold text-zinc-800 dark:text-zinc-200">Belum Ada Target Anggaran</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto mt-0.5 mb-2.5">Tentukan batas pengeluaran bulanan per kategori untuk mengontrol pengeluaran harian Anda.</p>
                        <a href="{{ route('admin.category_budgets.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shadow-xs shadow-emerald-500/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>Mulai Atur Anggaran</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pengeluaran Terbesar (Top Spending) -->
        <div class="lg:col-span-5 bg-white dark:bg-zinc-900 rounded-2xl shadow-xs border border-zinc-200/80 dark:border-zinc-800 p-4 sm:p-5 flex flex-col justify-between overflow-hidden">
            <div>
                <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3 mb-3">
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white mb-0.5">Pengeluaran Terbesar</h2>
                    <p class="text-xs text-zinc-500">Kategori biaya tertinggi {{ strtolower($filteredSummary['label']) }}</p>
                </div>

                <div class="space-y-3">
                    @php
                        $barColors = ['bg-indigo-500', 'bg-amber-500', 'bg-emerald-500', 'bg-pink-500', 'bg-cyan-500', 'bg-purple-500'];
                        $badgeColors = [
                            'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400',
                            'bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400',
                            'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400',
                            'bg-pink-50 text-pink-600 dark:bg-pink-950/60 dark:text-pink-400',
                            'bg-cyan-50 text-cyan-600 dark:bg-cyan-950/60 dark:text-cyan-400',
                            'bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400',
                        ];
                    @endphp
                    @forelse(array_slice($expenseBreakdown, 0, 5) as $idx => $cat)
                        <div>
                            <div class="flex justify-between items-center text-xs mb-1">
                                <span class="font-medium text-zinc-700 dark:text-zinc-300 truncate max-w-[150px] text-xs">{{ $cat['category_name'] }}</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-zinc-900 dark:text-white font-semibold text-xs">Rp {{ number_format($cat['total_amount'], 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $badgeColors[$idx % count($badgeColors)] }}">{{ $cat['percentage'] }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-zinc-100 dark:bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $barColors[$idx % count($barColors)] }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $cat['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-zinc-400 text-xs">
                            Belum ada data pengeluaran.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Transaksi Kas Terbaru (Full Width Table) -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xs border border-zinc-200/80 dark:border-zinc-800 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
            <div>
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Transaksi {{ $filteredSummary['label'] }}</h2>
                <p class="text-xs text-zinc-500">Mutasi kas, pengeluaran, dan transfer pada periode terpilih</p>
            </div>
            <a href="{{ route('admin.cash_transactions.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                <span>Semua Mutasi</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="uppercase bg-zinc-50/80 dark:bg-zinc-800/60 text-zinc-500 dark:text-zinc-400 border-b border-zinc-100 dark:border-zinc-800 text-[11px]">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Kategori / Aktivitas</th>
                        <th class="px-4 py-3 font-semibold">Akun / Dompet</th>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 {{ $tx->type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400' : ($tx->type === 'expense' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400') }}">
                                        @if($tx->type === 'income')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                        @elseif($tx->type === 'expense')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-zinc-900 dark:text-white text-xs">
                                            @if($tx->type === 'transfer')
                                                Pindah Dana / Transfer
                                            @else
                                                {{ $tx->category->name ?? 'Kategori Lain' }}
                                            @endif
                                        </p>
                                        @if($tx->note)
                                            <p class="text-[11px] text-zinc-400">{{ $tx->note }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs">
                                @if($tx->type === 'transfer')
                                    <span class="font-medium text-rose-600 dark:text-rose-400">{{ $tx->account->name ?? 'Bank' }}</span>
                                    <span class="text-zinc-400 mx-1">➔</span>
                                    <span class="font-medium text-emerald-600 dark:text-emerald-400">{{ $tx->toAccount->name ?? 'Cash' }}</span>
                                @else
                                    <span class="text-zinc-600 dark:text-zinc-300 font-medium">{{ $tx->account->name ?? 'Kas Utama' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $tx->transaction_date ? \Carbon\Carbon::parse($tx->transaction_date)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-bold text-xs {{ $tx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($tx->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-indigo-600 dark:text-indigo-400') }}">
                                {{ $tx->type === 'income' ? '+' : ($tx->type === 'expense' ? '-' : '') }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-zinc-400 text-xs">
                                Tidak ada transaksi kas pada periode <strong>{{ $filteredSummary['label'] }}</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="block md:hidden divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($recentTransactions as $tx)
                <div class="p-3 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 {{ $tx->type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400' : ($tx->type === 'expense' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400') }}">
                            @if($tx->type === 'income')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            @elseif($tx->type === 'expense')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-zinc-900 dark:text-white truncate">
                                @if($tx->type === 'transfer')
                                    Pindah: {{ $tx->account->name ?? 'Bank' }} ➔ {{ $tx->toAccount->name ?? 'Kas' }}
                                @else
                                    {{ $tx->category->name ?? 'Kategori Lain' }}
                                @endif
                            </p>
                            <div class="flex items-center gap-1 text-[10px] text-zinc-400 truncate">
                                <span>{{ $tx->transaction_date ? \Carbon\Carbon::parse($tx->transaction_date)->translatedFormat('d M') : '-' }}</span>
                                @if($tx->note)
                                    <span>•</span>
                                    <span class="truncate max-w-[90px]">{{ $tx->note }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs font-bold {{ $tx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($tx->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-indigo-600 dark:text-indigo-400') }}">
                            {{ $tx->type === 'income' ? '+' : ($tx->type === 'expense' ? '-' : '') }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center text-zinc-400 text-xs">
                    Tidak ada transaksi kas pada periode ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- 8. Rekap Tabungan 12 Bulan (Responsive Desktop Table + Mobile Cards) -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl shadow-xs border border-zinc-200/80 dark:border-zinc-800 overflow-hidden">
        <div class="p-3.5 sm:p-5 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center bg-zinc-50/50 dark:bg-zinc-800/30">
            <div>
                <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Rekap Tabungan Bulanan</span>
                </h2>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">Surplus / defisit dan rasio tabungan 12 bulan terakhir</p>
            </div>
        </div>

        <!-- Desktop View (Full Table) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="uppercase bg-zinc-50/80 dark:bg-zinc-800/60 text-zinc-500 dark:text-zinc-400 border-b border-zinc-100 dark:border-zinc-800 text-[11px]">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Bulan</th>
                        <th class="px-4 py-3 font-semibold text-right">Pemasukan</th>
                        <th class="px-4 py-3 font-semibold text-right">Pengeluaran</th>
                        <th class="px-4 py-3 font-semibold text-right">Net Tabungan</th>
                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                        <th class="px-4 py-3 font-semibold text-center">Savings Rate</th>
                        <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($monthlyRecap as $row)
                        @php
                            $hasActivity = $row['total_income'] > 0 || $row['total_expense'] > 0;
                        @endphp
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition-colors {{ $row['is_current_month'] ? 'bg-emerald-50/30 dark:bg-emerald-950/20' : '' }}">
                            <td class="px-4 py-3 font-semibold text-zinc-900 dark:text-white whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <span>{{ $row['month_name'] }}</span>
                                    @if($row['is_current_month'])
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">Bulan Ini</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-medium text-emerald-600 dark:text-emerald-400">
                                {{ $row['total_income'] > 0 ? '+Rp ' . number_format($row['total_income'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-medium text-rose-600 dark:text-rose-400">
                                {{ $row['total_expense'] > 0 ? '-Rp ' . number_format($row['total_expense'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-bold {{ $row['net_savings'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : ($row['net_savings'] < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-400') }}">
                                {{ $hasActivity ? ($row['net_savings'] > 0 ? '+' : '') . 'Rp ' . number_format($row['net_savings'], 0, ',', '.') : 'Rp 0' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @if(!$hasActivity)
                                    <span class="px-2 py-0.5 text-[9px] font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500">Nihil</span>
                                @elseif($row['is_surplus'])
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[9px] font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">Surplus</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[9px] font-bold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">Defisit</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @if($row['total_income'] > 0)
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-14 bg-zinc-100 dark:bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                                            <div class="{{ $row['savings_rate'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} h-1.5 rounded-full" style="width: {{ max(0, min(100, $row['savings_rate'])) }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-bold {{ $row['savings_rate'] >= 20 ? 'text-emerald-600 dark:text-emerald-400' : ($row['savings_rate'] >= 0 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                                            {{ $row['savings_rate'] }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="text-zinc-400 text-[10px]">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @if($hasActivity)
                                    <a href="{{ route('admin.cash_transactions.index', ['month' => $row['month'], 'year' => $row['year']]) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition"
                                       title="Lihat transaksi bulan {{ $row['month_name'] }}">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-zinc-300 dark:text-zinc-700 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile View (Compact Month Cards) -->
        <div class="block md:hidden divide-y divide-zinc-100 dark:divide-zinc-800">
            @foreach($monthlyRecap as $row)
                @php
                    $hasActivity = $row['total_income'] > 0 || $row['total_expense'] > 0;
                @endphp
                <div class="p-3 {{ $row['is_current_month'] ? 'bg-emerald-50/30 dark:bg-emerald-950/20' : '' }} space-y-1.5">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-zinc-900 dark:text-white">{{ $row['month_name'] }}</span>
                            @if($row['is_current_month'])
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">Bulan Ini</span>
                            @endif
                        </div>

                        <div>
                            @if(!$hasActivity)
                                <span class="px-1.5 py-0.5 text-[9px] font-semibold rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-400">Nihil</span>
                            @elseif($row['is_surplus'])
                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">Surplus</span>
                            @else
                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">Defisit</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <div class="text-[11px] text-zinc-400 space-x-1.5">
                            <span>Masuk: <strong class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ $row['total_income'] > 0 ? 'Rp ' . number_format($row['total_income'], 0, ',', '.') : '-' }}</strong></span>
                            <span>•</span>
                            <span>Keluar: <strong class="text-rose-600 dark:text-rose-400 font-semibold">{{ $row['total_expense'] > 0 ? 'Rp ' . number_format($row['total_expense'], 0, ',', '.') : '-' }}</strong></span>
                        </div>

                        <div class="text-right font-bold {{ $row['net_savings'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : ($row['net_savings'] < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-400') }}">
                            {{ $hasActivity ? ($row['net_savings'] > 0 ? '+' : '') . 'Rp ' . number_format($row['net_savings'], 0, ',', '.') : 'Rp 0' }}
                        </div>
                    </div>

                    @if($hasActivity && $row['total_income'] > 0)
                        <div class="flex items-center justify-between gap-2 pt-1">
                            <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                <span class="text-[10px] text-zinc-400 flex-shrink-0">Savings:</span>
                                <div class="flex-1 bg-zinc-100 dark:bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $row['savings_rate'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} h-1.5 rounded-full" style="width: {{ max(0, min(100, $row['savings_rate'])) }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold {{ $row['savings_rate'] >= 20 ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-600 dark:text-zinc-300' }}">{{ $row['savings_rate'] }}%</span>
                            </div>

                            <a href="{{ route('admin.cash_transactions.index', ['month' => $row['month'], 'year' => $row['year']]) }}"
                               class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex-shrink-0">
                                Lihat Transaksi →
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    </div>
    <!-- End Personal Finance View Container -->

    <!-- 1. Modal Quick Add Transaction -->
    <template x-teleport="body">
        <div x-show="quickModal" style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="quickModal = false"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6">
            <div @click.away="quickModal = false"
                 x-show="quickModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-lg bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden my-auto">

                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Catat Transaksi Cepat</h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Input mutasi kas dalam hitungan detik</p>
                        </div>
                    </div>
                    <button type="button" @click="quickModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Form -->
                <form action="{{ route('admin.cash_transactions.store') }}" method="POST" enctype="multipart/form-data" x-data="ajaxForm" @submit.prevent="submit" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ route('admin.dashboard', ['view' => 'personal']) }}">

                    <!-- Switcher Type -->
                    <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs font-semibold w-full">
                        <button type="button" @click="quickType = 'expense'"
                                :class="quickType === 'expense' ? 'bg-rose-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                                class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer">
                            Pengeluaran
                        </button>
                        <button type="button" @click="quickType = 'income'"
                                :class="quickType === 'income' ? 'bg-emerald-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                                class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer">
                            Pemasukan
                        </button>
                        <button type="button" @click="quickType = 'transfer'"
                                :class="quickType === 'transfer' ? 'bg-indigo-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                                class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer">
                            Transfer / Pindah Kas
                        </button>
                    </div>
                    <input type="hidden" name="type" :value="quickType">

                    <!-- Nominal -->
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
                        <input type="text" name="amount" required placeholder="0"
                               class="w-full text-base font-bold px-3.5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500"
                               onkeyup="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')">
                    </div>

                    <!-- Kategori (Expense / Income) -->
                    <div x-show="quickType !== 'transfer'">
                        <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1">Kategori <span class="text-rose-500">*</span></label>

                        <div x-show="quickType === 'expense'">
                            <select name="category_id" class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/30" :disabled="quickType !== 'expense'">
                                <option value="">Pilih Kategori Pengeluaran...</option>
                                @foreach($expenseCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="quickType === 'income'" style="display: none;">
                            <select name="category_id" class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30" :disabled="quickType !== 'income'">
                                <option value="">Pilih Kategori Pemasukan...</option>
                                @foreach($incomeCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Dompet / Akun Asal & Tujuan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1" x-text="quickType === 'transfer' ? 'Dari Akun Asal *' : (quickType === 'expense' ? 'Dari Dompet *' : 'Ke Dompet *')">Dompet</label>
                            <select name="account_id" required class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                                @foreach($cashAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="quickType === 'transfer'" style="display: none;">
                            <label class="block text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1">Ke Akun Tujuan *</label>
                            <select name="to_account_id" class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30" :disabled="quickType !== 'transfer'">
                                <option value="">Pilih Akun Tujuan...</option>
                                @foreach($cashAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div :class="quickType === 'transfer' ? 'col-span-2' : ''">
                            <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1">Tanggal *</label>
                            <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}"
                                   class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1">Catatan (Opsional)</label>
                        <input type="text" name="note" placeholder="Keterangan transaksi..."
                               class="w-full text-xs px-3 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                    </div>

                    <!-- Bukti / Struk / Nota (Upload) -->
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1 flex items-center justify-between">
                            <span>Bukti / Struk Transaksi</span>
                            <span class="text-[10px] text-zinc-400 font-normal normal-case">(Opsional - JPG, PNG, WEBP, PDF maks 10MB)</span>
                        </label>
                        <div class="flex items-start gap-3">
                            <label class="flex-1 flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700 hover:border-emerald-500 dark:hover:border-emerald-500 bg-zinc-50/50 dark:bg-zinc-800/40 cursor-pointer transition group">
                                <input type="file" name="proof" id="quick_proof_input" accept="image/*,application/pdf" class="hidden" @change="handleProofChange($event)">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-zinc-800 dark:text-zinc-200 truncate" x-text="quickProofName || 'Upload foto struk / nota'"></p>
                                    <p class="text-[10px] text-zinc-400">Klik untuk memilih file struk</p>
                                </div>
                            </label>

                            <!-- Preview Box -->
                            <div x-show="quickProofPreview || quickIsPdf" style="display: none;" class="w-14 h-12 relative rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 overflow-hidden flex-shrink-0 flex items-center justify-center shadow-xs">
                                <template x-if="quickProofPreview && !quickIsPdf">
                                    <img :src="quickProofPreview" alt="Struk" class="w-full h-full object-cover">
                                </template>
                                <template x-if="quickIsPdf">
                                    <div class="flex flex-col items-center justify-center text-rose-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <span class="text-[8px] font-bold">PDF</span>
                                    </div>
                                </template>
                                <button type="button" @click="removeProof()" class="absolute top-0.5 right-0.5 p-0.5 bg-black/70 hover:bg-rose-600 text-white rounded cursor-pointer transition-colors" title="Hapus Bukti">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                        <button type="button" @click="quickModal = false" class="px-4 py-2 text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" :disabled="loading" class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 rounded-xl shadow-md shadow-emerald-500/20 cursor-pointer disabled:opacity-50">
                            <svg x-show="loading" class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="loading ? 'Menyimpan...' : 'Simpan Transaksi'">Simpan Transaksi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Area Chart: Tren 6 Bulan
    const isDark = document.documentElement.classList.contains('dark');
    const monthlyTrends = @json($monthlyTrends);
    const months = monthlyTrends.map(item => item.month_name);
    const incomeData = monthlyTrends.map(item => item.income);
    const expenseData = monthlyTrends.map(item => item.expense);

    const trendOptions = {
        series: [
            { name: 'Pemasukan', data: incomeData },
            { name: 'Pengeluaran', data: expenseData }
        ],
        chart: {
            type: 'area',
            height: '100%',
            fontFamily: 'inherit',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        colors: ['#10B981', '#F43F5E'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 95, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        xaxis: {
            categories: months,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: isDark ? '#9CA3AF' : '#6B7280', fontSize: '11px', fontWeight: 500 }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                    if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'K';
                    return 'Rp ' + val;
                },
                style: { colors: isDark ? '#9CA3AF' : '#6B7280', fontSize: '10px' }
            }
        },
        grid: {
            borderColor: isDark ? '#27272A' : '#F4F4F5',
            strokeDashArray: 4,
            padding: { top: 0, right: 0, bottom: 0, left: 10 }
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: {
                formatter: function(val) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                }
            }
        },
        legend: { show: false }
    };

    let trendChart = new ApexCharts(document.querySelector("#financialTrendChart"), trendOptions);
    trendChart.render();

    // 2. Donut Chart: Komposisi Pengeluaran
    const expenseCategories = @json($expenseBreakdown);
    if (expenseCategories.length > 0) {
        const donutSeries = expenseCategories.map(c => Number(c.total_amount));
        const donutLabels = expenseCategories.map(c => c.category_name);

        const donutOptions = {
            series: donutSeries,
            labels: donutLabels,
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'inherit'
            },
            colors: [
                '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6',
                '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', '#EC4899',
                '#F43F5E', '#F97316', '#EAB308', '#84CC16', '#22C55E'
            ],
            legend: {
                position: 'bottom',
                fontSize: '10px',
                labels: { colors: isDark ? '#A1A1AA' : '#52525B' }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '11px',
                                fontWeight: 600,
                                color: isDark ? '#A1A1AA' : '#71717A',
                                offsetY: -4
                            },
                            value: {
                                show: true,
                                fontSize: '14px',
                                fontWeight: 700,
                                color: isDark ? '#FFFFFF' : '#18181B',
                                offsetY: 4,
                                formatter: function(val) {
                                    const num = Number(val);
                                    if (num >= 1000000) return 'Rp ' + (num / 1000000).toFixed(1) + 'M';
                                    if (num >= 1000) return 'Rp ' + (num / 1000).toFixed(0) + 'K';
                                    return 'Rp ' + num;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '11px',
                                fontWeight: 600,
                                color: isDark ? '#A1A1AA' : '#71717A',
                                formatter: function(w) {
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    if (total >= 1000000) return 'Rp ' + (total / 1000000).toFixed(1) + 'M';
                                    if (total >= 1000) return 'Rp ' + (total / 1000).toFixed(0) + 'K';
                                    return 'Rp ' + total;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function(val) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            }
        };

        let donutChart = new ApexCharts(document.querySelector("#categoryDonutChart"), donutOptions);
        donutChart.render();
    }

    // 3. User Registration Growth Chart (Super Admin)
    let userRegChart = null;
    @if($isSuperAdmin && isset($systemStats['user_growth_trends']))
    const userGrowthTrends = @json($systemStats['user_growth_trends']);
    if (document.querySelector("#userRegistrationChart") && userGrowthTrends.length > 0) {
        const userMonths = userGrowthTrends.map(item => item.month_name);
        const userCounts = userGrowthTrends.map(item => item.new_users);

        const userRegistrationOptions = {
            series: [{
                name: 'Pengguna Baru',
                data: userCounts
            }],
            chart: {
                type: 'bar',
                height: '100%',
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '38%',
                    distributed: false,
                }
            },
            colors: ['#6366F1'],
            dataLabels: { enabled: false },
            xaxis: {
                categories: userMonths,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: isDark ? '#9CA3AF' : '#6B7280', fontSize: '11px', fontWeight: 500 }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) { return Math.round(val); },
                    style: { colors: isDark ? '#9CA3AF' : '#6B7280', fontSize: '10px' }
                }
            },
            grid: {
                borderColor: isDark ? '#27272A' : '#F4F4F5',
                strokeDashArray: 4,
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function(val) { return val + ' Pengguna Finance'; }
                }
            }
        };

        userRegChart = new ApexCharts(document.querySelector("#userRegistrationChart"), userRegistrationOptions);
        userRegChart.render();
    }
    @endif

    function updateChartsTheme(dark) {
        if (trendChart) {
            trendChart.updateOptions({
                xaxis: { labels: { style: { colors: dark ? '#9CA3AF' : '#6B7280' } } },
                yaxis: { labels: { style: { colors: dark ? '#9CA3AF' : '#6B7280' } } },
                grid: { borderColor: dark ? '#27272A' : '#F4F4F5' },
                tooltip: { theme: dark ? 'dark' : 'light' }
            }, false, false);
        }
        if (typeof donutChart !== 'undefined' && donutChart) {
            donutChart.updateOptions({
                legend: { labels: { colors: dark ? '#A1A1AA' : '#52525B' } },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                name: { color: dark ? '#A1A1AA' : '#71717A' },
                                value: { color: dark ? '#FFFFFF' : '#18181B' },
                                total: { color: dark ? '#A1A1AA' : '#71717A' }
                            }
                        }
                    }
                },
                tooltip: { theme: dark ? 'dark' : 'light' }
            }, false, false);
        }
        if (userRegChart) {
            userRegChart.updateOptions({
                xaxis: { labels: { style: { colors: dark ? '#9CA3AF' : '#6B7280' } } },
                yaxis: { labels: { style: { colors: dark ? '#9CA3AF' : '#6B7280' } } },
                grid: { borderColor: dark ? '#27272A' : '#F4F4F5' },
                tooltip: { theme: dark ? 'dark' : 'light' }
            }, false, false);
        }
    }

    window.addEventListener('theme-changed', (e) => {
        updateChartsTheme(e.detail.isDark);
    });

    const themeObserver = new MutationObserver(() => {
        const dark = document.documentElement.classList.contains('dark');
        updateChartsTheme(dark);
    });
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});
</script>
@endpush
