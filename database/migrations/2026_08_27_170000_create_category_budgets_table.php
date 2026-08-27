<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('transaction_categories')->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedTinyInteger('month')->nullable(); // 1-12 or null for default monthly
            $table->unsignedSmallInteger('year')->nullable(); // e.g. 2026 or null for default
            $table->timestamps();

            $table->unique(['user_id', 'category_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_budgets');
    }
};
