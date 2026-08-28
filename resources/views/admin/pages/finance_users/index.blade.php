@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna Finance')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Pengguna Finance</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Kelola dan kontrol status keaktifan akun pengguna finance yang terdaftar.</p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-3.5">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Akun Finance</p>
                    <h3 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white mt-1">{{ $totalFinanceUsers }} User</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Akun Aktif</p>
                    <h3 class="text-base sm:text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $activeFinanceUsers }} User</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Akun Nonaktif</p>
                    <h3 class="text-base sm:text-lg font-bold text-rose-600 dark:text-rose-400 mt-1">{{ $inactiveFinanceUsers }} User</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop View (PowerGrid Table Card) -->
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 shadow-xs overflow-hidden">
        <livewire:tables.finance-user-table />
    </div>

    <!-- Mobile View (Finance User Cards) -->
    <div class="block md:hidden space-y-2.5">
        @forelse($mobileUsers as $user)
            <div class="p-3.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-2xs space-y-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $user->name }}</h3>
                            <p class="text-[10px] text-zinc-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- Toggle Status Button -->
                    <form action="{{ route('admin.finance_users.toggle_status', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[9px] font-bold px-2 py-0.5 rounded-full transition-colors cursor-pointer {{ $user->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200 dark:border-rose-800' }}" title="Klik untuk mengubah status aktif/nonaktif">
                            {{ $user->is_active ? '● Aktif' : '○ Nonaktif' }}
                        </button>
                    </form>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800 text-[10px] text-zinc-400">
                    <span>Terdaftar: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.finance_users.edit', $user->id) }}" class="px-2.5 py-1 text-[11px] font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('admin.finance_users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna finance {{ $user->name }}?')">
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
                Belum ada akun finance terdaftar.
            </div>
        @endforelse
    </div>
</div>
@endsection
