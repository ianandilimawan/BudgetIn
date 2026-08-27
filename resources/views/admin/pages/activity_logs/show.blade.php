@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Activity Log Details</h1>
                <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">View detailed information about this
                    activity</p>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}"
                class="lg:px-4 px-3 lg:py-2 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center lg:text-base text-sm">
                <svg class="lg:w-5 w-4 lg:h-5 h-4 inline lg:mr-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Logs
            </a>
        </div>

        <!-- Activity Log Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="lg:px-6 px-4 lg:py-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h2 class="lg:text-lg text-base font-semibold text-gray-900 dark:text-white">Activity Information</h2>
            </div>
            <div class="lg:px-6 px-4 lg:py-4 py-3 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action</label>
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ strtolower($activityLog->action) == 'create' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                            {{ strtolower($activityLog->action) == 'update' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                            {{ strtolower($activityLog->action) == 'delete' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                            {{ ucfirst($activityLog->action) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model Type</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $activityLog->model_type }}</p>
                        @if ($activityLog->model_id)
                            <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $activityLog->model_id }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                        @if ($activityLog->user)
                            <p class="text-sm text-gray-900 dark:text-white">{{ $activityLog->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activityLog->user->email }}</p>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">System</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ $activityLog->created_at->format('Y-m-d H:i:s') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activityLog->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $activityLog->description }}</p>
                </div>
            </div>
        </div>

        <!-- Data Changes -->
        @if (($activityLog->old_values && !empty($activityLog->old_values)) || ($activityLog->new_values && !empty($activityLog->new_values)))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="lg:px-6 px-4 lg:py-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h2 class="lg:text-lg text-base font-semibold text-gray-900 dark:text-white">
                    {{ strtolower($activityLog->action) === 'update' ? 'Data Changes' : 'Data Details' }}
                </h2>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-750">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Field</th>
                            @if(strtolower($activityLog->action) === 'update')
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Old Value</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">New Value</th>
                            @else
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
        @php
            $old = is_array($activityLog->old_values) ? $activityLog->old_values : [];
            $new = is_array($activityLog->new_values) ? $activityLog->new_values : [];
            $allKeys = array_unique(array_merge(array_keys($old), array_keys($new)));
            
            $changedKeys = [];
            $unchangedKeys = [];
            
            foreach ($allKeys as $key) {
                if (strtolower($activityLog->action) === 'update') {
                    $existsInOld = array_key_exists($key, $old);
                    $existsInNew = array_key_exists($key, $new);
                    
                    if (!$existsInNew) {
                        // If it's not in new_values, it wasn't changed (due to Laravel getChanges())
                        $unchangedKeys[] = $key;
                        continue;
                    }
                    
                    $oldVal = $existsInOld ? $old[$key] : null;
                    $newVal = $new[$key];
                    
                    if (is_numeric($oldVal) && is_numeric($newVal)) {
                        if ((float)$oldVal != (float)$newVal) {
                            $changedKeys[] = $key;
                        } else {
                            $unchangedKeys[] = $key;
                        }
                    } else if ($oldVal !== $newVal) {
                        $changedKeys[] = $key;
                    } else {
                        $unchangedKeys[] = $key;
                    }
                } else {
                    $changedKeys[] = $key;
                }
            }
            
            // If action is delete and deleted_at is null, we can fill it with the activity timestamp
            if (strtolower($activityLog->action) === 'delete' && in_array('deleted_at', $allKeys)) {
                if (array_key_exists('deleted_at', $old) && empty($old['deleted_at'])) {
                    $old['deleted_at'] = $activityLog->created_at->format('Y-m-d H:i:s');
                }
            }
            
            $sortFunc = function($a, $b) {
                $bottomKeysOrder = ['created_at' => 1, 'updated_at' => 2, 'deleted_at' => 3];
                
                $aIsBottom = isset($bottomKeysOrder[$a]);
                $bIsBottom = isset($bottomKeysOrder[$b]);
                
                if ($aIsBottom && !$bIsBottom) return 1;
                if (!$aIsBottom && $bIsBottom) return -1;
                if ($aIsBottom && $bIsBottom) {
                    return $bottomKeysOrder[$a] <=> $bottomKeysOrder[$b];
                }
                
                $aIsId = ($a === 'id');
                $bIsId = ($b === 'id');
                
                if ($aIsId && !$bIsId) return -1;
                if (!$aIsId && $bIsId) return 1;
                
                return strcmp($a, $b);
            };
            
            usort($changedKeys, $sortFunc);
            usort($unchangedKeys, $sortFunc);
            
            $displayKeys = strtolower($activityLog->action) === 'update' ? $changedKeys : $allKeys;
            
            if (strtolower($activityLog->action) !== 'update') {
                usort($displayKeys, $sortFunc);
            }
        @endphp

        @foreach($displayKeys as $key)
            @php
                $oldVal = array_key_exists($key, $old) ? $old[$key] : null;
                $newVal = array_key_exists($key, $new) ? $new[$key] : null;
            @endphp
            <tr class="{{ strtolower($activityLog->action) === 'update' ? 'bg-yellow-50/30 dark:bg-yellow-900/10' : '' }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ ucwords(str_replace('_', ' ', $key)) }}
                </td>
                @if(strtolower($activityLog->action) === 'update')
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                        @if(array_key_exists($key, $old))
                            <span class="text-red-600 dark:text-red-400 line-through mr-2">{{ is_array($oldVal) ? json_encode($oldVal) : ($oldVal ?? '-') }}</span>
                        @else
                            <span class="text-gray-400 dark:text-gray-600 italic">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                        @if(array_key_exists($key, $new))
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ is_array($newVal) ? json_encode($newVal) : ($newVal ?? '-') }}</span>
                        @else
                            <span class="text-gray-400 dark:text-gray-600 italic">-</span>
                        @endif
                    </td>
                @else
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                        @php
                            $val = strtolower($activityLog->action) === 'delete' ? $oldVal : $newVal;
                        @endphp
                        {{ is_array($val) ? json_encode($val) : ($val ?? '-') }}
                    </td>
                @endif
            </tr>
        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
@endsection
