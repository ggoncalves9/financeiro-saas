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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // ID da transação EFI
            $table->string('txid')->nullable(); // TXID do PIX
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BRL');
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_method', ['pix', 'credit_card', 'bank_slip']);
            $table->json('gateway_response')->nullable(); // Resposta da EFI
            $table->string('qr_code')->nullable(); // QR Code do PIX
            $table->string('qr_code_image')->nullable(); // Imagem do QR Code
            $table->datetime('expires_at')->nullable(); // Expiração do PIX
            $table->datetime('paid_at')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Dados extras
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['plan_id', 'status']);
            $table->index('transaction_id');
            $table->index('txid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
