<?php

// Script para executar as alterações no banco de dados
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Adicionar coluna parent_id se não existir
    DB::statement('ALTER TABLE categories ADD COLUMN parent_id BIGINT UNSIGNED NULL AFTER id');
    echo "Coluna parent_id adicionada com sucesso.\n";
} catch (Exception $e) {
    echo "Coluna parent_id já existe ou erro: " . $e->getMessage() . "\n";
}

try {
    // Adicionar foreign key
    DB::statement('ALTER TABLE categories ADD FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE');
    echo "Foreign key adicionada com sucesso.\n";
} catch (Exception $e) {
    echo "Foreign key já existe ou erro: " . $e->getMessage() . "\n";
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
    // Verificar se já existe
    $existing = DB::table('categories')->where('name', $parentName)->where('type', 'revenue')->where('parent_id', null)->first();
    
    if (!$existing) {
        $parentId = DB::table('categories')->insertGetId([
            'name' => $parentName,
            'type' => 'revenue',
            'user_id' => null,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Categoria de receita criada: $parentName\n";
    } else {
        $parentId = $existing->id;
        echo "Categoria de receita já existe: $parentName\n";
    }

    foreach ($subcategories as $subName) {
        $existingSub = DB::table('categories')->where('name', $subName)->where('parent_id', $parentId)->first();
        
        if (!$existingSub) {
            DB::table('categories')->insert([
                'name' => $subName,
                'type' => 'revenue',
                'parent_id' => $parentId,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Subcategoria de receita criada: $subName\n";
        } else {
            echo "Subcategoria de receita já existe: $subName\n";
        }
    }
}

// Inserir categorias de despesa
foreach ($expenseCategories as $parentName => $subcategories) {
    // Verificar se já existe
    $existing = DB::table('categories')->where('name', $parentName)->where('type', 'expense')->where('parent_id', null)->first();
    
    if (!$existing) {
        $parentId = DB::table('categories')->insertGetId([
            'name' => $parentName,
            'type' => 'expense',
            'user_id' => null,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Categoria de despesa criada: $parentName\n";
    } else {
        $parentId = $existing->id;
        echo "Categoria de despesa já existe: $parentName\n";
    }

    foreach ($subcategories as $subName) {
        $existingSub = DB::table('categories')->where('name', $subName)->where('parent_id', $parentId)->first();
        
        if (!$existingSub) {
            DB::table('categories')->insert([
                'name' => $subName,
                'type' => 'expense',
                'parent_id' => $parentId,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Subcategoria de despesa criada: $subName\n";
        } else {
            echo "Subcategoria de despesa já existe: $subName\n";
        }
    }
}

echo "Script executado com sucesso!\n";
