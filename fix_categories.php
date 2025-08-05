<?php
// Script simples para adicionar a coluna parent_id
$host = 'localhost';
$dbname = 'financeiro_saas';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado ao banco de dados.\n";
    
    // Verificar se a coluna parent_id já existe
    $checkColumn = $pdo->query("SHOW COLUMNS FROM categories LIKE 'parent_id'")->fetch();
    
    if (!$checkColumn) {
        // Adicionar a coluna parent_id
        $pdo->exec("ALTER TABLE categories ADD COLUMN parent_id BIGINT UNSIGNED NULL AFTER id");
        echo "Coluna parent_id adicionada com sucesso!\n";
        
        // Adicionar foreign key
        $pdo->exec("ALTER TABLE categories ADD CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE");
        echo "Foreign key adicionada com sucesso!\n";
    } else {
        echo "Coluna parent_id já existe.\n";
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

    // Inserir categorias de receita
    foreach ($revenueCategories as $parentName => $subcategories) {
        // Verificar se categoria principal já existe
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND type = 'revenue' AND parent_id IS NULL");
        $stmt->execute([$parentName]);
        $existing = $stmt->fetch();
        
        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'revenue', NULL, NULL, NOW(), NOW())");
            $stmt->execute([$parentName]);
            $parentId = $pdo->lastInsertId();
            echo "Categoria de receita criada: $parentName\n";
        } else {
            $parentId = $existing['id'];
            echo "Categoria de receita já existe: $parentName\n";
        }

        // Inserir subcategorias
        foreach ($subcategories as $subName) {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND parent_id = ?");
            $stmt->execute([$subName, $parentId]);
            $existingSub = $stmt->fetch();
            
            if (!$existingSub) {
                $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'revenue', NULL, ?, NOW(), NOW())");
                $stmt->execute([$subName, $parentId]);
                echo "Subcategoria de receita criada: $subName\n";
            } else {
                echo "Subcategoria de receita já existe: $subName\n";
            }
        }
    }

    // Inserir categorias de despesa
    foreach ($expenseCategories as $parentName => $subcategories) {
        // Verificar se categoria principal já existe
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND type = 'expense' AND parent_id IS NULL");
        $stmt->execute([$parentName]);
        $existing = $stmt->fetch();
        
        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'expense', NULL, NULL, NOW(), NOW())");
            $stmt->execute([$parentName]);
            $parentId = $pdo->lastInsertId();
            echo "Categoria de despesa criada: $parentName\n";
        } else {
            $parentId = $existing['id'];
            echo "Categoria de despesa já existe: $parentName\n";
        }

        // Inserir subcategorias
        foreach ($subcategories as $subName) {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND parent_id = ?");
            $stmt->execute([$subName, $parentId]);
            $existingSub = $stmt->fetch();
            
            if (!$existingSub) {
                $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'expense', NULL, ?, NOW(), NOW())");
                $stmt->execute([$subName, $parentId]);
                echo "Subcategoria de despesa criada: $subName\n";
            } else {
                echo "Subcategoria de despesa já existe: $subName\n";
            }
        }
    }
    
    echo "\nScript executado com sucesso!\n";
    echo "Categorias e subcategorias configuradas.\n";
    
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
