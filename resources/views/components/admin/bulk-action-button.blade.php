<div class="mb-3" x-cloak x-show="window.pgBulkActions && window.pgBulkActions.count('{{ $tableName }}') > 0">
    <button type="button"
        x-on:click="$wire.triggerBulkDelete(window.pgBulkActions.get('{{ $tableName }}'))"
        class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-500/20 transition-all duration-200 focus:ring-2 focus:ring-rose-500/30 flex items-center justify-center cursor-pointer">
        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg> 
        <span>Hapus Terpilih (<span x-text="window.pgBulkActions ? window.pgBulkActions.count('{{ $tableName }}') : 0"></span>)</span>
    </button>
</div>
