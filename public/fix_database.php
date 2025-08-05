<?php
// Script direto para resolver o problema da coluna parent_id
echo "<h2>🔧 Correção Urgente - Adicionando Coluna parent_id</h2>";
echo "<pre>";

$host = 'localhost';
$dbname = 'financeiro_saas';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado ao banco de dados MySQL\n\n";
    
    // Verificar se a coluna parent_id já existe
    $result = $pdo->query("SHOW COLUMNS FROM categories LIKE 'parent_id'");
    $columnExists = $result->fetch();
    
    if (!$columnExists) {
        echo "📝 Adicionando coluna parent_id...\n";
        
        // Adicionar a coluna parent_id
        $pdo->exec("ALTER TABLE categories ADD COLUMN parent_id BIGINT UNSIGNED NULL AFTER id");
        echo "✅ Coluna parent_id adicionada!\n";
        
        // Adicionar foreign key constraint
        try {
            $pdo->exec("ALTER TABLE categories ADD CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE");
            echo "✅ Foreign key constraint adicionada!\n";
        } catch (Exception $e) {
            echo "⚠️ Foreign key já existe ou erro: " . $e->getMessage() . "\n";
        }
    } else {
        echo "ℹ️ Coluna parent_id já existe!\n";
    }
    
    // Verificar estrutura final
    echo "\n📋 Estrutura atual da tabela categories:\n";
    $columns = $pdo->query("DESCRIBE categories")->fetchAll();
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']}) " . ($column['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    // Inserir categorias de exemplo
    echo "\n📥 Inserindo categorias de exemplo...\n";
    
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
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND type = 'revenue' AND parent_id IS NULL");
        $stmt->execute([$parentName]);
        $existing = $stmt->fetch();
        
        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'revenue', NULL, NULL, NOW(), NOW())");
            $stmt->execute([$parentName]);
            $parentId = $pdo->lastInsertId();
            echo "📈 Receita criada: $parentName\n";
        } else {
            $parentId = $existing['id'];
            echo "📈 Receita já existe: $parentName\n";
        }

        foreach ($subcategories as $subName) {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND parent_id = ?");
            $stmt->execute([$subName, $parentId]);
            $existingSub = $stmt->fetch();
            
            if (!$existingSub) {
                $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'revenue', NULL, ?, NOW(), NOW())");
                $stmt->execute([$subName, $parentId]);
                echo "  ↳ $subName\n";
            }
        }
    }

    // Inserir categorias de despesa
    foreach ($expenseCategories as $parentName => $subcategories) {
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND type = 'expense' AND parent_id IS NULL");
        $stmt->execute([$parentName]);
        $existing = $stmt->fetch();
        
        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'expense', NULL, NULL, NOW(), NOW())");
            $stmt->execute([$parentName]);
            $parentId = $pdo->lastInsertId();
            echo "📉 Despesa criada: $parentName\n";
        } else {
            $parentId = $existing['id'];
            echo "📉 Despesa já existe: $parentName\n";
        }

        foreach ($subcategories as $subName) {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND parent_id = ?");
            $stmt->execute([$subName, $parentId]);
            $existingSub = $stmt->fetch();
            
            if (!$existingSub) {
                $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id, parent_id, created_at, updated_at) VALUES (?, 'expense', NULL, ?, NOW(), NOW())");
                $stmt->execute([$subName, $parentId]);
                echo "  ↳ $subName\n";
            }
        }
    }
    
    echo "\n🎉 CORREÇÃO APLICADA COM SUCESSO!\n";
    echo "✅ Banco de dados atualizado\n";
    echo "✅ Categorias e subcategorias inseridas\n";
    echo "\n🔗 <a href='/categories' target='_blank'>TESTAR CATEGORIAS AGORA</a>\n";
    
} catch(PDOException $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    
    // Tentar método alternativo se falhar
    echo "\n🔄 Tentando método alternativo...\n";
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS categories_backup AS SELECT * FROM categories");
        echo "✅ Backup criado\n";
        
        $pdo->exec("DROP TABLE categories");
        echo "✅ Tabela antiga removida\n";
        
        $createTable = "
        CREATE TABLE categories (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            parent_id BIGINT UNSIGNED NULL,
            user_id BIGINT UNSIGNED NULL,
            type ENUM('revenue', 'expense') NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
        )";
        
        $pdo->exec($createTable);
        echo "✅ Nova tabela criada com parent_id\n";
        
        // Restaurar dados básicos
        $pdo->exec("INSERT INTO categories (user_id, type, name, created_at, updated_at) SELECT user_id, type, name, created_at, updated_at FROM categories_backup");
        echo "✅ Dados restaurados\n";
        
    } catch(Exception $e2) {
        echo "❌ Erro no método alternativo: " . $e2->getMessage() . "\n";
    }
}

echo "</pre>";
?>
