<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('type', ['revenue', 'expense']);
            $table->string('name', 50);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'type']);
        });

        // Inserir categorias padrão
        $defaultCategories = [
            // Receitas
            ['user_id' => null, 'type' => 'revenue', 'name' => 'Salário'],
            ['user_id' => null, 'type' => 'revenue', 'name' => 'Freelance'],
            ['user_id' => null, 'type' => 'revenue', 'name' => 'Investimentos'],
            ['user_id' => null, 'type' => 'revenue', 'name' => 'Vendas'],
            ['user_id' => null, 'type' => 'revenue', 'name' => 'Aluguéis'],
            ['user_id' => null, 'type' => 'revenue', 'name' => 'Outros'],
            
            // Despesas
            ['user_id' => null, 'type' => 'expense', 'name' => 'Alimentação'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Transporte'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Moradia'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Saúde'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Educação'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Lazer'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Escritório'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Marketing'],
            ['user_id' => null, 'type' => 'expense', 'name' => 'Outros'],
        ];

        foreach ($defaultCategories as $category) {
            DB::table('categories')->insert([
                'user_id' => $category['user_id'],
                'type' => $category['type'],
                'name' => $category['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
