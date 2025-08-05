&lt;?php
/**
 * Script para criar tabela de planos e inserir dados padrão
 */

// Configurações do banco
$host = 'localhost';
$dbname = 'financeiro_saas';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::EXCEPTION);
    
    echo "&lt;h1&gt;Configuração do Sistema de Planos&lt;/h1&gt;";
    echo "&lt;style&gt;body{font-family:Arial,sans-serif;margin:20px;}.success{color:green;font-weight:bold;}.error{color:red;font-weight:bold;}&lt;/style&gt;";
    
    // Criar tabela plans
    echo "&lt;h2&gt;1. Criando tabela plans...&lt;/h2&gt;";
    
    $createPlansTable = "
    CREATE TABLE IF NOT EXISTS `plans` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text,
        `price` decimal(10,2) NOT NULL,
        `billing_cycle` enum('monthly','quarterly','annual') NOT NULL DEFAULT 'monthly',
        `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
        `limits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `sort_order` int(11) DEFAULT 0,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createPlansTable);
    echo "&lt;p class='success'&gt;✓ Tabela plans criada com sucesso!&lt;/p&gt;";
    
    // Adicionar coluna plan_id na tabela users
    echo "&lt;h2&gt;2. Adicionando coluna plan_id na tabela users...&lt;/h2&gt;";
    
    try {
        $addPlanIdColumn = "ALTER TABLE `users` ADD COLUMN `plan_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `id`";
        $pdo->exec($addPlanIdColumn);
        echo "&lt;p class='success'&gt;✓ Coluna plan_id adicionada com sucesso!&lt;/p&gt;";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "&lt;p class='success'&gt;✓ Coluna plan_id já existe!&lt;/p&gt;";
        } else {
            throw $e;
        }
    }
    
    // Verificar se já existem planos
    echo "&lt;h2&gt;3. Inserindo planos padrão...&lt;/h2&gt;";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM plans");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($count['total'] == 0) {
        // Inserir planos padrão
        $plans = [
            [
                'name' => 'Plano Básico',
                'description' => 'Ideal para uso pessoal',
                'price' => 29.90,
                'billing_cycle' => 'monthly',
                'features' => json_encode([
                    'Controle de receitas e despesas',
                    'Relatórios básicos',
                    'Categorização automática',
                    'Suporte por email'
                ]),
                'limits' => json_encode([
                    'max_transactions' => 100,
                    'max_categories' => 10,
                    'reports' => 'basic',
                    'storage_mb' => 100
                ]),
                'sort_order' => 1
            ],
            [
                'name' => 'Plano Profissional',
                'description' => 'Para pequenas empresas e freelancers',
                'price' => 59.90,
                'billing_cycle' => 'monthly',
                'features' => json_encode([
                    'Todas as funcionalidades do Básico',
                    'Relatórios avançados',
                    'Múltiplas contas',
                    'Dashboard personalizado',
                    'API de integração',
                    'Suporte prioritário'
                ]),
                'limits' => json_encode([
                    'max_transactions' => 1000,
                    'max_categories' => 50,
                    'max_accounts' => 5,
                    'reports' => 'advanced',
                    'storage_mb' => 1000,
                    'api_access' => true
                ]),
                'sort_order' => 2
            ],
            [
                'name' => 'Plano Empresarial',
                'description' => 'Para empresas de médio e grande porte',
                'price' => 129.90,
                'billing_cycle' => 'monthly',
                'features' => json_encode([
                    'Todas as funcionalidades do Profissional',
                    'Usuários ilimitados',
                    'Relatórios customizados',
                    'Integração EFI Pay',
                    'Backup automático',
                    'Suporte 24/7'
                ]),
                'limits' => json_encode([
                    'max_transactions' => -1,
                    'max_categories' => -1,
                    'max_accounts' => -1,
                    'max_users' => -1,
                    'reports' => 'custom',
                    'storage_mb' => -1,
                    'api_access' => true,
                    'priority_support' => true
                ]),
                'sort_order' => 3
            ]
        ];
        
        $insertPlan = $pdo->prepare("
            INSERT INTO plans (name, description, price, billing_cycle, features, limits, is_active, sort_order, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, 1, ?, NOW(), NOW())
        ");
        
        foreach ($plans as $plan) {
            $insertPlan->execute([
                $plan['name'],
                $plan['description'],
                $plan['price'],
                $plan['billing_cycle'],
                $plan['features'],
                $plan['limits'],
                $plan['sort_order']
            ]);
        }
        
        echo "&lt;p class='success'&gt;✓ " . count($plans) . " planos inseridos com sucesso!&lt;/p&gt;";
    } else {
        echo "&lt;p class='success'&gt;✓ Já existem {$count['total']} planos cadastrados!&lt;/p&gt;";
    }
    
    // Mostrar planos criados
    echo "&lt;h2&gt;4. Planos disponíveis:&lt;/h2&gt;";
    
    $stmt = $pdo->query("SELECT * FROM plans ORDER BY sort_order");
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "&lt;table border='1' cellpadding='10' style='border-collapse:collapse;width:100%;'&gt;";
    echo "&lt;tr style='background-color:#f2f2f2;'&gt;&lt;th&gt;ID&lt;/th&gt;&lt;th&gt;Nome&lt;/th&gt;&lt;th&gt;Preço&lt;/th&gt;&lt;th&gt;Ciclo&lt;/th&gt;&lt;th&gt;Descrição&lt;/th&gt;&lt;/tr&gt;";
    
    foreach ($plans as $plan) {
        echo "&lt;tr&gt;";
        echo "&lt;td&gt;{$plan['id']}&lt;/td&gt;";
        echo "&lt;td&gt;{$plan['name']}&lt;/td&gt;";
        echo "&lt;td&gt;R$ " . number_format($plan['price'], 2, ',', '.') . "&lt;/td&gt;";
        echo "&lt;td&gt;{$plan['billing_cycle']}&lt;/td&gt;";
        echo "&lt;td&gt;{$plan['description']}&lt;/td&gt;";
        echo "&lt;/tr&gt;";
    }
    
    echo "&lt;/table&gt;";
    
    echo "&lt;h2&gt;✅ Sistema configurado com sucesso!&lt;/h2&gt;";
    echo "&lt;p&gt;&lt;strong&gt;Próximos passos:&lt;/strong&gt;&lt;/p&gt;";
    echo "&lt;ul&gt;";
    echo "&lt;li&gt;&lt;a href='/admin/plans' target='_blank'&gt;Acessar gerenciamento de planos&lt;/a&gt;&lt;/li&gt;";
    echo "&lt;li&gt;&lt;a href='/admin/payment-settings' target='_blank'&gt;Configurar sistema de pagamento&lt;/a&gt;&lt;/li&gt;";
    echo "&lt;li&gt;&lt;a href='/admin' target='_blank'&gt;Ir para o painel admin&lt;/a&gt;&lt;/li&gt;";
    echo "&lt;/ul&gt;";
    
} catch (PDOException $e) {
    echo "&lt;p class='error'&gt;Erro: " . $e->getMessage() . "&lt;/p&gt;";
}
?&gt;
