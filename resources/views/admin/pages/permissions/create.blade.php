@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Create Permission</h1>
            <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">Add a new permission to the system</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900 border-2 border-red-600 dark:border-red-600 shadow-sm rounded-xl p-4">
                <ul class="text-sm text-red-900 dark:text-red-200 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.permissions.store') }}" method="POST" class="lg:p-8 px-4 py-4 space-y-6">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <x-input-floating type="text" name="name" label="Name" value="{{ old('name') }}" required="true" />
                </div>

                <!-- Slug -->
                <div class="mb-6">
                    <x-input-floating type="text" name="slug" label="Slug" value="{{ old('slug') }}" required="true" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lowercase with hyphens (e.g., view-users)</p>
                </div>

                <!-- Module -->
                <div class="mb-6">
                    <x-input-floating type="text" name="module" label="Module" value="{{ old('module') }}" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional module/feature name (e.g., users)</p>
                </div>

                <!-- Description -->
                <div>
                    <x-textarea-floating name="description" label="Description" value="{{ old('description') }}" />
                </div>

                <!-- Is Active -->
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-8 border-t-2 border-gray-100 dark:border-gray-700 mt-8">
                    <a href="{{ route('admin.permissions.index') }}"
                        class="lg:px-8 px-3 py-3 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors font-semibold shadow-md hover:shadow-lg border-2 border-gray-200 dark:border-gray-600 lg:text-base text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="lg:px-8 px-3 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold shadow-md hover:shadow-lg hover:scale-105 transform lg:text-base text-sm" x-bind:disabled="loading">
<span x-show="!loading">Create Permission</span>
                        <span x-show="loading" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
</button>
                </div>
            </form>
        </div>
    </div>
@endsection
