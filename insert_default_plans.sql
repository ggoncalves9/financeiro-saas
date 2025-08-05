-- Criar tabela de planos se não existir
CREATE TABLE IF NOT EXISTS `plans` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` text,
    `price` decimal(10,2) NOT NULL,
    `billing_cycle` enum('monthly','quarterly','annual') NOT NULL DEFAULT 'monthly',
    `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
    `limits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`limits`)),
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `sort_order` int(11) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Adicionar coluna plan_id na tabela users se não existir
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `plan_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `id`,
ADD CONSTRAINT `users_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`);

-- Inserir planos padrão
INSERT INTO `plans` (`name`, `description`, `price`, `billing_cycle`, `features`, `limits`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Plano Básico', 'Ideal para uso pessoal', 29.90, 'monthly', 
 '["Controle de receitas e despesas", "Relatórios básicos", "Categorização automática", "Suporte por email"]',
 '{"max_transactions": 100, "max_categories": 10, "reports": "basic", "storage_mb": 100}',
 1, 1, NOW(), NOW()),

('Plano Profissional', 'Para pequenas empresas e freelancers', 59.90, 'monthly',
 '["Todas as funcionalidades do Básico", "Relatórios avançados", "Múltiplas contas", "Dashboard personalizado", "API de integração", "Suporte prioritário"]',
 '{"max_transactions": 1000, "max_categories": 50, "max_accounts": 5, "reports": "advanced", "storage_mb": 1000, "api_access": true}',
 1, 2, NOW(), NOW()),

('Plano Empresarial', 'Para empresas de médio e grande porte', 129.90, 'monthly',
 '["Todas as funcionalidades do Profissional", "Usuários ilimitados", "Relatórios customizados", "Integração EFI Pay", "Backup automático", "Suporte 24/7"]',
 '{"max_transactions": -1, "max_categories": -1, "max_accounts": -1, "max_users": -1, "reports": "custom", "storage_mb": -1, "api_access": true, "priority_support": true}',
 1, 3, NOW(), NOW());
