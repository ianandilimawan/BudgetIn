@extends('admin.layouts.app')

@section('title', 'Transaction Categories')

@section('content')
    <div class="space-y-6">

<!-- Page Header -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h2 class="lg:text-2xl text-xl font-bold text-gray-900 dark:text-white">Transaction Categories</h2>
                <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">Manage your transactioncategories here</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(auth()->user() && auth()->user()->hasPermission('create-transaction_categories'))
                
                <a href="{{ route('admin.transaction_categories.create') }}"
                    class="lg:px-4 px-3 lg:py-2 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors lg:text-base text-sm">
                    <svg class="lg:w-5 w-4 lg:h-5 h-4 inline lg:mr-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Transaction Category
                </a>
                @endif
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden p-2">
            <livewire:tables.transaction-category-table />
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-confirm-delete-modal title="Delete Transaction Category"
        message="Are you sure you want to delete this transactioncategory? This action cannot be undone." />
@endsection
