<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sistema Financeiro SaaS - Configurações
    |--------------------------------------------------------------------------
    |
    | Configurações específicas do sistema financeiro SaaS
    |
    */

    'app_name' => 'Financeiro SaaS',
    'app_version' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Planos e Preços
    |--------------------------------------------------------------------------
    */

    'plans' => [
        'free' => [
            'name' => 'Gratuito',
            'price' => 0,
            'stripe_price_id' => null,
            'features' => [
                'users' => 1,
                'transactions' => 100,
                'accounts' => 2,
                'goals' => 3,
                'reports' => false,
                'api_access' => false,
                'ai_features' => false,
                'priority_support' => false,
                'team_members' => 0,
                'export_data' => false,
                'bank_sync' => false,
            ],
            'target_audience' => 'pf',
        ],
        'pro_pf' => [
            'name' => 'Pro PF',
            'price' => 29.90,
            'stripe_price_id' => env('STRIPE_PRICE_PRO_PF'),
            'features' => [
                'users' => 1,
                'transactions' => 1000,
                'accounts' => 10,
                'goals' => 20,
                'reports' => true,
                'api_access' => false,
                'ai_features' => true,
                'priority_support' => false,
                'team_members' => 0,
                'export_data' => true,
                'bank_sync' => true,
            ],
            'target_audience' => 'pf',
        ],
        'empresarial' => [
            'name' => 'Empresarial',
            'price' => 99.90,
            'stripe_price_id' => env('STRIPE_PRICE_EMPRESARIAL'),
            'features' => [
                'users' => 10,
                'transactions' => 5000,
                'accounts' => 50,
                'goals' => 100,
                'reports' => true,
                'api_access' => true,
                'ai_features' => true,
                'priority_support' => false,
                'team_members' => 10,
                'export_data' => true,
                'bank_sync' => true,
            ],
            'target_audience' => 'pj',
        ],
        'premium_pj' => [
            'name' => 'Premium PJ',
            'price' => 299.90,
            'stripe_price_id' => env('STRIPE_PRICE_PREMIUM_PJ'),
            'features' => [
                'users' => -1, // unlimited
                'transactions' => -1,
                'accounts' => -1,
                'goals' => -1,
                'reports' => true,
                'api_access' => true,
                'ai_features' => true,
                'priority_support' => true,
                'team_members' => -1,
                'export_data' => true,
                'bank_sync' => true,
            ],
            'target_audience' => 'pj',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Categorias Padrão
    |--------------------------------------------------------------------------
    */

    'default_categories' => [
        'revenues' => [
            'Salário' => ['13º Salário', 'Férias', 'Hora Extra', 'Comissão'],
            'Freelance' => ['Consultoria', 'Projeto', 'Serviço Avulso'],
            'Investimentos' => ['Dividendos', 'Juros', 'Rendimento'],
            'Negócio' => ['Vendas', 'Serviços', 'Produtos'],
            'Outros' => ['Presente', 'Bonificação', 'Reembolso'],
        ],
        'expenses' => [
            'Moradia' => ['Aluguel', 'Financiamento', 'Condomínio', 'IPTU', 'Manutenção'],
            'Transporte' => ['Combustível', 'Manutenção Veículo', 'Transporte Público', 'Uber/Taxi'],
            'Alimentação' => ['Supermercado', 'Restaurante', 'Delivery', 'Lanche'],
            'Saúde' => ['Plano de Saúde', 'Médico', 'Farmácia', 'Exames'],
            'Educação' => ['Mensalidade', 'Livros', 'Cursos', 'Material Escolar'],
            'Lazer' => ['Cinema', 'Viagem', 'Academia', 'Hobbies'],
            'Serviços' => ['Internet', 'Telefone', 'Streaming', 'Banco'],
            'Vestuário' => ['Roupas', 'Calçados', 'Acessórios'],
            'Casa' => ['Móveis', 'Eletrodomésticos', 'Decoração', 'Limpeza'],
            'Impostos' => ['IR', 'IPVA', 'Taxas Governamentais'],
            'Outros' => ['Presente', 'Doação', 'Multa', 'Emergência'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Relatórios
    |--------------------------------------------------------------------------
    */

    'reports' => [
        'max_export_records' => 10000,
        'cache_duration' => 3600, // 1 hora
        'formats' => ['pdf', 'excel', 'csv'],
        'chart_colors' => [
            '#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1',
            '#fd7e14', '#20c997', '#6c757d', '#e83e8c', '#17a2b8'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Upload
    |--------------------------------------------------------------------------
    */

    'uploads' => [
        'max_file_size' => 5120, // 5MB em KB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf'],
        'path' => 'uploads',
        'receipts_path' => 'receipts',
        'avatars_path' => 'avatars',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Notificações
    |--------------------------------------------------------------------------
    */

    'notifications' => [
        'due_date_reminder_days' => 3,
        'trial_expiry_reminder_days' => 7,
        'goal_achievement_enabled' => true,
        'expense_limit_warning' => true,
        'monthly_summary' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de API
    |--------------------------------------------------------------------------
    */

    'api' => [
        'rate_limit' => 60, // requests per minute
        'version' => 'v1',
        'pagination_limit' => 50,
        'max_pagination_limit' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Segurança
    |--------------------------------------------------------------------------
    */

    'security' => [
        'session_timeout' => 120, // minutes
        'max_login_attempts' => 5,
        'lockout_duration' => 15, // minutes
        'password_min_length' => 8,
        'require_2fa_for_admin' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Backup
    |--------------------------------------------------------------------------
    */

    'backup' => [
        'enabled' => env('BACKUP_ENABLED', true),
        'schedule' => 'daily',
        'retention_days' => 30,
        'notification_email' => env('BACKUP_NOTIFICATION_EMAIL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Integrações Bancárias
    |--------------------------------------------------------------------------
    */

    'bank_integrations' => [
        'enabled' => env('BANK_SYNC_ENABLED', false),
        'providers' => [
            'open_banking' => [
                'enabled' => false,
                'api_key' => env('OPEN_BANKING_API_KEY'),
                'environment' => env('OPEN_BANKING_ENV', 'sandbox'),
            ],
            'pluggy' => [
                'enabled' => false,
                'client_id' => env('PLUGGY_CLIENT_ID'),
                'client_secret' => env('PLUGGY_CLIENT_SECRET'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features IA
    |--------------------------------------------------------------------------
    */

    'ai_features' => [
        'enabled' => env('AI_FEATURES_ENABLED', false),
        'provider' => env('AI_PROVIDER', 'openai'),
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => 'gpt-3.5-turbo',
        ],
        'features' => [
            'expense_categorization' => true,
            'financial_insights' => true,
            'budget_recommendations' => true,
            'investment_tips' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Cache
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'dashboard_ttl' => 300, // 5 minutes
        'reports_ttl' => 1800, // 30 minutes
        'user_preferences_ttl' => 3600, // 1 hour
        'system_stats_ttl' => 600, // 10 minutes
    ],

];
