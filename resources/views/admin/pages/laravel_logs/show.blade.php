@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Log Viewer</h1>
                <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">View detailed log entries</p>
            </div>
            <a href="{{ route('admin.laravel-logs.index', ['file' => $fileName]) }}"
                class="lg:px-4 px-3 lg:py-2 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center lg:text-base text-sm">
                <svg class="lg:w-5 w-4 lg:h-5 h-4 inline lg:mr-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Logs
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form x-data="ajaxForm" @submit.prevent="submit" method="GET" action="{{ route('admin.laravel-logs.show', $fileName) }}" class="flex flex-wrap gap-2">
                <select name="level"
                    class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Levels</option>
                    @foreach ($levels as $logLevel)
                        <option value="{{ $logLevel }}" {{ request('level') === $logLevel ? 'selected' : '' }}>
                            {{ $logLevel }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..."
                    class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[200px]">

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-sm font-medium" x-bind:disabled="loading">
<span x-show="!loading">Filter</span>
                        <span x-show="loading" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
</button>

                @if (request()->anyFilled(['level', 'search']))
                    <a href="{{ route('admin.laravel-logs.show', $fileName) }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-sm font-medium">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Log Entries -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Log Entries: {{ $fileName }}
                </h2>
            </div>

            <div class="p-6">
                @if (count($logData['entries']) > 0)
                    <div class="space-y-4">
                        @foreach ($logData['entries'] as $entry)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full {{ \App\Services\LaravelLogService::getLevelColor($entry['level']) }}">
                                            {{ $entry['level'] }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $entry['timestamp'] }}
                                        </span>
                                        @if ($entry['environment'])
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $entry['environment'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <p class="text-sm text-gray-900 dark:text-white font-medium mb-2">
                                        {{ $entry['message'] }}
                                    </p>

                                    @if (!empty($entry['stack']))
                                        <details class="mt-2" open>
                                            <summary
                                                class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-300 mb-2">
                                                Stack Trace
                                            </summary>
                                            <pre
                                                class="mt-2 p-3 bg-gray-100 dark:bg-gray-900 rounded-lg overflow-x-auto text-xs text-gray-800 dark:text-gray-200 font-mono whitespace-pre-wrap">{{ $entry['stack'] }}</pre>
                                        </details>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($logData['last_page'] > 1)
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Showing {{ ($logData['current_page'] - 1) * $logData['per_page'] + 1 }} to
                                {{ min($logData['current_page'] * $logData['per_page'], $logData['total']) }} of
                                {{ $logData['total'] }} entries
                            </div>

                            <div class="flex gap-2">
                                @if ($logData['current_page'] > 1)
                                    <a href="{{ route('admin.laravel_logs.show', array_merge(['fileName' => $fileName], request()->only(['level', 'search']), ['page' => $logData['current_page'] - 1])) }}"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                        Previous
                                    </a>
                                @endif

                                @if ($logData['current_page'] < $logData['last_page'])
                                    <a href="{{ route('admin.laravel_logs.show', array_merge(['fileName' => $fileName], request()->only(['level', 'search']), ['page' => $logData['current_page'] + 1])) }}"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                        Next
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">
                        No log entries found
                        @if (request()->anyFilled(['level', 'search']))
                            with the current filters
                        @endif
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
