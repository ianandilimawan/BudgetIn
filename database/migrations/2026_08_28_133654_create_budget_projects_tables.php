<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('icon')->default('✨');
            $table->decimal('target_amount', 15, 2);
            $table->date('target_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
        });

        Schema::create('budget_project_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_project_id')->constrained('budget_projects')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['budget_project_id', 'status']);
        });

        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->foreignId('budget_project_id')->nullable()->after('category_id')->constrained('budget_projects')->nullOnDelete();
            $table->foreignId('budget_project_item_id')->nullable()->after('budget_project_id')->constrained('budget_project_items')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropForeign(['budget_project_id']);
            $table->dropForeign(['budget_project_item_id']);
            $table->dropColumn(['budget_project_id', 'budget_project_item_id']);
        });

        Schema::dropIfExists('budget_project_items');
        Schema::dropIfExists('budget_projects');
    }
};
