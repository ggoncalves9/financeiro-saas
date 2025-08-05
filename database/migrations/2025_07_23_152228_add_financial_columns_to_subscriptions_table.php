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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->after('plan');
            $table->decimal('monthly_revenue', 15, 2)->default(0)->after('amount');
            $table->string('currency', 3)->default('BRL')->after('monthly_revenue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['amount', 'monthly_revenue', 'currency']);
        });
    }
};
