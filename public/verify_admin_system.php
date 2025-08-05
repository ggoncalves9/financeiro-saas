&lt;?php
/**
 * Verificação completa do sistema de admin e planos
 */

// Configurações do banco de dados (ajuste conforme necessário)
$host = 'localhost';
$dbname = 'financeiro_saas';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::EXCEPTION);
    
    echo "&lt;h1&gt;Verificação do Sistema Admin&lt;/h1&gt;";
    echo "&lt;style&gt;
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .section { margin: 30px 0; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
    &lt;/style&gt;";
    
    // Verificar se a tabela plans existe
    echo "&lt;div class='section'&gt;";
    echo "&lt;h2&gt;1. Verificação da Tabela Plans&lt;/h2&gt;";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'plans'");
    if ($stmt->rowCount() > 0) {
        echo "&lt;p class='success'&gt;✓ Tabela 'plans' existe&lt;/p&gt;";
        
        // Verificar estrutura da tabela
        $stmt = $pdo->query("DESCRIBE plans");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "&lt;h3&gt;Estrutura da tabela plans:&lt;/h3&gt;";
        echo "&lt;table&gt;&lt;tr&gt;&lt;th&gt;Campo&lt;/th&gt;&lt;th&gt;Tipo&lt;/th&gt;&lt;th&gt;Null&lt;/th&gt;&lt;th&gt;Key&lt;/th&gt;&lt;th&gt;Default&lt;/th&gt;&lt;/tr&gt;";
        foreach ($columns as $column) {
            echo "&lt;tr&gt;&lt;td&gt;{$column['Field']}&lt;/td&gt;&lt;td&gt;{$column['Type']}&lt;/td&gt;&lt;td&gt;{$column['Null']}&lt;/td&gt;&lt;td&gt;{$column['Key']}&lt;/td&gt;&lt;td&gt;{$column['Default']}&lt;/td&gt;&lt;/tr&gt;";
        }
        echo "&lt;/table&gt;";
        
        // Verificar dados na tabela
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM plans");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($count['total'] > 0) {
            echo "&lt;p class='success'&gt;✓ Tabela plans contém {$count['total']} registro(s)&lt;/p&gt;";
            
            // Mostrar planos existentes
            $stmt = $pdo->query("SELECT * FROM plans ORDER BY sort_order");
            $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "&lt;h3&gt;Planos cadastrados:&lt;/h3&gt;";
            echo "&lt;table&gt;&lt;tr&gt;&lt;th&gt;ID&lt;/th&gt;&lt;th&gt;Nome&lt;/th&gt;&lt;th&gt;Preço&lt;/th&gt;&lt;th&gt;Ciclo&lt;/th&gt;&lt;th&gt;Ativo&lt;/th&gt;&lt;th&gt;Ordem&lt;/th&gt;&lt;/tr&gt;";
            foreach ($plans as $plan) {
                $active = $plan['is_active'] ? 'Sim' : 'Não';
                echo "&lt;tr&gt;&lt;td&gt;{$plan['id']}&lt;/td&gt;&lt;td&gt;{$plan['name']}&lt;/td&gt;&lt;td&gt;R$ {$plan['price']}&lt;/td&gt;&lt;td&gt;{$plan['billing_cycle']}&lt;/td&gt;&lt;td&gt;{$active}&lt;/td&gt;&lt;td&gt;{$plan['sort_order']}&lt;/td&gt;&lt;/tr&gt;";
            }
            echo "&lt;/table&gt;";
        } else {
            echo "&lt;p class='warning'&gt;⚠ Tabela plans existe mas está vazia&lt;/p&gt;";
        }
    } else {
        echo "&lt;p class='error'&gt;✗ Tabela 'plans' não existe&lt;/p&gt;";
    }
    echo "&lt;/div&gt;";
    
    // Verificar coluna plan_id na tabela users
    echo "&lt;div class='section'&gt;";
    echo "&lt;h2&gt;2. Verificação da Coluna plan_id em Users&lt;/h2&gt;";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'plan_id'");
    if ($stmt->rowCount() > 0) {
        echo "&lt;p class='success'&gt;✓ Coluna 'plan_id' existe na tabela users&lt;/p&gt;";
        
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "&lt;p&gt;Tipo: {$column['Type']}, Null: {$column['Null']}, Default: {$column['Default']}&lt;/p&gt;";
    } else {
        echo "&lt;p class='error'&gt;✗ Coluna 'plan_id' não existe na tabela users&lt;/p&gt;";
    }
    echo "&lt;/div&gt;";
    
    // Verificar arquivos do sistema admin
    echo "&lt;div class='section'&gt;";
    echo "&lt;h2&gt;3. Verificação dos Arquivos do Sistema&lt;/h2&gt;";
    
    $files_to_check = [
        'app/Http/Controllers/Admin/PlanController.php' => 'Controller de Planos',
        'app/Http/Controllers/Admin/PaymentSettingsController.php' => 'Controller de Configurações de Pagamento',
        'app/Models/Plan.php' => 'Model de Plano',
        'resources/views/admin/plans/index.blade.php' => 'View de listagem de planos',
        'resources/views/admin/plans/create.blade.php' => 'View de criação de planos',
        'resources/views/admin/plans/edit.blade.php' => 'View de edição de planos',
        'resources/views/admin/payment-settings/index.blade.php' => 'View de configurações de pagamento',
        'config/payment.php' => 'Arquivo de configuração de pagamento',
        'database/migrations/2024_01_01_000001_create_plans_table.php' => 'Migration da tabela plans',
        'database/migrations/2024_01_01_000002_add_plan_id_to_users_table.php' => 'Migration para adicionar plan_id'
    ];
    
    foreach ($files_to_check as $file => $description) {
        if (file_exists($file)) {
            echo "&lt;p class='success'&gt;✓ {$description}: {$file}&lt;/p&gt;";
        } else {
            echo "&lt;p class='error'&gt;✗ {$description}: {$file} - ARQUIVO NÃO ENCONTRADO&lt;/p&gt;";
        }
    }
    echo "&lt;/div&gt;";
    
    // Verificar rotas
    echo "&lt;div class='section'&gt;";
    echo "&lt;h2&gt;4. Rotas do Sistema Admin&lt;/h2&gt;";
    echo "&lt;p&gt;As seguintes rotas devem estar configuradas em routes/admin.php:&lt;/p&gt;";
    echo "&lt;ul&gt;";
    echo "&lt;li&gt;/admin/plans (GET, POST)&lt;/li&gt;";
    echo "&lt;li&gt;/admin/plans/create (GET)&lt;/li&gt;";
    echo "&lt;li&gt;/admin/plans/{id}/edit (GET)&lt;/li&gt;";
    echo "&lt;li&gt;/admin/plans/{id} (PUT, DELETE)&lt;/li&gt;";
    echo "&lt;li&gt;/admin/payment-settings (GET, POST)&lt;/li&gt;";
    echo "&lt;/ul&gt;";
    
    if (file_exists('routes/admin.php')) {
        echo "&lt;p class='success'&gt;✓ Arquivo routes/admin.php existe&lt;/p&gt;";
        $routes_content = file_get_contents('routes/admin.php');
        if (strpos($routes_content, 'plans') !== false) {
            echo "&lt;p class='success'&gt;✓ Rotas de planos encontradas&lt;/p&gt;";
        } else {
            echo "&lt;p class='warning'&gt;⚠ Rotas de planos não encontradas no arquivo&lt;/p&gt;";
        }
    } else {
        echo "&lt;p class='error'&gt;✗ Arquivo routes/admin.php não encontrado&lt;/p&gt;";
    }
    echo "&lt;/div&gt;";
    
    // Verificar configurações do .env
    echo "&lt;div class='section'&gt;";
    echo "&lt;h2&gt;5. Configurações do Sistema&lt;/h2&gt;";
    
    if (file_exists('.env')) {
        echo "&lt;p class='success'&gt;✓ Arquivo .env existe&lt;/p&gt;";
        
        $env_content = file_get_contents('.env');
        $required_configs = [
            'DB_CONNECTION' => 'Conexão do banco',
            'DB_HOST' => 'Host do banco',
            'DB_DATABASE' => 'Nome do banco',
            'APP_URL' => 'URL da aplicação'
        ];
        
        foreach ($required_configs as $config => $description) {
            if (strpos($env_content, $config) !== false) {
                echo "&lt;p class='success'&gt;✓ {$description} configurado&lt;/p&gt;";
            } else {
                echo "&lt;p class='warning'&gt;⚠ {$description} não encontrado&lt;/p&gt;";
            }
        }
    } else {
        echo "&lt;p class='error'&gt;✗ Arquivo .env não encontrado&lt;/p&gt;";
    }
    echo "&lt;/div&gt;";
    
    echo "&lt;div class='section'&gt;";
    echo "&lt;h2&gt;6. Próximos Passos&lt;/h2&gt;";
    echo "&lt;ol&gt;";
    echo "&lt;li&gt;Se a tabela plans não existir, execute: &lt;code&gt;php artisan migrate&lt;/code&gt;&lt;/li&gt;";
    echo "&lt;li&gt;Se a tabela estiver vazia, execute o SQL em insert_default_plans.sql&lt;/li&gt;";
    echo "&lt;li&gt;Acesse &lt;a href='/admin/plans' target='_blank'&gt;/admin/plans&lt;/a&gt; para gerenciar planos&lt;/li&gt;";
    echo "&lt;li&gt;Acesse &lt;a href='/admin/payment-settings' target='_blank'&gt;/admin/payment-settings&lt;/a&gt; para configurar pagamentos&lt;/li&gt;";
    echo "&lt;li&gt;Configure as credenciais da EFI Pay nas configurações de pagamento&lt;/li&gt;";
    echo "&lt;/ol&gt;";
    echo "&lt;/div&gt;";
    
} catch (PDOException $e) {
    echo "&lt;p class='error'&gt;Erro de conexão com o banco: " . $e->getMessage() . "&lt;/p&gt;";
    echo "&lt;p&gt;Verifique se:&lt;/p&gt;";
    echo "&lt;ul&gt;";
    echo "&lt;li&gt;O MySQL está rodando&lt;/li&gt;";
    echo "&lt;li&gt;O banco 'financeiro_saas' existe&lt;/li&gt;";
    echo "&lt;li&gt;As credenciais estão corretas&lt;/li&gt;";
    echo "&lt;/ul&gt;";
}
?&gt;
