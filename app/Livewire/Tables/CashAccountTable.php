<?php

namespace App\Livewire\Tables;

use App\Models\CashAccount;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

class CashAccountTable extends PowerGridComponent
{
    public string $tableName = 'cashAccount-table';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        $canDelete = auth()->user() && auth()->user()->hasPermission('delete-cash_accounts');

        if ($canDelete) {
            $this->showCheckBox();
        }

        $header = PowerGrid::header()
            ->showSearchInput()
            ->showToggleColumns();

        if ($canDelete) {
            $header->includeViewOnTop('components.admin.bulk-action-button');
        }

        return [
            $header,
            PowerGrid::footer()
                ->showPerPage(10, [10, 25, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return CashAccount::query()
            ->where('user_id', auth()->id())
            ->with('accountType');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name_display', function (CashAccount $row) {
                if ($row->type === 'bank') {
                    $svg = '<div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>';
                } elseif ($row->type === 'cash') {
                    $svg = '<div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>';
                } elseif ($row->type === 'ewallet') {
                    $svg = '<div class="w-6 h-6 rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>';
                } elseif ($row->type === 'investment') {
                    $svg = '<div class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg></div>';
                } else {
                    $svg = '<div class="w-6 h-6 rounded-lg bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg></div>';
                }
                return '<div class="flex items-center gap-2">' . $svg . '<span class="font-semibold text-zinc-900 dark:text-white">' . e($row->name) . '</span></div>';
            })
            ->add('type_display', function (CashAccount $row) {
                $typeName = $row->type_name;
                if ($row->type === 'bank') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">' . e($typeName) . '</span>';
                } elseif ($row->type === 'cash') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">' . e($typeName) . '</span>';
                } elseif ($row->type === 'ewallet') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300">' . e($typeName) . '</span>';
                } elseif ($row->type === 'investment') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">' . e($typeName) . '</span>';
                }
                return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300">' . e($typeName) . '</span>';
            })
            ->add('account_number_display', fn (CashAccount $row) => e($row->account_number ?: '-'))
            ->add('initial_balance_formatted', fn (CashAccount $row) => 'Rp ' . number_format($row->initial_balance, 0, ',', '.'))
            ->add('is_active_display', function (CashAccount $row) {
                if ($row->is_active) {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Aktif</span>';
                }
                return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">Nonaktif</span>';
            })
            ->add('action', function (CashAccount $row) {
                $actions = '<div class="flex items-center justify-center gap-1">';

                if (auth()->user() && auth()->user()->hasPermission('view-cash_accounts')) {
                    $viewUrl = route('admin.cash_accounts.show', $row->id);
                    $actions .= '<a href="' . $viewUrl . '" class="p-1.5 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('edit-cash_accounts')) {
                    $editUrl = route('admin.cash_accounts.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('delete-cash_accounts')) {
                    $deleteUrl = route('admin.cash_accounts.destroy', $row->id);
                    $actions .= '<button onclick="window.dispatchEvent(new CustomEvent(\'open-delete-modal\', { detail: { action: \'' . $deleteUrl . '\' } }))" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>';
                }

                $actions .= '</div>';
                return $actions;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('No')->index()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make('Nama Dompet / Akun', 'name_display', 'name')->sortable()->searchable(),
            Column::make('Tipe', 'type_display', 'type')->sortable(),
            Column::make('Nomor Rekening / Info', 'account_number_display', 'account_number')->sortable()->searchable(),
            Column::make('Saldo Awal', 'initial_balance_formatted', 'initial_balance')->sortable(),
            Column::make('Status', 'is_active_display', 'is_active')->sortable(),
            Column::make('Aksi', 'action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center')
                ->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('is_active_display', 'is_active'),
        ];
    }

    #[\Livewire\Attributes\On('triggerBulkDelete')]
    public function triggerBulkDelete(?array $ids = null): void
    {
        if (!auth()->user() || !auth()->user()->hasPermission('delete-cash_accounts')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus akun kas.');
            return;
        }

        if (!$ids) {
            $ids = $this->checkboxValues;
        }

        if (empty($ids)) return;

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $ids,
            'model' => 'App\\\\Models\\\\CashAccount',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }

    #[\Livewire\Attributes\On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        if (!auth()->user() || !auth()->user()->hasPermission('delete-cash_accounts')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus akun kas.');
            return;
        }

        try {
            $deletedCount = CashAccount::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(CashAccount::class, $deletedCount, $ids);

            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: $deletedCount . ' akun kas berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal menghapus akun kas.');
        }
    }
}
