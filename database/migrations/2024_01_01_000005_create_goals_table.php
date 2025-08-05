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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->date('target_date')->nullable();
            $table->string('category', 100);
            $table->enum('type', ['saving', 'investment', 'debt_payment', 'expense_reduction'])->default('saving');
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('auto_save')->default(false);
            $table->decimal('auto_save_amount', 15, 2)->nullable();
            $table->enum('auto_save_frequency', ['daily', 'weekly', 'monthly'])->nullable();
            $table->foreignId('linked_account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->boolean('reminder_enabled')->default(false);
            $table->string('reminder_frequency', 20)->nullable();
            $table->text('notes')->nullable();
            $table->date('achievement_date')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['category', 'type']);
            $table->index(['target_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
