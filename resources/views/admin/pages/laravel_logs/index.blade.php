@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Server Logs</h1>
                <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">View and manage Server application logs</p>
            </div>
        </div>

<!-- Layout Grid -->
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- Sidebar: Log Files -->
            <div class="w-full lg:w-1/3 xl:w-1/4 flex-shrink-0">
                <div x-data="{ open: window.innerWidth >= 1024 }" @resize.window="if (window.innerWidth >= 1024) open = true" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden sticky top-6">
                    <!-- Mobile Toggle Button -->
                    <button @click="open = !open" class="lg:hidden w-full px-5 py-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-semibold text-gray-900 dark:text-white">Log Files Directory</span>
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Desktop Header (Hidden on Mobile) -->
                    <div class="hidden lg:flex px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Log Files</h2>
                    </div>

                    <!-- Files List -->
                    <div x-show="open" x-collapse class="max-h-[calc(100vh-12rem)] overflow-y-auto custom-scrollbar p-3 space-y-4">
                        @if (count($logFiles) > 0)
                            @foreach ($groupedLogFiles as $group)
                                <div>
                                    <h3 class="px-2 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                                        @if ($group['date'])
                                            {{ $group['date_formatted'] }}
                                            @if ($group['date'] === date('Y-m-d'))
                                                <span class="ml-2 px-1.5 py-0.5 text-[10px] bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400 rounded-md">Today</span>
                                            @endif
                                        @else
                                            {{ $group['date_formatted'] }}
                                        @endif
                                    </h3>
                                    <ul class="space-y-1">
                                        @foreach ($group['files'] as $file)
                                            <li>
                                                <a href="{{ route('admin.laravel-logs.index', ['file' => $file['name']]) }}"
                                                    class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors border border-transparent {{ $selectedFile === $file['name'] ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-medium truncate {{ $selectedFile === $file['name'] ? 'text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white' }}">
                                                            {{ $file['name'] }}
                                                        </p>
                                                        <p class="text-xs mt-0.5 {{ $selectedFile === $file['name'] ? 'text-blue-500/70 dark:text-blue-400/70' : 'text-gray-500 dark:text-gray-500' }}">
                                                            {{ $file['size_human'] }} &bull; {{ date('H:i', strtotime($file['modified_human'])) }}
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <div class="px-2 py-4 text-center">
                                <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No log files found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content: Log Entries -->
            <div class="w-full lg:w-2/3 xl:w-3/4 flex-1">
                @if ($selectedFile && $logData)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700 flex flex-col h-full lg:max-h-[calc(100vh-8rem)]">

                        <!-- Sticky Header & Filters -->
                        <div class="sticky top-0 z-20 bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white truncate" title="{{ $selectedFile }}">
                                        {{ $selectedFile }}
                                    </h2>
                                    <!-- Clear File Button -->
                                    <form x-data="ajaxForm" @submit.prevent="submit" method="POST" action="{{ route('admin.laravel-logs.clear', $selectedFile) }}" class="inline m-0" onsubmit="return confirm('Are you sure you want to clear this log file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors tooltip" title="Clear this log file" x-bind:disabled="loading">
<span x-show="!loading"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></span>
                        <span x-show="loading" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
</button>
                                    </form>
                                </div>

                                <!-- Filters -->
                                <form x-data="ajaxForm" @submit.prevent="submit" method="GET" action="{{ route('admin.laravel-logs.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                                    <input type="hidden" name="file" value="{{ $selectedFile }}">

                                    <div class="relative flex-1 md:w-40">
                                        <select name="level" class="w-full pl-3 pr-8 py-2 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                                            <option value="">All Levels</option>
                                            @foreach ($levels as $logLevel)
                                                <option value="{{ $logLevel }}" {{ request('level') === $logLevel ? 'selected' : '' }}>
                                                    {{ $logLevel }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>

                                    <div class="relative flex-1 md:w-56">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search messages..." class="w-full pl-9 pr-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-sm font-medium shadow-sm" x-bind:disabled="loading">
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
                                        <a href="{{ route('admin.laravel-logs.index', ['file' => $selectedFile]) }}" class="px-3 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                                            Clear
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>

                        <!-- Log Entries List (Scrollable Area) -->
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-0 bg-gray-50/50 dark:bg-gray-900/20">
                            @if (count($logData['entries']) > 0)
                                <div class="divide-y divide-gray-200 dark:divide-gray-750">
                                    @foreach ($logData['entries'] as $index => $entry)
                                        @php
                                            $isError = in_array(strtoupper($entry['level']), ['ERROR', 'EMERGENCY', 'CRITICAL', 'ALERT']);
                                            $isWarning = strtoupper($entry['level']) === 'WARNING';
                                            $isInfo = in_array(strtoupper($entry['level']), ['INFO', 'NOTICE']);
                                        @endphp
                                        <div x-data="{ expanded: false }" class="p-4 hover:bg-white dark:hover:bg-gray-800 transition-colors group">
                                            <div class="flex items-start gap-4">

                                                <!-- Icon -->
                                                <div class="flex-shrink-0 mt-0.5">
                                                    @if ($isError)
                                                        <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center border border-red-200 dark:border-red-800/50">
                                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @elseif ($isWarning)
                                                        <div class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center border border-yellow-200 dark:border-yellow-800/50">
                                                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        </div>
                                                    @elseif ($isInfo)
                                                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center border border-blue-200 dark:border-blue-800/50">
                                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @else
                                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 mb-1">
                                                        <span class="text-xs font-bold tracking-wider {{ $isError ? 'text-red-600 dark:text-red-400' : ($isWarning ? 'text-yellow-600 dark:text-yellow-400' : ($isInfo ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400')) }}">
                                                            {{ $entry['level'] }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                                            {{ $entry['timestamp'] }}
                                                        </span>
                                                        @if ($entry['environment'])
                                                            <span class="hidden sm:inline-block px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 uppercase">
                                                                {{ $entry['environment'] }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="text-sm text-gray-800 dark:text-gray-200 font-mono break-all sm:break-words whitespace-pre-wrap" :class="{'line-clamp-2': !expanded && {{ !empty($entry['stack']) ? 'true' : 'false' }} }">{{ $entry['message'] }}</div>

                                                    @if (!empty($entry['stack']))
                                                        <div x-show="expanded" x-collapse class="mt-3">
                                                            <div class="relative bg-gray-900 rounded-lg p-4 font-mono text-[11px] leading-relaxed text-gray-300 overflow-x-auto custom-scrollbar border border-gray-700/50 shadow-inner group/stack">
                                                                <button @click="navigator.clipboard.writeText($refs.stack_{{ $index }}.innerText); $el.innerText = 'Copied!'; setTimeout(() => $el.innerText = 'Copy', 2000)" class="absolute top-2 right-2 px-2 py-1 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded text-xs opacity-0 group-hover/stack:opacity-100 transition-opacity">
                                                                    Copy
                                                                </button>
                                                                <pre x-ref="stack_{{ $index }}">{{ $entry['stack'] }}</pre>
                                                            </div>
                                                        </div>

                                                        <button @click="expanded = !expanded" class="mt-2 text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1 focus:outline-none">
                                                            <span x-text="expanded ? 'Hide Stack Trace' : 'Show Stack Trace'"></span>
                                                            <svg :class="{'rotate-180': expanded}" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-16 px-4 text-center h-full">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No log entries found</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                        @if (request()->anyFilled(['level', 'search']))
                                            Try adjusting your filters or search terms to find what you're looking for.
                                        @else
                                            This log file is empty.
                                        @endif
                                    </p>
                                    @if (request()->anyFilled(['level', 'search']))
                                        <a href="{{ route('admin.laravel-logs.index', ['file' => $selectedFile]) }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900/50 dark:hover:bg-blue-900">
                                            Clear Filters
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Pagination Footer -->
                        @if ($logData && $logData['last_page'] > 1)
                            <div class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-5 py-3 flex items-center justify-between">
                                <div class="hidden sm:block text-sm text-gray-600 dark:text-gray-400">
                                    Showing <span class="font-medium text-gray-900 dark:text-white">{{ ($logData['current_page'] - 1) * $logData['per_page'] + 1 }}</span> to
                                    <span class="font-medium text-gray-900 dark:text-white">{{ min($logData['current_page'] * $logData['per_page'], $logData['total']) }}</span> of
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $logData['total'] }}</span> entries
                                </div>
                                <div class="flex gap-2 w-full sm:w-auto justify-between sm:justify-end">
                                    @if ($logData['current_page'] > 1)
                                        <a href="{{ route('admin.laravel-logs.index', array_merge(request()->only(['file', 'level', 'search']), ['page' => $logData['current_page'] - 1])) }}" class="px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-sm font-medium shadow-sm">
                                            &larr; Previous
                                        </a>
                                    @else
                                        <button disabled class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-600 rounded-md text-sm font-medium cursor-not-allowed">
                                            &larr; Previous
                                        </button>
                                    @endif

                                    @if ($logData['current_page'] < $logData['last_page'])
                                        <a href="{{ route('admin.laravel-logs.index', array_merge(request()->only(['file', 'level', 'search']), ['page' => $logData['current_page'] + 1])) }}" class="px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-sm font-medium shadow-sm">
                                            Next &rarr;
                                        </a>
                                    @else
                                        <button disabled class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-600 rounded-md text-sm font-medium cursor-not-allowed">
                                            Next &rarr;
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif ($selectedFile)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center border border-gray-200 dark:border-gray-700">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No log entries found</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">This log file is completely empty.</p>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center border border-gray-200 dark:border-gray-700">
                        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Select a Log File</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Please select a log file from the menu to view its contents.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #475569;
        }
    </style>
@endsection
