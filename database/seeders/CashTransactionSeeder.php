<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashTransaction;
use App\Models\CashAccount;
use App\Models\TransactionCategory;
use App\Models\User;

class CashTransactionSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        CashTransaction::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;

        // Ensure accounts exist
        $bankBca = CashAccount::firstOrCreate(['name' => 'Bank BCA'], [
            'type' => 'bank',
            'account_number' => 'BCA Rekening Utama',
            'color' => 'blue',
            'initial_balance' => 5000000,
            'is_active' => true,
        ]);

        $dompetTunai = CashAccount::firstOrCreate(['name' => 'Dompet Tunai Ian'], [
            'type' => 'cash',
            'color' => 'emerald',
            'initial_balance' => 500000,
            'is_active' => true,
        ]);

        $ewallet = CashAccount::firstOrCreate(['name' => 'E-Wallet (Gopay / Ovo)'], [
            'type' => 'ewallet',
            'color' => 'purple',
            'initial_balance' => 250000,
            'is_active' => true,
        ]);

        // Categories
        $catGaji = TransactionCategory::firstOrCreate(['name' => 'Gaji', 'type' => 'income'], ['is_active' => true]);
        $catBonus = TransactionCategory::firstOrCreate(['name' => 'Bonus & THR', 'type' => 'income'], ['is_active' => true]);

        $catBelanja = TransactionCategory::firstOrCreate(['name' => 'Belanja', 'type' => 'expense'], ['is_active' => true]);
        $catMakanan = TransactionCategory::firstOrCreate(['name' => 'Makanan & Minuman', 'type' => 'expense'], ['is_active' => true]);
        $catTagihan = TransactionCategory::firstOrCreate(['name' => 'Tagihan & Utilitas', 'type' => 'expense'], ['is_active' => true]);
        $catPendidikan = TransactionCategory::firstOrCreate(['name' => 'Pendidikan', 'type' => 'expense'], ['is_active' => true]);
        $catKesehatan = TransactionCategory::firstOrCreate(['name' => 'Kesehatan', 'type' => 'expense'], ['is_active' => true]);
        $catTransport = TransactionCategory::firstOrCreate(['name' => 'Transportasi', 'type' => 'expense'], ['is_active' => true]);
        $catHiburan = TransactionCategory::firstOrCreate(['name' => 'Hiburan', 'type' => 'expense'], ['is_active' => true]);

        $transactions = [
            // ==========================================
            // 1. BULAN JULI 2026 (DEFISIT / -Rp 4.750.000)
            // ==========================================
            // Pemasukan Juli: Rp 10.000.000
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catGaji->id,
                'type' => 'income',
                'amount' => 10000000,
                'transaction_date' => '2026-07-01',
                'note' => 'Gaji Pokok Bulan Juli 2026',
            ],

            // Pengeluaran Juli: Rp 14.750.000 (Total Pengeluaran > Pemasukan)
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catPendidikan->id,
                'type' => 'expense',
                'amount' => 5500000,
                'transaction_date' => '2026-07-05',
                'note' => 'Uang Pangkal & Buku Semester Baru Anak',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catBelanja->id,
                'type' => 'expense',
                'amount' => 3800000,
                'transaction_date' => '2026-07-08',
                'note' => 'Belanja Kebutuhan Pokok & Sembako Bulanan',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catKesehatan->id,
                'type' => 'expense',
                'amount' => 2450000,
                'transaction_date' => '2026-07-15',
                'note' => 'Pemeriksaan Dokter Spesialis & Resep Obat',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catTagihan->id,
                'type' => 'expense',
                'amount' => 1350000,
                'transaction_date' => '2026-07-18',
                'note' => 'Listrik PLN, PDAM, & Wifi Indihome',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $ewallet->id,
                'to_account_id' => null,
                'category_id' => $catMakanan->id,
                'type' => 'expense',
                'amount' => 1650000,
                'transaction_date' => '2026-07-22',
                'note' => 'Makan & Kuliner Mingguan Keluarga',
            ],

            // Transfer / Tarik Tunai Juli
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => $dompetTunai->id,
                'category_id' => null,
                'type' => 'transfer',
                'amount' => 1000000,
                'transaction_date' => '2026-07-03',
                'note' => 'Tarik tunai ATM BCA untuk pegangan kas dompet',
            ],

            // ==========================================
            // 2. BULAN AGUSTUS 2026 (SURPLUS / +Rp 9.200.000)
            // ==========================================
            // Pemasukan Agustus: Rp 16.500.000
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catGaji->id,
                'type' => 'income',
                'amount' => 13500000,
                'transaction_date' => '2026-08-01',
                'note' => 'Gaji Pokok Bulan Agustus 2026',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catBonus->id,
                'type' => 'income',
                'amount' => 3000000,
                'transaction_date' => '2026-08-15',
                'note' => 'Insentif Bonus Project Kemerdekaan',
            ],

            // Pengeluaran Agustus: Rp 7.300.000 (Pemasukan > Pengeluaran = SURPLUS)
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catBelanja->id,
                'type' => 'expense',
                'amount' => 3200000,
                'transaction_date' => '2026-08-03',
                'note' => 'Belanja Bulanan Supermarket',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => null,
                'category_id' => $catTagihan->id,
                'type' => 'expense',
                'amount' => 1250000,
                'transaction_date' => '2026-08-06',
                'note' => 'Tagihan Listrik & Internet Bulanan',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $ewallet->id,
                'to_account_id' => null,
                'category_id' => $catMakanan->id,
                'type' => 'expense',
                'amount' => 1500000,
                'transaction_date' => '2026-08-12',
                'note' => 'Makan bersama keluarga di akhir pekan',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $dompetTunai->id,
                'to_account_id' => null,
                'category_id' => $catTransport->id,
                'type' => 'expense',
                'amount' => 750000,
                'transaction_date' => '2026-08-16',
                'note' => 'Bensin & e-Toll Perjalanan Luar Kota',
            ],
            [
                'user_id' => $adminId,
                'account_id' => $ewallet->id,
                'to_account_id' => null,
                'category_id' => $catHiburan->id,
                'type' => 'expense',
                'amount' => 600000,
                'transaction_date' => '2026-08-20',
                'note' => 'Tiket Bioskop & Langganan Netflix/Spotify',
            ],

            // Transfer / Tarik Tunai Agustus
            [
                'user_id' => $adminId,
                'account_id' => $bankBca->id,
                'to_account_id' => $ewallet->id,
                'category_id' => null,
                'type' => 'transfer',
                'amount' => 1500000,
                'transaction_date' => '2026-08-10',
                'note' => 'Top up GoPay untuk pembayaran utilitas dan makan',
            ],
        ];

        // Seed transactions for Finance user if exists
        $financeUser = User::where('email', 'finance@intechstudio.id')->first();
        if ($financeUser) {
            $mandiri = CashAccount::firstOrCreate(
                ['user_id' => $financeUser->id, 'name' => 'Bank Mandiri Operasional'],
                ['type' => 'bank', 'initial_balance' => 25000000, 'color' => 'blue', 'is_active' => true]
            );
            $pettyCash = CashAccount::firstOrCreate(
                ['user_id' => $financeUser->id, 'name' => 'Petty Cash Finance'],
                ['type' => 'cash', 'initial_balance' => 3000000, 'color' => 'emerald', 'is_active' => true]
            );

            $transactions[] = [
                'user_id' => $financeUser->id,
                'account_id' => $mandiri->id,
                'to_account_id' => null,
                'category_id' => $catGaji->id,
                'type' => 'income',
                'amount' => 45000000,
                'transaction_date' => '2026-08-01',
                'note' => 'Penerimaan Invoice Pembayaran Project Klien',
            ];

            $transactions[] = [
                'user_id' => $financeUser->id,
                'account_id' => $mandiri->id,
                'to_account_id' => null,
                'category_id' => $catBelanja->id,
                'type' => 'expense',
                'amount' => 12500000,
                'transaction_date' => '2026-08-05',
                'note' => 'Pembayaran Sewa Server & Software Subscriptions',
            ];

            $transactions[] = [
                'user_id' => $financeUser->id,
                'account_id' => $pettyCash->id,
                'to_account_id' => null,
                'category_id' => $catMakanan->id,
                'type' => 'expense',
                'amount' => 850000,
                'transaction_date' => '2026-08-10',
                'note' => 'Konsumsi Meeting & Snack Karyawan',
            ];

            $transactions[] = [
                'user_id' => $financeUser->id,
                'account_id' => $mandiri->id,
                'to_account_id' => $pettyCash->id,
                'category_id' => null,
                'type' => 'transfer',
                'amount' => 2000000,
                'transaction_date' => '2026-08-08',
                'note' => 'Isi Ulang Petty Cash Finance Mingguan',
            ];
        }

        foreach ($transactions as $tx) {
            CashTransaction::create($tx);
        }
    }
}
