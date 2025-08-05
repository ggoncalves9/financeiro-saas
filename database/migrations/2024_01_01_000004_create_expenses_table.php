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
        Schema::create('expenses', function (Blueprint $table) {
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
            $table->string('supplier_name')->nullable();
            $table->string('supplier_document')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('recurring')->default(false);
            $table->enum('recurring_type', ['weekly', 'monthly', 'yearly'])->nullable();
            $table->date('recurring_until')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('paid');
            $table->date('due_date')->nullable();
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_url')->nullable();
            $table->boolean('is_business_expense')->default(false);
            $table->boolean('is_deductible')->default(false);
            $table->string('cost_center', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'date']);
            $table->index(['category', 'subcategory']);
            $table->index(['status', 'due_date']);
            $table->index(['is_business_expense']);
            $table->index(['approval_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
