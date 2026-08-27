<?php

namespace App\Livewire\Tables;

use App\Models\CashTransaction;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

class CashTransactionTable extends PowerGridComponent
{
    public string $tableName = 'cashTransaction-table';
    public string $sortField = 'transaction_date';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $canDelete = auth()->user() && auth()->user()->hasPermission('delete-cash_transactions');

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
        $query = CashTransaction::query()
            ->where('user_id', auth()->id())
            ->with(['category', 'account', 'toAccount', 'user']);

        $period = request()->get('period', 'this_month');
        $startDate = request()->get('start_date');
        $endDate = request()->get('end_date');
        $month = request()->filled('month') ? (int) request()->get('month') : null;
        $year = request()->filled('year') ? (int) request()->get('year') : null;
        $type = request()->get('type');
        $categoryId = request()->get('category_id');
        $accountId = request()->get('account_id');

        $summaryService = app(\App\Services\CashSummaryService::class);
        $dateRange = $summaryService->parseDateRange($period, $startDate, $endDate, $month, $year);

        if (!$dateRange['is_all_time']) {
            if ($dateRange['start_date'] && $dateRange['end_date']) {
                $query->whereBetween('transaction_date', [$dateRange['start_date'], $dateRange['end_date']]);
            } elseif ($dateRange['start_date']) {
                $query->where('transaction_date', '>=', $dateRange['start_date']);
            } elseif ($dateRange['end_date']) {
                $query->where('transaction_date', '<=', $dateRange['end_date']);
            }
        }

        if ($type && in_array($type, ['income', 'expense', 'transfer'])) {
            $query->where('type', $type);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($accountId) {
            $query->where(function ($q) use ($accountId) {
                $q->where('account_id', $accountId)->orWhere('to_account_id', $accountId);
            });
        }

        return $query;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('category_name', function (CashTransaction $model) {
                if ($model->type === 'transfer') {
                    return '<span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium inline-flex items-center gap-1.5"><div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg></div> Transfer / Pindah Kas</span>';
                }
                if ($model->category) {
                    $badge = \App\Helpers\CategoryIconHelper::renderBadge($model->category->icon, $model->category->type ?? $model->type, 'w-6 h-6');
                    return '<div class="flex items-center gap-2">' . $badge . '<span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">' . e($model->category->name) . '</span></div>';
                }
                return '<span class="text-xs text-zinc-400">-</span>';
            })
            ->add('account_display', function (CashTransaction $model) {
                if ($model->type === 'transfer') {
                    $from = $model->account?->name ?? '-';
                    $to = $model->toAccount?->name ?? '-';
                    return '<div class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 flex items-center gap-1.5"><span class="text-rose-600 dark:text-rose-400">' . e($from) . '</span> ➔ <span class="text-emerald-600 dark:text-emerald-400">' . e($to) . '</span></div>';
                }
                $acc = $model->account?->name ?? 'Kas Utama';
                return '<span class="text-xs text-zinc-600 dark:text-zinc-400 font-medium">' . e($acc) . '</span>';
            })
            ->add('creator_name', function (CashTransaction $model) {
                $name = $model->user?->name ?? 'Admin';
                return '<div class="flex items-center gap-1.5"><span class="w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-[10px] font-bold uppercase">' . substr($name, 0, 1) . '</span><span class="text-xs text-zinc-700 dark:text-zinc-300 font-medium">' . e($name) . '</span></div>';
            })
            ->add('type_badge', function (CashTransaction $model) {
                if ($model->type === 'income') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Pemasukan</span>';
                } elseif ($model->type === 'expense') {
                    return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">Pengeluaran</span>';
                }
                return '<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Transfer</span>';
            })
            ->add('amount_formatted', function (CashTransaction $model) {
                $formatted = 'Rp ' . number_format($model->amount, 0, ',', '.');
                if ($model->type === 'income') {
                    return '<span class="font-semibold text-emerald-600 dark:text-emerald-400">+' . $formatted . '</span>';
                } elseif ($model->type === 'expense') {
                    return '<span class="font-semibold text-rose-600 dark:text-rose-400">-' . $formatted . '</span>';
                }
                return '<span class="font-semibold text-indigo-600 dark:text-indigo-400">' . $formatted . '</span>';
            })
            ->add('transaction_date_formatted', fn (CashTransaction $model) => $model->transaction_date ? \Carbon\Carbon::parse($model->transaction_date)->format('d M Y') : '-')
            ->add('note', fn (CashTransaction $model) => e($model->note ?? '-'))
            ->add('proof_display', function (CashTransaction $model) {
                if (!$model->proof) {
                    return '<span class="text-zinc-300 dark:text-zinc-700">-</span>';
                }
                $url = $model->proof_url;
                $isPdf = str_ends_with(strtolower($model->proof), '.pdf');
                if ($isPdf) {
                    return '<a href="' . $url . '" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-300 transition-colors" title="Lihat PDF Bukti"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> PDF</a>';
                }
                return '<button type="button" onclick="window.dispatchEvent(new CustomEvent(\'preview-receipt\', { detail: { url: \'' . $url . '\', title: \'Bukti Transaksi #' . $model->id . '\' } }))" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-300 transition-colors" title="Klik untuk lihat bukti"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Bukti</button>';
            })
            ->add('action', function (CashTransaction $row) {
                $actions = '<div class="flex items-center justify-center gap-1">';

                if (auth()->user() && auth()->user()->hasPermission('view-cash_transactions')) {
                    $viewUrl = route('admin.cash_transactions.show', $row->id);
                    $actions .= '<a href="' . $viewUrl . '" class="p-1.5 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('edit-cash_transactions')) {
                    $editUrl = route('admin.cash_transactions.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('delete-cash_transactions')) {
                    $deleteUrl = route('admin.cash_transactions.destroy', $row->id);
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

            Column::make('Date', 'transaction_date_formatted', 'transaction_date')->sortable(),
            Column::make('Type', 'type_badge', 'type')->sortable(),
            Column::make('Category', 'category_name'),
            Column::make('Wallet / Akun', 'account_display'),
            Column::make('Amount', 'amount_formatted', 'amount')->sortable(),
            Column::make('Bukti', 'proof_display')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),
            Column::make('Note', 'note')->searchable(),
            Column::make('User', 'creator_name'),
            Column::make('Actions', 'action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center')
                ->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('triggerBulkDelete')]
    public function triggerBulkDelete(?array $ids = null): void
    {
        if (!auth()->user() || !auth()->user()->hasPermission('delete-cash_transactions')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus transaksi kas.');
            return;
        }

        if (!$ids) {
            $ids = $this->checkboxValues;
        }

        if (empty($ids)) return;

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $ids,
            'model' => 'App\\\\Models\\\\CashTransaction',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }

    #[\Livewire\Attributes\On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        if (!auth()->user() || !auth()->user()->hasPermission('delete-cash_transactions')) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus transaksi kas.');
            return;
        }

        try {
            $deletedCount = CashTransaction::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(CashTransaction::class, $deletedCount, $ids);

            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: $deletedCount . ' transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal menghapus transaksi.');
        }
    }
}
