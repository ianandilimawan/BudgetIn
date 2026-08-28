<?php

namespace App\Http\Controllers;

use App\Models\BudgetProject;
use App\Models\BudgetProjectItem;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Services\ActivityLogService;
use App\Services\FileUploadService;
use App\Services\GeminiAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetProjectController extends Controller
{
    protected GeminiAiService $geminiAi;

    public function __construct(GeminiAiService $geminiAi)
    {
        $this->geminiAi = $geminiAi;
    }

    /**
     * Display a listing of budget projects.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $status = $request->get('status', 'active'); // 'all', 'active', 'completed', 'cancelled'

        $query = BudgetProject::forUser($userId)
            ->with(['items', 'transactions'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $projects = $query->paginate(12)->withQueryString();

        // Calculate summary stats across all projects of this user
        $allProjects = BudgetProject::forUser($userId)->with(['items', 'transactions'])->get();
        $totalProjectsCount = $allProjects->count();
        $activeProjectsCount = $allProjects->where('status', 'active')->count();
        $completedProjectsCount = $allProjects->where('status', 'completed')->count();
        $totalTargetOverall = $allProjects->where('status', 'active')->sum('target_amount');
        $totalSpentOverall = $allProjects->where('status', 'active')->sum('total_spent');
        $totalRemainingOverall = max(0, $totalTargetOverall - $totalSpentOverall);

        return view('admin.pages.budget_projects.index', compact(
            'projects',
            'status',
            'totalProjectsCount',
            'activeProjectsCount',
            'completedProjectsCount',
            'totalTargetOverall',
            'totalSpentOverall',
            'totalRemainingOverall'
        ));
    }

    /**
     * Store a newly created budget project.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:20',
            'target_amount' => 'required|numeric|min:1',
            'target_date' => 'nullable|date',
            'note' => 'nullable|string|max:1000',
            'items' => 'nullable|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.target_amount' => 'required_with:items|numeric|min:0',
        ], [
            'name.required' => 'Nama proyek / rencana acara wajib diisi.',
            'target_amount.required' => 'Total pagu anggaran wajib diisi.',
            'target_amount.min' => 'Total pagu anggaran minimal Rp 1.',
        ]);

        DB::beginTransaction();
        try {
            $project = BudgetProject::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'icon' => $request->icon ?: '✨',
                'target_amount' => $request->target_amount,
                'target_date' => $request->target_date,
                'status' => 'active',
                'note' => $request->note,
            ]);

            // Create initial sub-items if provided
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $itemData) {
                    if (!empty($itemData['name'])) {
                        BudgetProjectItem::create([
                            'budget_project_id' => $project->id,
                            'name' => $itemData['name'],
                            'target_amount' => (float) ($itemData['target_amount'] ?? 0),
                            'spent_amount' => 0,
                            'status' => 'pending',
                        ]);
                    }
                }
            }

            ActivityLogService::logCustom([
                'action' => 'Create',
                'model_type' => BudgetProject::class,
                'model_id' => $project->id,
                'user_id' => auth()->id(),
                'description' => "Membuat proyek anggaran baru: '{$project->name}' senilai {$project->target_amount_formatted}.",
            ]);

            DB::commit();

            return redirect()->route('admin.budget_projects.show', $project->id)
                ->with('success', "Proyek '{$project->name}' berhasil dibuat! Mari atur pos rincian anggarannya.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat proyek: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified budget project dashboard.
     */
    public function show(BudgetProject $budgetProject)
    {
        if ($budgetProject->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        $budgetProject->load(['items.transactions', 'transactions.account', 'transactions.category']);

        // Accounts and Categories for quick logging inside this project
        $accounts = CashAccount::forUser(auth()->id())->where('is_active', true)->get();
        $categories = TransactionCategory::forUser(auth()->id())->where('type', 'expense')->get();

        // Project AI Insights
        $aiInsights = $this->getProjectAiInsights($budgetProject);

        return view('admin.pages.budget_projects.show', compact(
            'budgetProject',
            'accounts',
            'categories',
            'aiInsights'
        ));
    }

    /**
     * Update the specified budget project.
     */
    public function update(Request $request, BudgetProject $budgetProject)
    {
        if ($budgetProject->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:20',
            'target_amount' => 'required|numeric|min:1',
            'target_date' => 'nullable|date',
            'status' => 'required|in:active,completed,cancelled',
            'note' => 'nullable|string|max:1000',
        ]);

        $budgetProject->update([
            'name' => $request->name,
            'icon' => $request->icon ?: '✨',
            'target_amount' => $request->target_amount,
            'target_date' => $request->target_date,
            'status' => $request->status,
            'note' => $request->note,
        ]);

        ActivityLogService::logCustom([
            'action' => 'Update',
            'model_type' => BudgetProject::class,
            'model_id' => $budgetProject->id,
            'user_id' => auth()->id(),
            'description' => "Memperbarui data proyek: '{$budgetProject->name}'.",
        ]);

        return back()->with('success', 'Data proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified budget project.
     */
    public function destroy(BudgetProject $budgetProject)
    {
        if ($budgetProject->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        $projectName = $budgetProject->name;
        $budgetProject->delete();

        ActivityLogService::logCustom([
            'action' => 'Delete',
            'model_type' => BudgetProject::class,
            'model_id' => $budgetProject->id,
            'user_id' => auth()->id(),
            'description' => "Menghapus proyek anggaran: '{$projectName}'.",
        ]);

        return redirect()->route('admin.budget_projects.index')
            ->with('success', "Proyek '{$projectName}' berhasil dihapus.");
    }

    /**
     * Add a sub-budget item to a project.
     */
    public function addItem(Request $request, BudgetProject $budgetProject)
    {
        if ($budgetProject->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama pos rincian wajib diisi.',
            'target_amount.required' => 'Target anggaran pos wajib diisi.',
        ]);

        $item = BudgetProjectItem::create([
            'budget_project_id' => $budgetProject->id,
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'spent_amount' => 0,
            'status' => 'pending',
            'note' => $request->note,
        ]);

        return back()->with('success', "Pos '{$item->name}' berhasil ditambahkan ke rencana anggaran!");
    }

    /**
     * Update a sub-budget item.
     */
    public function updateItem(Request $request, BudgetProject $budgetProject, BudgetProjectItem $budgetProjectItem)
    {
        if ($budgetProject->user_id !== auth()->id() || $budgetProjectItem->budget_project_id !== $budgetProject->id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed',
            'note' => 'nullable|string|max:500',
        ]);

        $budgetProjectItem->update([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return back()->with('success', "Pos '{$budgetProjectItem->name}' berhasil diperbarui.");
    }

    /**
     * Toggle status of a sub-budget item.
     */
    public function toggleItemStatus(Request $request, BudgetProject $budgetProject, BudgetProjectItem $budgetProjectItem)
    {
        if ($budgetProject->user_id !== auth()->id() || $budgetProjectItem->budget_project_id !== $budgetProject->id) {
            abort(403, 'Akses ditolak.');
        }

        $current = $budgetProjectItem->status;
        $next = $current === 'completed' ? 'pending' : 'completed';

        $budgetProjectItem->update(['status' => $next]);

        return back()->with('success', "Status pos '{$budgetProjectItem->name}' diubah menjadi " . ($next === 'completed' ? 'Selesai / Lunas' : 'Belum Selesai') . ".");
    }

    /**
     * Delete a sub-budget item.
     */
    public function deleteItem(BudgetProject $budgetProject, BudgetProjectItem $budgetProjectItem)
    {
        if ($budgetProject->user_id !== auth()->id() || $budgetProjectItem->budget_project_id !== $budgetProject->id) {
            abort(403, 'Akses ditolak.');
        }

        $itemName = $budgetProjectItem->name;
        $budgetProjectItem->delete();

        return back()->with('success', "Pos '{$itemName}' berhasil dihapus.");
    }

    /**
     * Store transaction directly linked to this project.
     */
    public function storeTransaction(Request $request, BudgetProject $budgetProject)
    {
        if ($budgetProject->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'account_id' => 'required|exists:cash_accounts,id',
            'budget_project_item_id' => 'nullable|exists:budget_project_items,id',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'note' => 'required|string|max:255',
            'proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'account_id.required' => 'Pilih rekening/dompet sumber pembayaran.',
            'amount.required' => 'Nominal pengeluaran wajib diisi.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'note.required' => 'Keterangan pengeluaran wajib diisi.',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = FileUploadService::uploadFile($request->file('proof'), 'proofs');
        }

        $transaction = CashTransaction::create([
            'user_id' => auth()->id(),
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'budget_project_id' => $budgetProject->id,
            'budget_project_item_id' => $request->budget_project_item_id,
            'type' => 'expense',
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'note' => $request->note,
            'proof' => $proofPath,
        ]);

        // If linked to an item, also update spent_amount in item
        if ($request->budget_project_item_id) {
            $item = BudgetProjectItem::find($request->budget_project_item_id);
            if ($item) {
                $item->increment('spent_amount', $request->amount);
                if ($item->status === 'pending') {
                    $item->update(['status' => 'in_progress']);
                }
            }
        }

        ActivityLogService::logCustom([
            'action' => 'Create',
            'model_type' => CashTransaction::class,
            'model_id' => $transaction->id,
            'user_id' => auth()->id(),
            'description' => "Mencatat pengeluaran proyek '{$budgetProject->name}': Rp " . number_format($request->amount, 0, ',', '.') . " ({$request->note}).",
        ]);

        return back()->with('success', 'Pengeluaran proyek berhasil dicatat!');
    }

    /**
     * Refresh AI Project Insights via AJAX.
     */
    public function refreshAi(BudgetProject $budgetProject)
    {
        if ($budgetProject->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $insights = $this->getProjectAiInsights($budgetProject, true);
        return response()->json($insights);
    }

    /**
     * Helper to get or generate AI project analysis.
     */
    protected function getProjectAiInsights(BudgetProject $project, bool $forceRefresh = false): array
    {
        $cacheKey = "project_ai_insights_p{$project->id}";
        if ($forceRefresh) {
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
        }

        if (!$forceRefresh && \Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return \Illuminate\Support\Facades\Cache::get($cacheKey);
        }

        $project->load(['items', 'transactions']);
        $totalTarget = (float) $project->target_amount;
        $totalSpent = (float) $project->total_spent;
        $remaining = (float) $project->remaining_budget;
        $pct = $project->actual_spent_percentage;
        $days = $project->days_remaining;
        $itemsCount = $project->items->count();
        $overItems = $project->items->filter(fn ($i) => $i->is_over_budget)->count();

        // 1. Summary
        if ($pct >= 100) {
            $summary = "Total pengeluaran proyek telah mencapai {$pct}% (melampaui target pagu). Evaluasi kembali pengeluaran pos tersisa.";
        } elseif ($pct >= 80) {
            $summary = "Realisasi anggaran sudah mencapai {$pct}%. Sisa anggaran tersedia Rp " . number_format($remaining, 0, ',', '.') . ".";
        } elseif ($pct > 0) {
            $summary = "Pengeluaran proyek berjalan terkendali di angka {$pct}% dari total pagu Rp " . number_format($totalTarget, 0, ',', '.') . ".";
        } else {
            $summary = "Proyek siap dimulai! Total pagu dialokasikan Rp " . number_format($totalTarget, 0, ',', '.') . " terbagi ke {$itemsCount} pos rincian.";
        }

        // 2. Timeline & Pace Insight
        if ($days !== null) {
            if ($days < 0) {
                $paceInsight = "Target tanggal acara/proyek telah terlewat " . abs($days) . " hari yang lalu.";
            } elseif ($days <= 30) {
                $paceInsight = "Mendekati hari H! Sisa waktu tersisa {$days} hari lagi. Pastikan seluruh DP dan pelunasan vendor segera difinalisasi.";
            } elseif ($days <= 90) {
                $paceInsight = "Waktu tersisa {$days} hari (~" . round($days / 30, 1) . " bulan). Alokasikan sisa dana Rp " . number_format($remaining, 0, ',', '.') . " secara bertahap.";
            } else {
                $paceInsight = "Masih ada waktu {$days} hari menuju target. Kamu bisa menabung sekitar Rp " . number_format($days > 0 ? ($remaining / ($days / 30)) : 0, 0, ',', '.') . " per bulan.";
            }
        } else {
            $paceInsight = "Atur target tanggal deadline agar Gemini AI bisa menghitung estimasi laju tabungan bulanan yang dibutuhkan.";
        }

        // 3. Sub-items Warning
        if ($overItems > 0) {
            $itemWarning = "Perhatian: Ada {$overItems} pos rincian yang belanja aktualnya melebihi alokasi rencana awal.";
        } elseif ($project->unallocated_amount > 0) {
            $itemWarning = "Terdapat dana belum dialokasikan sebesar Rp " . number_format($project->unallocated_amount, 0, ',', '.') . " dari total target.";
        } elseif ($itemsCount > 0) {
            $itemWarning = "Alokasi pos rincian sudah mencakup 100% dari total pagu anggaran proyek.";
        } else {
            $itemWarning = "Tambahkan rincian sub-pos belanja (seperti Dekorasi, Katering, dll) agar tracking dana lebih terarah.";
        }

        // 4. Actionable Tip
        if ($pct >= 100) {
            $tip = "Cari alternatif pos biaya lain yang bisa dinegosiasi ulang atau dikurangi untuk menutupi selisih kelebihan budget.";
        } elseif ($days !== null && $days <= 30) {
            $tip = "Buat checklist final pelunasan vendor H-14 dan simpan semua bukti transfer di lampiran nota transaksi.";
        } else {
            $tip = "Setiap ada pembayaran DP atau belanja perlengkapan, langsung catat lewat tombol '+ Catat Pengeluaran' agar saldo proyek selalu akurat.";
        }

        $result = [
            'summary' => $summary,
            'pace_insight' => $paceInsight,
            'item_warning' => $itemWarning,
            'actionable_tip' => $tip,
            'engine' => 'BudgetIn Smart Engine',
            'generated_at' => now()->translatedFormat('d M Y, H:i'),
        ];

        \Illuminate\Support\Facades\Cache::put($cacheKey, $result, now()->addHours(6));
        return $result;
    }
}
