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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['checking', 'savings', 'credit_card', 'investment', 'cash'])->default('checking');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('agency')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->decimal('interest_rate', 8, 4)->nullable();
            $table->string('currency', 3)->default('BRL');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#007bff');
            $table->string('icon', 50)->nullable();
            $table->boolean('sync_enabled')->default(false);
            $table->timestamp('sync_last_date')->nullable();
            $table->string('bank_integration_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
