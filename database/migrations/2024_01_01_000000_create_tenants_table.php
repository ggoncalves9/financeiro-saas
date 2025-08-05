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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique();
            $table->string('stripe_id')->nullable()->index();
            $table->enum('plan', ['free', 'pro_pf', 'empresarial', 'premium_pj'])->default('free');
            $table->timestamp('plan_expires_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->string('custom_domain')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('primary_color', 7)->default('#007bff');
            $table->string('secondary_color', 7)->default('#6c757d');
            $table->string('timezone', 50)->default('America/Sao_Paulo');
            $table->string('locale', 5)->default('pt_BR');
            $table->string('currency', 3)->default('BRL');
            $table->string('date_format', 20)->default('d/m/Y');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['plan', 'is_active']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
