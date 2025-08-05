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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('member_user_id')->constrained('users')->onDelete('cascade');
            $table->string('role', 100);
            $table->json('permissions')->nullable();
            $table->string('department', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->decimal('salary', 15, 2)->nullable();
            $table->date('hire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('access_level', ['full', 'limited', 'read_only'])->default('limited');
            $table->boolean('can_approve_expenses')->default(false);
            $table->decimal('expense_limit', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['company_user_id', 'member_user_id']);
            $table->index(['company_user_id', 'is_active']);
            $table->index(['member_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
