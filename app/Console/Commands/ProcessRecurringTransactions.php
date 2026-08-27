<?php

namespace App\Console\Commands;

use App\Models\CashTransaction;
use App\Models\RecurringTransaction;
use Illuminate\Console\Command;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'app:generate-recurring-transactions';
    protected $description = 'Generate cash transactions from active recurring schedules that are due';

    public function handle(): int
    {
        $this->info('Checking recurring transactions...');
        $recurringSchedules = RecurringTransaction::where('is_active', true)->get();
        $generatedCount = 0;

        foreach ($recurringSchedules as $schedule) {
            if ($schedule->isDueToday()) {
                CashTransaction::create([
                    'user_id' => $schedule->user_id,
                    'type' => $schedule->type,
                    'category_id' => $schedule->category_id,
                    'account_id' => $schedule->account_id,
                    'to_account_id' => $schedule->to_account_id,
                    'amount' => $schedule->amount,
                    'transaction_date' => now()->format('Y-m-d'),
                    'note' => ($schedule->note ? $schedule->note . ' - ' : '') . '[Transaksi Berulang Otomatis: ' . $schedule->name . ']',
                ]);

                $schedule->last_generated_date = now()->toDateString();
                $schedule->save();
                $generatedCount++;

                $this->info("Generated: {$schedule->name} (Rp " . number_format($schedule->amount, 0, ',', '.') . ") for User #{$schedule->user_id}");
            }
        }

        $this->info("Completed. {$generatedCount} recurring transactions generated.");
        return Command::SUCCESS;
    }
}
