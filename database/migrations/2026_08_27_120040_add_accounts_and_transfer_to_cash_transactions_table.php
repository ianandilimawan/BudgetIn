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
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->foreignId('account_id')
                ->nullable()
                ->after('user_id')
                ->constrained('cash_accounts')
                ->nullOnDelete();

            $table->foreignId('to_account_id')
                ->nullable()
                ->after('account_id')
                ->constrained('cash_accounts')
                ->nullOnDelete();
        });

        // Modify category_id to be nullable (for internal transfers) and update type enum
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });

        // Support 'transfer' in type enum
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE cash_transactions MODIFY COLUMN type ENUM('income', 'expense', 'transfer') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['to_account_id']);
            $table->dropColumn(['account_id', 'to_account_id']);
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE cash_transactions MODIFY COLUMN type ENUM('income', 'expense') NOT NULL");
        }
    }
};
