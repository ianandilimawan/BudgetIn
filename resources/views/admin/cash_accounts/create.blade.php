@extends('admin.layouts.app')

@section('title', 'Tambah Dompet / Akun Kas')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center gap-3 sm:gap-4">
        <a href="{{ route('admin.cash_accounts.index') }}" class="p-1.5 sm:p-2 bg-white dark:bg-zinc-800 rounded-xl shadow-xs border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-indigo-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <nav class="flex text-[11px] sm:text-xs text-zinc-500 font-medium mb-0.5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.cash_accounts.index') }}" class="hover:text-indigo-600 transition-colors">Manajemen Dompet</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-zinc-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span class="text-zinc-400">Tambah Baru</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-base sm:text-lg lg:text-xl font-bold text-zinc-900 dark:text-white">Tambah Akun / Dompet Baru</h1>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-2xl shadow-xs border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
        <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.cash_accounts.store') }}" method="POST" class="p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
            @csrf

            @include('admin.cash_accounts.fields')

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-2.5 pt-4 sm:pt-6 border-t border-zinc-100 dark:border-zinc-800">
                <a href="{{ route('admin.cash_accounts.index') }}"
                   class="px-3.5 py-2 sm:px-4 sm:py-2.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition font-semibold text-xs sm:text-sm">
                    Batal
                </a>
                <button type="submit"
                        :disabled="loading"
                        class="px-4 py-2 sm:px-5 sm:py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-semibold shadow-xs hover:shadow-md text-xs sm:text-sm disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer">
                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg x-show="loading" class="animate-spin h-4 w-4 text-white" style="display: none;" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Akun'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
