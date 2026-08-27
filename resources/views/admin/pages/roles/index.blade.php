@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Roles</h1>
                <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">Manage user roles and permissions</p>
            </div>
            @if (auth()->user() && auth()->user()->hasPermission('create-roles'))
                <a href="{{ route('admin.roles.create') }}"
                    class="lg:px-4 px-3 lg:py-2 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center lg:text-base text-sm">
                    <svg class="lg:w-5 w-4 lg:h-5 h-4 inline lg:mr-2 mr-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Role
                </a>
            @endif
        </div>

<!-- Roles Table -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden p-2">
            <livewire:tables.role-table />
        </div>
    </div>
@endsection
