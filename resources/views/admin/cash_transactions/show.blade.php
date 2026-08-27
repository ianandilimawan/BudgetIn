@extends('admin.layouts.app')

@section('title', 'Detail Transaksi Kas')

@section('content')
<div class="space-y-6" x-data="{ showImageModal: false }">
    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cash_transactions.index') }}" class="p-2 bg-white dark:bg-zinc-800 rounded-xl shadow-xs border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-indigo-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <nav class="flex text-xs text-zinc-500 font-medium mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.cash_transactions.index') }}" class="hover:text-indigo-600 transition-colors">Pencatatan Keuangan</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 text-zinc-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span class="text-zinc-400">Detail Transaksi</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl lg:text-2xl font-bold text-zinc-900 dark:text-white">Detail Transaksi #TRX-{{ str_pad($cashTransaction->id, 5, '0', STR_PAD_LEFT) }}</h1>
            </div>
        </div>

        @if (auth()->user() && auth()->user()->hasPermission('edit-cash_transactions'))
            <a href="{{ route('admin.cash_transactions.edit', $cashTransaction) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition text-sm font-semibold shadow-xs">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Transaksi
            </a>
        @endif
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="{{ $cashTransaction->proof ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-2xl shadow-sm border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold {{ $cashTransaction->type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400' : ($cashTransaction->type === 'expense' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400') }}">
                            @if($cashTransaction->type === 'income')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            @elseif($cashTransaction->type === 'expense')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold {{ $cashTransaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($cashTransaction->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-indigo-600 dark:text-indigo-400') }}">
                                {{ $cashTransaction->type === 'income' ? '+' : ($cashTransaction->type === 'expense' ? '-' : '') }}Rp {{ number_format($cashTransaction->amount, 0, ',', '.') }}
                            </h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cashTransaction->type === 'income' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : ($cashTransaction->type === 'expense' ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300') }}">
                                {{ $cashTransaction->type === 'income' ? 'Pemasukan' : ($cashTransaction->type === 'expense' ? 'Pengeluaran' : 'Tarik Tunai / Transfer') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border border-zinc-100 dark:border-zinc-800 rounded-xl overflow-hidden divide-y divide-zinc-100 dark:divide-zinc-800">
                    <dl class="mb-0 text-sm">
                        <!-- Kategori / Alur Transfer -->
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">
                                {{ $cashTransaction->type === 'transfer' ? 'Alur Transfer' : 'Kategori' }}
                            </dt>
                            <dd class="mt-1 sm:mt-0 sm:w-2/3 font-semibold text-zinc-900 dark:text-white">
                                @if($cashTransaction->type === 'transfer')
                                    <span class="text-rose-600 dark:text-rose-400">{{ $cashTransaction->account->name ?? 'Bank' }}</span>
                                    <span class="text-zinc-400 mx-1.5">➔</span>
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ $cashTransaction->toAccount->name ?? 'Cash' }}</span>
                                @else
                                    {{ $cashTransaction->category->name ?? '-' }}
                                @endif
                            </dd>
                        </div>

                        <!-- Dompet / Akun (Untuk non-transfer) -->
                        @if($cashTransaction->type !== 'transfer')
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Dompet / Akun Terkait</dt>
                            <dd class="mt-1 sm:mt-0 sm:w-2/3 font-semibold text-zinc-900 dark:text-white">
                                {{ $cashTransaction->account->name ?? 'Kas Utama' }}
                            </dd>
                        </div>
                        @endif

                        <!-- Tanggal Transaksi -->
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Tanggal Transaksi</dt>
                            <dd class="mt-1 sm:mt-0 sm:w-2/3 font-semibold text-zinc-900 dark:text-white">
                                {{ $cashTransaction->transaction_date ? \Carbon\Carbon::parse($cashTransaction->transaction_date)->translatedFormat('l, d F Y') : '-' }}
                            </dd>
                        </div>

                        <!-- Catatan -->
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Catatan</dt>
                            <dd class="mt-1 sm:mt-0 sm:w-2/3 text-zinc-800 dark:text-zinc-200">
                                {{ $cashTransaction->note ?: '-' }}
                            </dd>
                        </div>

                        <!-- Dicatat Oleh / User -->
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Dicatat Oleh (User)</dt>
                            <dd class="mt-1 sm:mt-0 sm:w-2/3 font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-[10px] font-bold uppercase">
                                    {{ substr($cashTransaction->user->name ?? 'A', 0, 1) }}
                                </span>
                                <span>{{ $cashTransaction->user->name ?? 'Admin / System' }}</span>
                                @if($cashTransaction->user)
                                    <span class="text-xs text-zinc-400 font-normal">({{ $cashTransaction->user->email }})</span>
                                @endif
                            </dd>
                        </div>

                        <!-- Waktu Input -->
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <dt class="sm:w-1/3 font-medium text-zinc-500 dark:text-zinc-400">Waktu Input Sistem</dt>
                            <dd class="mt-1 sm:mt-0 sm:w-2/3 text-zinc-700 dark:text-zinc-300">
                                {{ $cashTransaction->created_at ? $cashTransaction->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Proof / Bukti Attachment Card (if present) -->
        @if($cashTransaction->proof)
        <div class="lg:col-span-1 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-2xl shadow-sm border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Bukti Transaksi
                    </h3>
                    <a href="{{ $cashTransaction->proof_url }}" target="_blank" download class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh
                    </a>
                </div>

                @php
                    $isPdf = str_ends_with(strtolower($cashTransaction->proof), '.pdf');
                @endphp

                @if($isPdf)
                    <div class="p-6 text-center bg-zinc-50 dark:bg-zinc-800/60 rounded-xl border border-zinc-200/80 dark:border-zinc-700/80">
                        <svg class="w-16 h-16 text-rose-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <p class="text-xs font-bold text-zinc-900 dark:text-white mb-1">Dokumen PDF Terlampir</p>
                        <p class="text-[11px] text-zinc-400 mb-4 truncate">{{ basename($cashTransaction->proof) }}</p>
                        <a href="{{ $cashTransaction->proof_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Buka File PDF
                        </a>
                    </div>
                @else
                    <div class="relative group rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 cursor-pointer" @click="showImageModal = true">
                        <img src="{{ $cashTransaction->proof_url }}" alt="Bukti Struk Transaksi" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-semibold text-xs gap-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                            Klik untuk Perbesar
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Image Zoom Modal -->
    @if($cashTransaction->proof && !str_ends_with(strtolower($cashTransaction->proof), '.pdf'))
    <div x-show="showImageModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center">
            <div x-show="showImageModal" @click="showImageModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"></div>
            <div x-show="showImageModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-zinc-900 rounded-2xl max-w-3xl w-full p-2 overflow-hidden shadow-2xl z-10">
                <div class="flex items-center justify-between p-3 border-b border-zinc-800 text-white">
                    <span class="text-xs font-semibold">Bukti Struk Transaksi #TRX-{{ str_pad($cashTransaction->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <button type="button" @click="showImageModal = false" class="p-1 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-2 max-h-[80vh] overflow-auto flex items-center justify-center">
                    <img src="{{ $cashTransaction->proof_url }}" alt="Bukti Transaksi Full" class="max-w-full max-h-[75vh] rounded-lg object-contain">
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
