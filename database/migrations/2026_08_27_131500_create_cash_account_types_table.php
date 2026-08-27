<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('icon')->nullable()->default('wallet');
            $table->string('color')->nullable()->default('zinc');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Seed default account types
        $defaultTypes = [
            [
                'name' => 'Tunai',
                'code' => 'cash',
                'icon' => 'wallet',
                'color' => 'emerald',
                'description' => 'Uang tunai fisik di dompet atau brankas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank',
                'code' => 'bank',
                'icon' => 'credit-card',
                'color' => 'blue',
                'description' => 'Rekening tabungan bank (BCA, Mandiri, BRI, BNI, dll)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'E-Wallet',
                'code' => 'ewallet',
                'icon' => 'banknotes',
                'color' => 'purple',
                'description' => 'GoPay, OVO, DANA, ShopeePay, LinkAja',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Investasi',
                'code' => 'investment',
                'icon' => 'chart-pie',
                'color' => 'amber',
                'description' => 'Bibit, Bareksa, Stockbit, Deposito, Emas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pinjaman / Paylater',
                'code' => 'loan',
                'icon' => 'arrows-right-left',
                'color' => 'rose',
                'description' => 'Kartu kredit, Paylater, Pinjaman Bank',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lainnya',
                'code' => 'other',
                'icon' => 'tag',
                'color' => 'zinc',
                'description' => 'Tipe akun atau simpanan lainnya',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('cash_account_types')->insert($defaultTypes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_account_types');
    }
};
