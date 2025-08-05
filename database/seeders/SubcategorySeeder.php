<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    public function run()
    {
        // Primeiro, adicionar a coluna parent_id se não existir
        try {
            DB::statement('ALTER TABLE categories ADD COLUMN parent_id BIGINT UNSIGNED NULL AFTER id');
            DB::statement('ALTER TABLE categories ADD FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Coluna já existe
        }

        // Inserir categorias principais de teste se não existirem
        $revenueCategories = [
            'Salário' => ['Salário CLT', 'Salário PJ', 'Freelance'],
            'Investimentos' => ['Dividendos', 'Renda Fixa', 'Renda Variável'],
            'Vendas' => ['Produtos', 'Serviços', 'Comissões'],
        ];

        $expenseCategories = [
            'Alimentação' => ['Supermercado', 'Restaurantes', 'Delivery'],
            'Transporte' => ['Combustível', 'Uber/Taxi', 'Transporte Público'],
            'Moradia' => ['Aluguel', 'Condomínio', 'IPTU'],
            'Saúde' => ['Plano de Saúde', 'Medicamentos', 'Consultas'],
        ];

        // Inserir categorias de receita
        foreach ($revenueCategories as $parentName => $subcategories) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $parentName,
                'type' => 'revenue',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($subcategories as $subName) {
                DB::table('categories')->insert([
                    'name' => $subName,
                    'type' => 'revenue',
                    'parent_id' => $parentId,
                    'user_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Inserir categorias de despesa
        foreach ($expenseCategories as $parentName => $subcategories) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $parentName,
                'type' => 'expense',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($subcategories as $subName) {
                DB::table('categories')->insert([
                    'name' => $subName,
                    'type' => 'expense',
                    'parent_id' => $parentId,
                    'user_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
