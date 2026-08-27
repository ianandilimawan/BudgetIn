<div class="mb-3" x-cloak x-show="window.pgBulkActions && window.pgBulkActions.count('{{ $tableName }}') > 0">
    <button type="button"
        x-on:click="$wire.triggerBulkDelete(window.pgBulkActions.get('{{ $tableName }}'))"
        class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium shadow-sm transition-all duration-200 focus:ring-2 focus:ring-red-500/20 flex items-center justify-center">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg> 
        Bulk Delete (<span x-text="window.pgBulkActions ? window.pgBulkActions.count('{{ $tableName }}') : 0"></span>)
    </button>
</div>
