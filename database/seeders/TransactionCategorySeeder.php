<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Income categories
            [
                'name' => 'Gaji',
                'type' => 'income',
                'icon' => 'briefcase',
                'is_active' => true,
            ],
            [
                'name' => 'Bonus & THR',
                'type' => 'income',
                'icon' => 'gift',
                'is_active' => true,
            ],
            [
                'name' => 'Investasi & Dividen',
                'type' => 'income',
                'icon' => 'chart-bar',
                'is_active' => true,
            ],
            [
                'name' => 'Pelunasan Piutang',
                'type' => 'income',
                'icon' => 'arrow-down-left',
                'is_active' => true,
            ],
            [
                'name' => 'Pencairan Pinjaman Bank',
                'type' => 'income',
                'icon' => 'building-library',
                'is_active' => true,
            ],
            [
                'name' => 'Pendapatan Lain',
                'type' => 'income',
                'icon' => 'plus-circle',
                'is_active' => true,
            ],

            // Expense categories
            [
                'name' => 'Makanan & Minuman',
                'type' => 'expense',
                'icon' => 'utensils',
                'is_active' => true,
            ],
            [
                'name' => 'Transportasi',
                'type' => 'expense',
                'icon' => 'truck',
                'is_active' => true,
            ],
            [
                'name' => 'Belanja',
                'type' => 'expense',
                'icon' => 'shopping-bag',
                'is_active' => true,
            ],
            [
                'name' => 'Tagihan & Utilitas',
                'type' => 'expense',
                'icon' => 'bolt',
                'is_active' => true,
            ],
            [
                'name' => 'Cicilan & Hutang Bank',
                'type' => 'expense',
                'icon' => 'credit-card',
                'is_active' => true,
            ],
            [
                'name' => 'Pinjaman ke Orang / Piutang',
                'type' => 'expense',
                'icon' => 'arrow-up-right',
                'is_active' => true,
            ],
            [
                'name' => 'Hiburan',
                'type' => 'expense',
                'icon' => 'film',
                'is_active' => true,
            ],
            [
                'name' => 'Kesehatan',
                'type' => 'expense',
                'icon' => 'heart',
                'is_active' => true,
            ],
            [
                'name' => 'Pendidikan',
                'type' => 'expense',
                'icon' => 'academic-cap',
                'is_active' => true,
            ],
            [
                'name' => 'Pengeluaran Lain',
                'type' => 'expense',
                'icon' => 'minus-circle',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TransactionCategory::updateOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                $category
            );
        }
    }
}
