@extends('admin.layouts.app')

@section('title', 'Detail Dompet / Rekening')

@section('content')
<div class="space-y-6">
    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cash_accounts.index') }}" class="p-2 bg-white dark:bg-zinc-800 rounded-xl shadow-xs border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-indigo-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <nav class="flex text-xs text-zinc-500 font-medium mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.cash_accounts.index') }}" class="hover:text-indigo-600 transition-colors">Manajemen Dompet</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 text-zinc-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span class="text-zinc-400">Detail</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl lg:text-2xl font-bold text-zinc-900 dark:text-white">{{ $cashAccount->name }}</h1>
            </div>
        </div>

        @if (auth()->user() && auth()->user()->hasPermission('edit-cash_accounts'))
            <a href="{{ route('admin.cash_accounts.edit', $cashAccount) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition text-sm font-semibold shadow-xs">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Dompet
            </a>
        @endif
    </div>

    <!-- Live Card Visual -->
    <div class="p-6 rounded-2xl border shadow-sm {{ $cashAccount->color === 'blue' ? 'bg-blue-50/80 dark:bg-blue-950/40 border-blue-200 dark:border-blue-800' : ($cashAccount->color === 'indigo' ? 'bg-indigo-50/80 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800' : ($cashAccount->color === 'purple' ? 'bg-purple-50/80 dark:bg-purple-950/40 border-purple-200 dark:border-purple-800' : ($cashAccount->color === 'rose' ? 'bg-rose-50/80 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800' : ($cashAccount->color === 'amber' ? 'bg-amber-50/80 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800' : ($cashAccount->color === 'cyan' ? 'bg-cyan-50/80 dark:bg-cyan-950/40 border-cyan-200 dark:border-cyan-800' : ($cashAccount->color === 'zinc' ? 'bg-zinc-100 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700' : 'bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800')))))) }}">
        <div class="flex items-start justify-between">
            <div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wide uppercase bg-black/10 dark:bg-white/10 text-zinc-900 dark:text-white">
                    {{ $cashAccount->type_name }}
                </span>
                <h2 class="text-2xl font-black text-zinc-900 dark:text-white mt-2">{{ $cashAccount->name }}</h2>
                <p class="text-xs text-zinc-600 dark:text-zinc-300 font-mono mt-0.5">{{ $cashAccount->account_number ?: 'Tanpa nomor rekening' }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Saldo Awal Terdaftar</span>
                <p class="text-2xl font-extrabold text-zinc-900 dark:text-white mt-1">Rp {{ number_format($cashAccount->initial_balance, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-2xl shadow-sm border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
        <div class="p-6 sm:p-8">
            <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-4">Informasi Lengkap</h3>

            <div class="border border-zinc-100 dark:border-zinc-800 rounded-xl overflow-hidden divide-y divide-zinc-100 dark:divide-zinc-800">
                <dl class="mb-0 text-sm">
                    <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                        <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Nama Dompet</dt>
                        <dd class="mt-1 sm:mt-0 sm:w-2/3 font-semibold text-zinc-900 dark:text-white">{{ $cashAccount->name }}</dd>
                    </div>
                    <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                        <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Tipe Akun</dt>
                        <dd class="mt-1 sm:mt-0 sm:w-2/3 font-semibold text-zinc-900 dark:text-white">{{ $cashAccount->type_name }}</dd>
                    </div>
                    <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                        <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Nomor Rekening / Catatan</dt>
                        <dd class="mt-1 sm:mt-0 sm:w-2/3 font-mono text-zinc-800 dark:text-zinc-200">{{ $cashAccount->account_number ?: '-' }}</dd>
                    </div>
                    <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                        <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Warna Tema</dt>
                        <dd class="mt-1 sm:mt-0 sm:w-2/3 font-semibold text-zinc-900 dark:text-white capitalize">{{ $cashAccount->color ?: 'emerald' }}</dd>
                    </div>
                    <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                        <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Status Akun</dt>
                        <dd class="mt-1 sm:mt-0 sm:w-2/3">
                            @if($cashAccount->is_active)
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">Aktif</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">Non-Aktif</span>
                            @endif
                        </dd>
                    </div>
                    <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                        <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Dibuat Pada</dt>
                        <dd class="mt-1 sm:mt-0 sm:w-2/3 text-zinc-700 dark:text-zinc-300">{{ $cashAccount->created_at ? $cashAccount->created_at->translatedFormat('d M Y, H:i') : '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
