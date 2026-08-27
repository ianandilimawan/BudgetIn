<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashAccount;
use App\Models\User;

class CashAccountSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@intechstudio.id')->first();
        $finance = User::where('email', 'finance@intechstudio.id')->first();

        $adminId = $admin ? $admin->id : 1;
        $financeId = $finance ? $finance->id : 2;

        $accounts = [
            // Admin Accounts
            [
                'user_id' => $adminId,
                'name' => 'Dompet Tunai Ian',
                'type' => 'cash',
                'account_number' => null,
                'icon' => null,
                'color' => 'emerald',
                'initial_balance' => 500000,
                'is_active' => true,
            ],
            [
                'user_id' => $adminId,
                'name' => 'Dompet Tunai Lulu',
                'type' => 'cash',
                'account_number' => null,
                'icon' => null,
                'color' => 'rose',
                'initial_balance' => 300000,
                'is_active' => true,
            ],
            [
                'user_id' => $adminId,
                'name' => 'Bank BCA',
                'type' => 'bank',
                'account_number' => 'BCA Rekening Utama',
                'icon' => null,
                'color' => 'blue',
                'initial_balance' => 5000000,
                'is_active' => true,
            ],
            [
                'user_id' => $adminId,
                'name' => 'E-Wallet (Gopay / Ovo)',
                'type' => 'ewallet',
                'account_number' => '0812xxxxxxx',
                'icon' => null,
                'color' => 'purple',
                'initial_balance' => 250000,
                'is_active' => true,
            ],

            // Finance User Accounts
            [
                'user_id' => $financeId,
                'name' => 'Bank Mandiri Operasional',
                'type' => 'bank',
                'account_number' => '137-00-123456-7',
                'icon' => null,
                'color' => 'blue',
                'initial_balance' => 25000000,
                'is_active' => true,
            ],
            [
                'user_id' => $financeId,
                'name' => 'Petty Cash Finance',
                'type' => 'cash',
                'account_number' => 'Brankas Finance Lt 2',
                'icon' => null,
                'color' => 'emerald',
                'initial_balance' => 3000000,
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $acc) {
            CashAccount::updateOrCreate(
                ['user_id' => $acc['user_id'], 'name' => $acc['name']],
                $acc
            );
        }
    }
}
