<?php
// Script web para adicionar a coluna parent_id e configurar subcategorias
echo "<h2>Script de Configuração de Categorias</h2>";
echo "<pre>";

// Usar as configurações do Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Verificar se a coluna parent_id já existe
    $columns = DB::select("SHOW COLUMNS FROM categories");
    $hasParentId = false;
    foreach ($columns as $column) {
        if ($column->Field === 'parent_id') {
            $hasParentId = true;
            break;
        }
    }
    
    if (!$hasParentId) {
        // Adicionar a coluna parent_id
        DB::statement("ALTER TABLE categories ADD COLUMN parent_id BIGINT UNSIGNED NULL AFTER id");
        echo "✅ Coluna parent_id adicionada com sucesso!\n";
        
        // Adicionar foreign key
        try {
            DB::statement("ALTER TABLE categories ADD CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE");
            echo "✅ Foreign key adicionada com sucesso!\n";
        } catch (Exception $e) {
            echo "⚠️ Foreign key já existe ou erro: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✅ Coluna parent_id já existe.\n";
    }
    
    // Inserir categorias de exemplo se não existirem
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

    echo "\n📈 Configurando categorias de RECEITA:\n";
    // Inserir categorias de receita
    foreach ($revenueCategories as $parentName => $subcategories) {
        // Verificar se categoria principal já existe
        $existing = DB::table('categories')
            ->where('name', $parentName)
            ->where('type', 'revenue')
            ->whereNull('parent_id')
            ->first();
        
        if (!$existing) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $parentName,
                'type' => 'revenue',
                'user_id' => null,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "  ✅ Categoria criada: $parentName\n";
        } else {
            $parentId = $existing->id;
            echo "  ℹ️ Categoria já existe: $parentName\n";
        }

        // Inserir subcategorias
        foreach ($subcategories as $subName) {
            $existingSub = DB::table('categories')
                ->where('name', $subName)
                ->where('parent_id', $parentId)
                ->first();
            
            if (!$existingSub) {
                DB::table('categories')->insert([
                    'name' => $subName,
                    'type' => 'revenue',
                    'user_id' => null,
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                echo "    ↳ Subcategoria criada: $subName\n";
            } else {
                echo "    ↳ Subcategoria já existe: $subName\n";
            }
        }
    }

    echo "\n📉 Configurando categorias de DESPESA:\n";
    // Inserir categorias de despesa
    foreach ($expenseCategories as $parentName => $subcategories) {
        // Verificar se categoria principal já existe
        $existing = DB::table('categories')
            ->where('name', $parentName)
            ->where('type', 'expense')
            ->whereNull('parent_id')
            ->first();
        
        if (!$existing) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $parentName,
                'type' => 'expense',
                'user_id' => null,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "  ✅ Categoria criada: $parentName\n";
        } else {
            $parentId = $existing->id;
            echo "  ℹ️ Categoria já existe: $parentName\n";
        }

        // Inserir subcategorias
        foreach ($subcategories as $subName) {
            $existingSub = DB::table('categories')
                ->where('name', $subName)
                ->where('parent_id', $parentId)
                ->first();
            
            if (!$existingSub) {
                DB::table('categories')->insert([
                    'name' => $subName,
                    'type' => 'expense',
                    'user_id' => null,
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                echo "    ↳ Subcategoria criada: $subName\n";
            } else {
                echo "    ↳ Subcategoria já existe: $subName\n";
            }
        }
    }
    
    echo "\n🎉 SCRIPT EXECUTADO COM SUCESSO!\n";
    echo "📋 Categorias e subcategorias configuradas para PF e PJ.\n";
    echo "\n🔗 <a href='/categories'>Acesse a tela de categorias</a>\n";
    
} catch(Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
