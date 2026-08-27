@extends('admin.layouts.app')

@section('title', 'Edit Transaksi Berulang')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Edit Jadwal Transaksi Berulang</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Perbarui nominal, frekuensi, atau kategori jadwal transaksi rutin.</p>
        </div>
        <a href="{{ route('admin.recurring_transactions.index') }}"
            class="px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-xl hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700 dark:hover:bg-zinc-700">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
        <form action="{{ route('admin.recurring_transactions.update', $recurring->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.recurring_transactions.fields')

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800">
                <a href="{{ route('admin.recurring_transactions.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-xl hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700 dark:hover:bg-zinc-700">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl font-semibold text-sm shadow-md shadow-emerald-500/20 hover:scale-[1.02] transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
