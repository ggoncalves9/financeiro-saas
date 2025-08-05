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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'cancelled', 'expired', 'trial', 'suspended'])->default('trial');
            $table->decimal('amount', 10, 2);
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->datetime('trial_ends_at')->nullable();
            $table->datetime('next_billing_date')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->json('metadata')->nullable(); // Dados extras da assinatura
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['plan_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
