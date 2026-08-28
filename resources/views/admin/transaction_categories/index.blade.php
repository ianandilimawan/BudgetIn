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

    <!-- Desktop View (PowerGrid Table) -->
    <div class="hidden md:block bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-2 sm:p-3 shadow-xs overflow-hidden">
        <livewire:tables.transaction-category-table />
    </div>

    <!-- Mobile View (Category Cards with Tabs) -->
    <div class="block md:hidden space-y-2.5" x-data="{ activeTab: 'all' }">
        <!-- Tab Filters -->
        <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs font-semibold w-full">
            <button type="button" @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer text-[11px]">
                Semua ({{ count($categories) }})
            </button>
            <button type="button" @click="activeTab = 'expense'"
                    :class="activeTab === 'expense' ? 'bg-rose-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer text-[11px]">
                Pengeluaran ({{ $expenseCount }})
            </button>
            <button type="button" @click="activeTab = 'income'"
                    :class="activeTab === 'income' ? 'bg-emerald-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer text-[11px]">
                Pemasukan ({{ $incomeCount }})
            </button>
        </div>

        <!-- Cards List -->
        <div class="space-y-2">
            @forelse($categories as $cat)
                <div x-show="activeTab === 'all' || activeTab === '{{ $cat->type }}'"
                     class="p-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-2xs flex items-center justify-between gap-2.5">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            {!! \App\Helpers\CategoryIconHelper::renderBadge($cat->icon, $cat->type, 'w-8 h-8') !!}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $cat->name }}</h3>
                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded {{ $cat->type === 'expense' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/60' }}">
                                    {{ $cat->type === 'expense' ? 'Pengeluaran' : 'Pemasukan' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] text-zinc-400 mt-0.5">
                                <span>{{ $cat->transactions_count }} transaksi</span>
                                <span>•</span>
                                @if($cat->is_system || $cat->user_id === null)
                                    <span class="inline-flex items-center gap-1 text-zinc-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        <span>Bawaan Sistem</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span>Kustom</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1 flex-shrink-0">
                        @if($cat->is_system || $cat->user_id === null)
                            <span class="px-2 py-1 text-[10px] font-medium text-zinc-400 dark:text-zinc-500 flex items-center gap-0.5 select-none" title="Kategori bawaan sistem">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                        @else
                            @if(auth()->user() && auth()->user()->hasPermission('edit-transaction_categories'))
                                <a href="{{ route('admin.transaction_categories.edit', $cat->id) }}" class="p-1.5 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            @endif
                            @if(auth()->user() && auth()->user()->hasPermission('delete-transaction_categories'))
                                <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { action: '{{ route('admin.transaction_categories.destroy', $cat->id) }}' } }))"
                                        class="p-1.5 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-5 text-center bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200/80 dark:border-zinc-800 text-zinc-400 text-xs">
                    Belum ada kategori transaksi.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-confirm-delete-modal title="Hapus Kategori Transaksi"
    message="Apakah Anda yakin ingin menghapus kategori ini? Data transaksi terkait mungkin terdampak." />
@endsection
