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

    <!-- Desktop View (PowerGrid Table) -->
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 shadow-xs overflow-hidden">
        <livewire:tables.recurring-transaction-table />
    </div>

    <!-- Mobile View (Recurring Schedule Cards) -->
    <div class="block md:hidden space-y-2.5">
        @forelse($mobileRecurring as $item)
            <div class="p-3.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-2xs space-y-2.5">
                <!-- Card Header -->
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $item->type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400' : ($item->type === 'expense' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400') }}">
                            @if ($item->type === 'income')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            @elseif ($item->type === 'expense')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $item->name }}</h3>
                            <div class="flex items-center gap-1.5 text-[10px] text-zinc-400 mt-0.5">
                                @if($item->type === 'transfer')
                                    <span>{{ $item->account->name ?? 'Bank' }} ➔ {{ $item->toAccount->name ?? 'Kas' }}</span>
                                @else
                                    <span>{{ $item->category->name ?? 'Tanpa Kategori' }}</span>
                                    <span>•</span>
                                    <span>{{ $item->account->name ?? 'Dompet' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Toggle Status -->
                    <form action="{{ route('admin.recurring_transactions.toggle_status', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[9px] font-bold px-2 py-0.5 rounded-full transition-colors cursor-pointer {{ $item->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700' }}" title="Klik untuk mengubah status">
                            {{ $item->is_active ? '● Aktif' : '○ Nonaktif' }}
                        </button>
                    </form>
                </div>

                <!-- Schedule & Amount Row -->
                <div class="p-2.5 rounded-lg bg-zinc-50/80 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-zinc-400 block">Jadwal Eksekusi</span>
                        <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">
                            @if($item->frequency === 'monthly')
                                Tiap Tgl {{ $item->day_of_month }}
                            @elseif($item->frequency === 'daily')
                                Setiap Hari
                            @elseif($item->frequency === 'weekly')
                                Mingguan
                            @else
                                Tahunan
                            @endif
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold text-zinc-400 block">Nominal</span>
                        <span class="text-sm font-extrabold {{ $item->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($item->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-indigo-600 dark:text-indigo-400') }}">
                            {{ $item->type === 'income' ? '+' : ($item->type === 'expense' ? '-' : '') }}Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-1 border-t border-zinc-100 dark:border-zinc-800 text-xs">
                    <!-- Execute Now button -->
                    <form action="{{ route('admin.recurring_transactions.execute_now', $item->id) }}" method="POST" onsubmit="return confirm('Catat transaksi ini sekarang ke mutasi kas?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 rounded-lg transition-colors cursor-pointer">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Catat Sekarang</span>
                        </button>
                    </form>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.recurring_transactions.edit', $item->id) }}" class="px-2.5 py-1 text-[11px] font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('admin.recurring_transactions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal transaksi rutin ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900/60 rounded-lg transition-colors cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-5 text-center bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200/80 dark:border-zinc-800 text-zinc-400 text-xs">
                Belum ada jadwal transaksi berulang.
            </div>
        @endforelse
    </div>
</div>
@endsection
