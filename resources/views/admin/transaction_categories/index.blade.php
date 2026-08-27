@extends('admin.layouts.app')

@section('title', 'Kategori Transaksi')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Kategori Transaksi</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Kelola master kategori pemasukan dan pengeluaran untuk pengelompokan mutasi kas.</p>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user() && auth()->user()->hasPermission('create-transaction_categories'))
            <a href="{{ route('admin.transaction_categories.create') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl font-semibold text-xs shadow-sm shadow-emerald-500/20 hover:scale-[1.02] transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Kategori</span>
            </a>
            @endif
        </div>
    </div>

    <!-- DataTable -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-2 sm:p-3 shadow-xs overflow-hidden">
        <livewire:tables.transaction-category-table />
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-confirm-delete-modal title="Hapus Kategori Transaksi"
    message="Apakah Anda yakin ingin menghapus kategori ini? Data transaksi terkait mungkin terdampak." />
@endsection
