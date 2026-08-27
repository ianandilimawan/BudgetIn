@extends('admin.layouts.app')

@section('title', 'Edit Transaksi Berulang')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-6">
    <!-- Header -->
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Edit Jadwal Transaksi Rutin</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Perbarui nominal, frekuensi, atau kategori jadwal transaksi rutin.</p>
        </div>
        <a href="{{ route('admin.recurring_transactions.index') }}"
            class="px-3 py-1.5 text-xs font-semibold text-zinc-700 bg-white border border-zinc-300 rounded-xl hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700 dark:hover:bg-zinc-700 transition">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-xs">
        <form action="{{ route('admin.recurring_transactions.update', $recurring->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.recurring_transactions.fields')

            <div class="flex items-center justify-end gap-2.5 mt-6 pt-5 border-t border-zinc-100 dark:border-zinc-800">
                <a href="{{ route('admin.recurring_transactions.index') }}"
                    class="px-3.5 py-2 text-xs font-semibold text-zinc-700 bg-white border border-zinc-300 rounded-xl hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700 dark:hover:bg-zinc-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl font-semibold text-xs shadow-sm shadow-emerald-500/20 hover:scale-[1.02] transition-all cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
