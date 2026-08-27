<?php

namespace App\Livewire\Tables;

use App\Models\RecurringTransaction;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;

class RecurringTransactionTable extends PowerGridComponent
{
    public string $tableName = 'recurringTransaction-table';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $canDelete = auth()->user() && auth()->user()->hasPermission('delete-cash_transactions');

        if ($canDelete) {
            $this->showCheckBox();
        }

        $header = PowerGrid::header()
            ->showSearchInput();

        if ($canDelete) {
            $header->includeViewOnTop('components.admin.bulk-action-button');
        }

        return [
            $header,
            PowerGrid::footer()
                ->showPerPage(10, [10, 25, 50])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return RecurringTransaction::query()
            ->where('user_id', auth()->id())
            ->with(['category', 'account', 'toAccount']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name_display', function (RecurringTransaction $row) {
                return '<div class="font-semibold text-zinc-900 dark:text-white">' . e($row->name) . '</div>' . ($row->note ? '<div class="text-xs text-zinc-400 truncate max-w-xs">' . e($row->note) . '</div>' : '');
            })
            ->add('category_display', function (RecurringTransaction $row) {
                if ($row->type === 'transfer') {
                    return '<span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Tarik / Transfer</span>';
                }
                if ($row->category) {
                    $badge = \App\Helpers\CategoryIconHelper::renderBadge($row->category->icon, $row->category->type ?? $row->type, 'w-6 h-6');
                    return '<div class="flex items-center gap-2">' . $badge . '<span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">' . e($row->category->name) . '</span></div>';
                }
                return '<span class="text-xs text-zinc-400">-</span>';
            })
            ->add('type_badge', function (RecurringTransaction $row) {
                if ($row->type === 'income') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">Pemasukan</span>';
                } elseif ($row->type === 'expense') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">Pengeluaran</span>';
                }
                return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300">Transfer</span>';
            })
            ->add('account_display', function (RecurringTransaction $row) {
                if ($row->type === 'transfer') {
                    $from = $row->account?->name ?? '-';
                    $to = $row->toAccount?->name ?? '-';
                    return '<div class="text-xs font-medium text-zinc-700 dark:text-zinc-300"><span class="text-rose-600">' . e($from) . '</span> ➔ <span class="text-emerald-600">' . e($to) . '</span></div>';
                }
                return '<span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">' . e($row->account?->name ?? '-') . '</span>';
            })
            ->add('amount_formatted', function (RecurringTransaction $row) {
                return '<span class="font-bold text-sm ' . ($row->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400') . '">Rp ' . number_format($row->amount, 0, ',', '.') . '</span>';
            })
            ->add('frequency_display', function (RecurringTransaction $row) {
                if ($row->frequency === 'monthly') {
                    return '<span class="inline-flex items-center gap-1 text-xs text-zinc-700 dark:text-zinc-300 font-medium"><svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Bulanan (Tgl ' . $row->day_of_month . ')</span>';
                } elseif ($row->frequency === 'daily') {
                    return '<span class="text-xs text-zinc-700 dark:text-zinc-300 font-medium">Harian</span>';
                }
                return '<span class="text-xs text-zinc-700 dark:text-zinc-300 font-medium">' . ucfirst($row->frequency) . '</span>';
            })
            ->add('status_display', function (RecurringTransaction $row) {
                $toggleUrl = route('admin.recurring_transactions.toggle_status', $row->id);
                if ($row->is_active) {
                    return '<form action="' . $toggleUrl . '" method="POST" class="inline">' . csrf_field() . '<button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60 hover:opacity-80 transition-opacity" title="Klik untuk nonaktifkan"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</button></form>';
                }
                return '<form action="' . $toggleUrl . '" method="POST" class="inline">' . csrf_field() . '<button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200/60 hover:opacity-80 transition-opacity" title="Klik untuk aktifkan"><span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>Nonaktif</button></form>';
            })
            ->add('last_generated_formatted', function (RecurringTransaction $row) {
                return $row->last_generated_date ? $row->last_generated_date->translatedFormat('d M Y') : '<span class="text-zinc-400 text-xs">Belum pernah</span>';
            })
            ->add('action', function (RecurringTransaction $row) {
                $html = '<div class="flex items-center justify-center gap-1.5">';

                // Manual trigger button "Eksekusi Sekarang"
                $executeUrl = route('admin.recurring_transactions.execute_now', $row->id);
                $html .= '<form action="' . $executeUrl . '" method="POST" class="inline" onsubmit="return confirm(\'Catat transaksi ini ke kas sekarang?\')">';
                $html .= csrf_field();
                $html .= '<button type="submit" class="p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-950/40 rounded-lg transition-colors" title="Catat Sekarang">';
                $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                $html .= '</button></form>';

                $editUrl = route('admin.recurring_transactions.edit', $row->id);
                $html .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">';
                $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
                $html .= '</a>';

                $deleteUrl = route('admin.recurring_transactions.destroy', $row->id);
                $html .= '<button onclick="window.dispatchEvent(new CustomEvent(\'open-delete-modal\', { detail: { action: \'' . $deleteUrl . '\' } }))" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus">';
                $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                $html .= '</button>';

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

            Column::make('Nama Jadwal', 'name_display', 'name')->sortable()->searchable(),
            Column::make('Tipe', 'type_badge', 'type')->sortable(),
            Column::make('Kategori', 'category_display'),
            Column::make('Dompet / Rekening', 'account_display'),
            Column::make('Nominal', 'amount_formatted', 'amount')->sortable(),
            Column::make('Jadwal', 'frequency_display'),
            Column::make('Status', 'status_display')->headerAttribute('text-center')->bodyAttribute('text-center'),
            Column::make('Terakhir Dicatat', 'last_generated_formatted'),
            Column::make('Aksi', 'action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center')
                ->visibleInExport(false),
        ];
    }

    #[\Livewire\Attributes\On('triggerBulkDelete')]
    public function triggerBulkDelete(?array $ids = null): void
    {
        if (!auth()->user() || !auth()->user()->hasPermission('delete-cash_transactions')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus transaksi rutin.');
            return;
        }

        if (!$ids) {
            $ids = $this->checkboxValues;
        }

        if (empty($ids)) return;

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $ids,
            'model' => 'App\\\\Models\\\\RecurringTransaction',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }

    #[\Livewire\Attributes\On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        if (!auth()->user() || !auth()->user()->hasPermission('delete-cash_transactions')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus transaksi rutin.');
            return;
        }

        try {
            $deletedCount = RecurringTransaction::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(RecurringTransaction::class, $deletedCount, $ids);

            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: $deletedCount . ' jadwal transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal menghapus jadwal transaksi.');
        }
    }
}
