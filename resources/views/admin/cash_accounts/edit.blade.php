@extends('admin.layouts.app')

@section('title', 'Edit Dompet / Akun Kas')

@section('content')
    <div class="space-y-6">
        <!-- Page Header & Breadcrumbs -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cash_accounts.index') }}"
                class="p-2 bg-white dark:bg-zinc-800 rounded-xl shadow-xs border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-indigo-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <nav class="flex text-xs text-zinc-500 font-medium mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.cash_accounts.index') }}"
                                class="hover:text-indigo-600 transition-colors">Manajemen Dompet</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 text-zinc-400 mx-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <span class="text-zinc-400">Edit Akun</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl lg:text-2xl font-bold text-zinc-900 dark:text-white">Edit Akun / Dompet</h1>
            </div>
        </div>

        <!-- Form Container -->
        <div
            class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-2xl shadow-sm border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
            <form x-data="ajaxForm" @submit.prevent="submit"
                action="{{ route('admin.cash_accounts.update', $cashAccount) }}" method="POST"
                class="p-5 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                @include('admin.cash_accounts.fields')

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                    <a href="{{ route('admin.cash_accounts.index') }}"
                        class="px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition font-semibold text-xs sm:text-sm">
                        Batal
                    </a>
                    <button type="submit" :disabled="loading"
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-semibold shadow-xs hover:shadow-md text-xs sm:text-sm disabled:opacity-50 inline-flex items-center gap-2">
                        <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" style="display: none;" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="loading ? 'Memperbarui...' : 'Perbarui Akun'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
