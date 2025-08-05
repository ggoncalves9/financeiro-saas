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
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('category', 100);
            $table->string('subcategory', 100)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('reference_number')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_document')->nullable();
            $table->boolean('recurring')->default(false);
            $table->enum('recurring_type', ['weekly', 'monthly', 'yearly'])->nullable();
            $table->date('recurring_until')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_url')->nullable();
            $table->boolean('is_business_revenue')->default(false);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->string('invoice_number')->nullable();
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'date']);
            $table->index(['category', 'subcategory']);
            $table->index(['status', 'date']);
            $table->index(['is_business_revenue']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
