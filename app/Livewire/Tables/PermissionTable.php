<?php

namespace App\Livewire\Tables;

use App\Models\Permission;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

class PermissionTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'permission-table';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        $canDelete = auth()->user() && auth()->user()->hasPermission('delete-permissions');

        if ($canDelete) {
            $this->showCheckBox();
        }

        $header = PowerGrid::header()
            ->showSearchInput();

        if ($canDelete) {
            $header->includeViewOnTop('components.admin.bulk-action-button');
        }

        return [
            PowerGrid::exportable('export_permissions_' . now()->format('Ymd_His'))
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            $header,
            PowerGrid::footer()
                ->showPerPage(10, [10, 25, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Permission::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('module_display', function (Permission $row) {
                if ($row->module) {
                    return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">'
                        . e($row->module) . '</span>';
                }

                return '<span class="text-gray-400 dark:text-gray-500 text-xs">-</span>';
            })
            ->add('is_active_display', function (Permission $row) {
                if ($row->is_active) {
                    return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>';
                }

                return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Inactive</span>';
            })
            ->add('action', function (Permission $row) {
                $html = '<div class="flex items-center justify-center gap-1">';

                if (auth()->user() && auth()->user()->hasPermission('edit-permissions')) {
                    $editUrl = route('admin.permissions.edit', $row->id);
                    $html .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">';
                    $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
                    $html .= '</a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('delete-permissions')) {
                    $deleteUrl = route('admin.permissions.destroy', $row->id);
                    $html .= '<button onclick="window.dispatchEvent(new CustomEvent(\'open-delete-modal\', { detail: { action: \'' . $deleteUrl . '\' } }))" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">';
                    $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                    $html .= '</button>';
                }

                $html .= '</div>';
                return $html;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('No')->index()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Module', 'module_display')
                ->visibleInExport(false),

            Column::make('Status', 'is_active_display')
                ->visibleInExport(false),

            Column::make('Actions', 'action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center')
                ->visibleInExport(false),
        ];
    }



    #[\Livewire\Attributes\On('triggerBulkDelete')]
    public function triggerBulkDelete(?array $ids = null): void
    {
        if (!$ids) {
            $ids = $this->checkboxValues;
        }
        
        if (empty($ids)) return;
        
        if (!auth()->user()->hasPermission('delete-permissions')) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to delete permissions.');
            return;
        }

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $ids,
            'model' => 'App\\\\Models\\\\Permission',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }
    
    #[\Livewire\Attributes\On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        if (!auth()->user()->hasPermission('delete-permissions')) return;
        
        try {
            Permission::whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(Permission::class, count($ids), $ids);
            
            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: count($ids) . ' permissions have been deleted.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to delete permissions.');
        }
    }
}
