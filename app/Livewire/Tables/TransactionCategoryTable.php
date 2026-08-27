<?php

namespace App\Livewire\Tables;

use App\Models\TransactionCategory;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use Illuminate\Support\Facades\Schema;

class TransactionCategoryTable extends PowerGridComponent
{
    public string $tableName = 'transactionCategory-table';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        $canDelete = auth()->user() && auth()->user()->hasPermission('delete-transaction_categories');

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

    public function actionRules($row): array
    {
        return [
            Rule::checkbox()
                ->when(fn ($category) => is_null($category->user_id) || $category->user_id !== auth()->id())
                ->hide(),
        ];
    }

    public function datasource(): Builder
    {
        $query = TransactionCategory::forUser(auth()->id());

        if (Schema::hasColumn('transaction_categories', 'sort')) {
            $query->orderBy('sort', 'asc');
        }

        return $query;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name_display', function (TransactionCategory $row) {
                $badge = \App\Helpers\CategoryIconHelper::renderBadge($row->icon, $row->type, 'w-8 h-8');
                $isSystem = is_null($row->user_id);
                $systemBadge = $isSystem ? ' <span class="px-1.5 py-0.5 text-[10px] font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-500 rounded border border-zinc-200 dark:border-zinc-700">Sistem</span>' : '';

                return '<div class="flex items-center gap-3">' . $badge . '<div><div class="font-semibold text-zinc-900 dark:text-white flex items-center gap-1.5">' . e($row->name) . $systemBadge . '</div></div></div>';
            })
            ->add('type_display', function (TransactionCategory $row) {
                if ($row->type === 'income') {
                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Pemasukan</span>';
                }
                return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/50 dark:border-rose-800/40"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Pengeluaran</span>';
            })
            ->add('is_active_display', function (TransactionCategory $row) {
                if ($row->is_active) {
                    return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>';
                }
                return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700"><span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>Nonaktif</span>';
            })
            ->add('action', function (TransactionCategory $row) {
                $actions = '<div class="flex items-center justify-center gap-1">';

                if (auth()->user() && auth()->user()->hasPermission('view-transaction_categories')) {
                    $viewUrl = route('admin.transaction_categories.show', $row->id);
                    $actions .= '<a href="' . $viewUrl . '" class="p-1.5 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" title="Lihat Detail"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                }

                // Edit & Delete only allowed for user's own custom categories
                if ($row->user_id === auth()->id()) {
                    if (auth()->user() && auth()->user()->hasPermission('edit-transaction_categories')) {
                        $editUrl = route('admin.transaction_categories.edit', $row->id);
                        $actions .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>';
                    }

                    if (auth()->user() && auth()->user()->hasPermission('delete-transaction_categories')) {
                        $deleteUrl = route('admin.transaction_categories.destroy', $row->id);
                        $actions .= '<button onclick="window.dispatchEvent(new CustomEvent(\'open-delete-modal\', { detail: { action: \'' . $deleteUrl . '\' } }))" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>';
                    }
                } else {
                    $actions .= '<span class="p-1.5 text-zinc-400 dark:text-zinc-600" title="Kategori Default Sistem"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></span>';
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

            Column::make('Kategori', 'name_display', 'name')->sortable()->searchable(),
            Column::make('Tipe', 'type_display', 'type')->sortable()->searchable(),
            Column::make('Status', 'is_active_display', 'is_active')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),
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
        if (!auth()->user() || !auth()->user()->hasPermission('delete-transaction_categories')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus kategori transaksi.');
            return;
        }

        if (!$ids) {
            $ids = $this->checkboxValues;
        }

        if (empty($ids)) return;

        $validIds = TransactionCategory::where('user_id', auth()->id())
            ->whereIn('id', $ids)
            ->pluck('id')
            ->toArray();

        if (empty($validIds)) {
            $this->dispatch('notify', type: 'error', message: 'Kategori sistem bawaan tidak dapat dihapus.');
            return;
        }

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $validIds,
            'model' => 'App\\\\Models\\\\TransactionCategory',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }

    #[\Livewire\Attributes\On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        if (!auth()->user() || !auth()->user()->hasPermission('delete-transaction_categories')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus kategori transaksi.');
            return;
        }

        try {
            $deletedCount = TransactionCategory::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(TransactionCategory::class, $deletedCount, $ids);

            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: $deletedCount . ' kategori buatan Anda berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal menghapus kategori.');
        }
    }
}
