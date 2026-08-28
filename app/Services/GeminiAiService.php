<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected string $apiKey;
    protected string $model;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = (string) (config('services.gemini.api_key') ?: env('GEMINI_API_KEY', ''));
        $rawModel = (string) (config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-3.5-flash'));
        if (in_array($rawModel, ['gemini-1.5-flash', 'gemini-1.5-pro', 'gemini-2.0-flash', 'gemini-2.5-flash', ''])) {
            $rawModel = 'gemini-3.5-flash';
        }
        $this->model = $rawModel;
        $this->timeout = (int) (config('services.gemini.timeout') ?: env('GEMINI_TIMEOUT', 30));
    }

    /**
     * Check if Gemini API key is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get or generate AI Financial Insights for a given user & month.
     */
    public function getFinancialInsights(int $userId, array $healthData, bool $forceRefresh = false): array
    {
        $month = $healthData['month'] ?? (int) now()->format('n');
        $year = $healthData['year'] ?? (int) now()->format('Y');
        $cacheKey = "gemini_financial_insights_u{$userId}_{$month}_{$year}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        if (!$forceRefresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if ($this->isConfigured()) {
            try {
                $geminiResponse = $this->callGeminiApi($healthData);
                if ($geminiResponse) {
                    $result = array_merge($geminiResponse, [
                        'engine' => 'gemini',
                        'model' => $this->model,
                        'generated_at' => now()->translatedFormat('d M Y, H:i'),
                    ]);
                    Cache::put($cacheKey, $result, now()->addHours(12));
                    return $result;
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini AI insight generation failed, falling back to algorithmic rules.', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Fallback to Smart Algorithmic Rule Engine
        $algorithmicResponse = $this->generateRuleBasedInsights($healthData);
        $result = array_merge($algorithmicResponse, [
            'engine' => 'algorithmic',
            'model' => 'BudgetIn Smart Engine',
            'generated_at' => now()->translatedFormat('d M Y, H:i'),
        ]);
        Cache::put($cacheKey, $result, now()->addMinutes(5));
        return $result;
    }

    /**
     * Call Google Gemini REST API.
     */
    protected function callGeminiApi(array $healthData): ?array
    {
        $metrics = $healthData['metrics'];
        $score = $healthData['overall_score'];
        $status = $healthData['status_label'];
        $monthName = $healthData['month_name'];

        $topCategories = collect($metrics['top_expense_categories'])
            ->map(fn ($c) => "{$c['name']}: Rp " . number_format($c['amount'], 0, ',', '.') . " ({$c['percentage']}%)")
            ->implode(', ');

        $prompt = "Anda adalah Konsultan Keuangan Pribadi (Certified Financial Planner) profesional untuk aplikasi BudgetIn.
Analisis data keuangan pengguna berikut untuk periode {$monthName}:
- Skor Kesehatan Keuangan: {$score}/100 ({$status})
- Pemasukan Bulan Ini: Rp " . number_format($metrics['total_income'], 0, ',', '.') . "
- Pengeluaran Bulan Ini: Rp " . number_format($metrics['total_expense'], 0, ',', '.') . "
- Tabungan Bersih: Rp " . number_format($metrics['net_savings'], 0, ',', '.') . " (Rasio Tabungan: {$metrics['savings_rate_pct']}%)
- Total Saldo Kas/Bank Saat Ini: Rp " . number_format($metrics['total_liquid_balance'], 0, ',', '.') . "
- Ketahanan Dana Darurat: {$metrics['runway_months']} bulan pengeluaran
- Pos Anggaran Melebihi Batas: {$metrics['over_budget_count']} dari {$metrics['total_budgets_count']} pos anggaran
- Pengeluaran Terbesar: " . ($topCategories ?: 'Belum ada data rinci') . "

TUGAS:
Berikan evaluasi keuangan yang ringkas, personal, santun, memotivasi, dan bernasib konkret dalam format JSON murni TANPA markdown block ```json:
{
  \"summary\": \"Ringkasan 1-2 kalimat mengenai kondisi keuangan bulan ini.\",
  \"cashflow_insight\": \"1 kalimat analisis mendalam tentang rasio tabungan & pengeluaran.\",
  \"budget_warning\": \"1 kalimat peringatan atau apresiasi terkait batas anggaran/kategori pengeluaran terbesar.\",
  \"actionable_tip\": \"1-2 langkah konkret yang bisa langsung dilakukan minggu ini untuk meningkatkan kesehatan keuangan.\"
}";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $response = Http::timeout($this->timeout)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0.3,
                'maxOutputTokens' => 800,
                'thinkingConfig' => [
                    'thinkingBudget' => 0,
                ],
            ]
        ]);

        if (!$response->successful()) {
            Log::error('Gemini API HTTP Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $resultJson = $response->json();
        $parts = $resultJson['candidates'][0]['content']['parts'] ?? [];
        $rawText = null;
        foreach ($parts as $part) {
            if (isset($part['text']) && !empty($part['text'])) {
                $rawText = $part['text'];
            }
        }

        if (!$rawText) {
            return null;
        }

        // Clean any codeblock markdown wrappers
        $cleanedText = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawText));
        $parsed = json_decode($cleanedText, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
            return [
                'summary' => $parsed['summary'] ?? 'Analisis keuangan Anda telah diperbarui.',
                'cashflow_insight' => $parsed['cashflow_insight'] ?? 'Arus kas terpantau stabil.',
                'budget_warning' => $parsed['budget_warning'] ?? 'Disiplin anggaran terjaga dengan baik.',
                'actionable_tip' => $parsed['actionable_tip'] ?? 'Pertahankan konsistensi mencatat pos pengeluaran setiap hari.',
            ];
        }

        return null;
    }

    /**
     * High-accuracy algorithmic smart rules fallback.
     */
    public function generateRuleBasedInsights(array $healthData): array
    {
        $metrics = $healthData['metrics'];
        $score = $healthData['overall_score'];
        $savingsRate = $metrics['savings_rate_pct'];
        $runway = $metrics['runway_months'];
        $overBudget = $metrics['over_budget_count'];
        $topCategories = $metrics['top_expense_categories'];

        // 1. Summary
        if ($score >= 80) {
            $summary = "Kondisi keuangan bulan ini sangat sehat dan teratur! Pengelolaan arus kas dan porsi tabungan berjalan sangat disiplin.";
        } elseif ($score >= 65) {
            $summary = "Kondisi keuangan Anda stabil dan terkendali. Terdapat ruang positif untuk meningkatkan alokasi tabungan dan investasi.";
        } elseif ($score >= 50) {
            $summary = "Keuangan Anda dalam status waspada. Porsi pengeluaran mulai mendekati total pemasukan, perlu rem belanja.";
        } else {
            $summary = "Kondisi keuangan memerlukan evaluasi segera. Pengeluaran tercatat melampaui laju pemasukan aktif bulan ini.";
        }

        // 2. Cash Flow Insight
        if ($savingsRate >= 20) {
            $cashflowInsight = "Rasio tabungan Anda mencapai {$savingsRate}% (di atas target ideal 20%). Pertahankan konsistensi surplus kas ini!";
        } elseif ($savingsRate > 0) {
            $cashflowInsight = "Rasio tabungan saat ini {$savingsRate}%. Tambah sekitar " . (20 - $savingsRate) . "% lagi untuk mencapai standar ideal 20% pemasukan.";
        } elseif ($metrics['total_income'] == 0 && $metrics['total_expense'] > 0) {
            $cashflowInsight = "Belum ada catatan pemasukan bulan ini, sedangkan pengeluaran sudah tercatat Rp " . number_format($metrics['total_expense'], 0, ',', '.') . ".";
        } else {
            $cashflowInsight = "Arus kas mengalami defisit " . abs($savingsRate) . "%. Batasi transaksi non-pokok agar saldo dompet tidak menyusut.";
        }

        // 3. Budget / Category Warning
        $topCatName = !empty($topCategories) ? $topCategories[0]['name'] : null;
        $topCatPct = !empty($topCategories) ? $topCategories[0]['percentage'] : 0;

        if ($overBudget > 0) {
            $budgetWarning = "Perhatian: Ada {$overBudget} pos anggaran yang telah melampaui batas target belanja bulan ini.";
        } elseif ($topCatName && $topCatPct > 35) {
            $budgetWarning = "Pengeluaran pos '{$topCatName}' mendominasi {$topCatPct}% dari total belanja bulanan Anda.";
        } elseif ($overBudget === 0 && $metrics['total_budgets_count'] > 0) {
            $budgetWarning = "Luar biasa! Seluruh pos anggaran yang Anda tetapkan masih berada dalam batas aman.";
        } else {
            $budgetWarning = "Tetapkan batas pada menu Target Anggaran untuk memantau disiplin pos pengeluaran secara otomatis.";
        }

        // 4. Actionable Tip
        if ($runway < 3.0) {
            $actionableTip = "Prioritaskan cadangan dana darurat. Alokasikan minimal 10% pendapatan untuk mengamankan runway hingga minimal 3 bulan.";
        } elseif ($overBudget > 0) {
            $actionableTip = "Evaluasi transaksi di kategori yang over-budget dan tunda pembelian barang keinginan (wants) hingga bulan depan.";
        } elseif ($savingsRate >= 20) {
            $actionableTip = "Karena dana darurat dan tabungan Anda solid, pertimbangkan memindahkan sebagian surplus kas ke instrumen investasi.";
        } else {
            $actionableTip = "Gunakan kaidah 50/30/20 (50% Kebutuhan, 30% Keinginan, 20% Tabungan) sebagai panduan alokasi keuangan Anda.";
        }

        return [
            'summary' => $summary,
            'cashflow_insight' => $cashflowInsight,
            'budget_warning' => $budgetWarning,
            'actionable_tip' => $actionableTip,
        ];
    }
}
