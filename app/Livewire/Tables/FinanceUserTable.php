<?php

namespace App\Livewire\Tables;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Livewire\Attributes\On;

class FinanceUserTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'finance-user-table';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $canDelete = auth()->user() && auth()->user()->hasPermission('delete-users');

        if ($canDelete) {
            $this->showCheckBox();
        }

        $header = PowerGrid::header()
            ->showSearchInput();

        if ($canDelete) {
            $header->includeViewOnTop('components.admin.bulk-action-button');
        }

        return [
            PowerGrid::exportable('export_finance_users_' . now()->format('Ymd_His'))
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            $header,
            PowerGrid::footer()
                ->showPerPage(10, [10, 25, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::finance()->withCount(['cashAccounts', 'cashTransactions']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name_display', function (User $row) {
                $initial = strtoupper(substr($row->name, 0, 1));
                $avatar = '<div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold flex items-center justify-center text-sm flex-shrink-0">' . e($initial) . '</div>';
                
                return '<div class="flex items-center gap-3">' . $avatar . '<div><div class="font-semibold text-zinc-900 dark:text-white">' . e($row->name) . '</div><div class="text-xs text-zinc-500 dark:text-zinc-400">' . e($row->email) . '</div></div></div>';
            })
            ->add('cash_accounts_count', function (User $row) {
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">' . $row->cash_accounts_count . ' Akun</span>';
            })
            ->add('cash_transactions_count', function (User $row) {
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300">' . $row->cash_transactions_count . ' Transaksi</span>';
            })
            ->add('status_display', function (User $row) {
                if ($row->is_active) {
                    return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>';
                }
                return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-300 border border-red-200/60 dark:border-red-800/40"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Nonaktif</span>';
            })
            ->add('created_at_formatted', function (User $row) {
                return $row->created_at ? $row->created_at->translatedFormat('d M Y H:i') : '-';
            })
            ->add('action', function (User $row) {
                $html = '<div class="flex items-center justify-center gap-1.5">';

                // Toggle Status Button (Super Admin control)
                if (auth()->user() && auth()->user()->hasPermission('edit-users')) {
                    $toggleUrl = route('admin.finance_users.toggle_status', $row->id);
                    $toggleTitle = $row->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun';
                    $toggleColor = $row->is_active ? 'text-amber-600 hover:text-amber-800 hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-950/40' : 'text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-950/40';
                    $icon = $row->is_active
                        ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>'
                        : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';

                    $html .= '<form action="' . $toggleUrl . '" method="POST" class="inline" onsubmit="return confirm(\'Apakah Anda yakin ingin ' . ($row->is_active ? 'menonaktifkan' : 'mengaktifkan') . ' akun ini?\')">';
                    $html .= csrf_field();
                    $html .= '<button type="submit" class="p-1.5 ' . $toggleColor . ' rounded-lg transition-colors" title="' . $toggleTitle . '">' . $icon . '</button>';
                    $html .= '</form>';
                }

                if (auth()->user() && auth()->user()->hasPermission('edit-users')) {
                    $editUrl = route('admin.finance_users.edit', $row->id);
                    $html .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">';
                    $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
                    $html .= '</a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('delete-users')) {
                    $deleteUrl = route('admin.finance_users.destroy', $row->id);
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

            Column::make('Pengguna Finance', 'name_display', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Akun Kas', 'cash_accounts_count')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make('Transaksi', 'cash_transactions_count')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make('Status', 'status_display')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make('Terdaftar', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Aksi', 'action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center')
                ->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('status_display', 'is_active'),
        ];
    }

    #[On('triggerBulkDelete')]
    public function triggerBulkDelete(?array $ids = null): void
    {
        if (!$ids) {
            $ids = $this->checkboxValues;
        }

        if (empty($ids)) return;

        if (!auth()->user()->hasPermission('delete-users')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin menghapus pengguna.');
            return;
        }

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $ids,
            'model' => 'App\\\\Models\\\\User',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }

    #[On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        if (!auth()->user()->hasPermission('delete-users')) return;

        try {
            $deletedCount = User::finance()->whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(User::class, $deletedCount, $ids);

            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: $deletedCount . ' akun finance berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal menghapus akun finance.');
        }
    }
}
