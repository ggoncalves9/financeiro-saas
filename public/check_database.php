<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Verificação do Sistema</h1>";

try {
    // Conectar ao banco de dados
    $dsn = "mysql:host=localhost;dbname=financeiro_saas;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "<h2>✅ Conexão com banco de dados OK</h2>";
    
    // Verificar estrutura da tabela categories
    $stmt = $pdo->query("DESCRIBE categories");
    $columns = $stmt->fetchAll();
    
    echo "<h3>Estrutura da tabela categories:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr><th>Coluna</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $hasParentId = false;
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
        
        if ($column['Field'] === 'parent_id') {
            $hasParentId = true;
        }
    }
    echo "</table>";
    
    if ($hasParentId) {
        echo "<h3>✅ Coluna parent_id encontrada!</h3>";
    } else {
        echo "<h3>❌ Coluna parent_id NÃO encontrada!</h3>";
        
        // Tentar adicionar a coluna
        echo "<h4>Tentando adicionar coluna parent_id...</h4>";
        try {
            $pdo->exec("ALTER TABLE categories ADD COLUMN parent_id BIGINT UNSIGNED NULL");
            $pdo->exec("ALTER TABLE categories ADD CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE");
            echo "<p>✅ Coluna parent_id adicionada com sucesso!</p>";
        } catch (Exception $e) {
            echo "<p>❌ Erro ao adicionar coluna: " . $e->getMessage() . "</p>";
        }
    }
    
    // Verificar categorias existentes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM categories");
    $totalCategories = $stmt->fetch()['total'];
    
    echo "<h3>Total de categorias: " . $totalCategories . "</h3>";
    
    if ($totalCategories == 0) {
        echo "<h4>Inserindo categorias padrão...</h4>";
        
        $categories = [
            ['name' => 'Salário', 'type' => 'revenue', 'parent_id' => null],
            ['name' => 'Freelance', 'type' => 'revenue', 'parent_id' => null],
            ['name' => 'Investimentos', 'type' => 'revenue', 'parent_id' => null],
            ['name' => 'Alimentação', 'type' => 'expense', 'parent_id' => null],
            ['name' => 'Transporte', 'type' => 'expense', 'parent_id' => null],
            ['name' => 'Moradia', 'type' => 'expense', 'parent_id' => null],
        ];
        
        foreach ($categories as $category) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, type, parent_id, user_id, created_at, updated_at) VALUES (?, ?, ?, NULL, NOW(), NOW())");
            $stmt->execute([$category['name'], $category['type'], $category['parent_id']]);
        }
        
        echo "<p>✅ Categorias padrão inseridas!</p>";
    }
    
    // Mostrar todas as categorias
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY type, name");
    $categories = $stmt->fetchAll();
    
    echo "<h3>Categorias cadastradas:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Tipo</th><th>Parent ID</th><th>User ID</th></tr>";
    
    foreach ($categories as $category) {
        echo "<tr>";
        echo "<td>" . $category['id'] . "</td>";
        echo "<td>" . $category['name'] . "</td>";
        echo "<td>" . $category['type'] . "</td>";
        echo "<td>" . ($category['parent_id'] ?? 'NULL') . "</td>";
        echo "<td>" . ($category['user_id'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erro: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='/categories'>Ir para página de categorias</a></p>";
echo "<p><a href='/dashboard'>Ir para dashboard</a></p>";
echo "<p><a href='/expenses'>Ir para despesas</a></p>";
echo "<p><a href='/revenues'>Ir para receitas</a></p>";
?>
