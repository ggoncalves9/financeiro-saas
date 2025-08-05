<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem planos
        if (Plan::count() > 0) {
            $this->command->info('Planos já existem. Pulando seeder.');
            return;
        }

        // Plano Gratuito
        Plan::create([
            'name' => 'Plano Gratuito',
            'description' => 'Ideal para começar com funcionalidades básicas',
            'price' => 0.00,
            'billing_cycle' => 'monthly',
            'max_transactions' => 50,
            'max_users' => 1,
            'features' => [
                'Até 50 transações por mês',
                '3 contas bancárias',
                'Relatórios básicos',
                'Suporte por email'
            ],
            'is_active' => true,
            'trial_days' => 0,
            'sort_order' => 1
        ]);

        // Plano Premium
        Plan::create([
            'name' => 'Plano Premium',
            'description' => 'Para usuários que precisam de mais recursos',
            'price' => 29.90,
            'billing_cycle' => 'monthly',
            'max_transactions' => null, // ilimitado
            'max_users' => 1,
            'features' => [
                'Transações ilimitadas',
                'Contas bancárias ilimitadas',
                'Todos os relatórios',
                'Importação de OFX',
                'Metas avançadas',
                'Suporte prioritário'
            ],
            'is_active' => true,
            'trial_days' => 7,
            'sort_order' => 2
        ]);

        // Plano Enterprise
        Plan::create([
            'name' => 'Plano Enterprise',
            'description' => 'Para empresas com necessidades avançadas',
            'price' => 99.90,
            'billing_cycle' => 'monthly',
            'max_transactions' => null, // ilimitado
            'max_users' => null, // ilimitado
            'features' => [
                'Tudo do Premium',
                'Gestão de equipe',
                'Relatórios empresariais',
                'API personalizada',
                'Suporte 24/7',
                'Onboarding personalizado'
            ],
            'is_active' => true,
            'trial_days' => 14,
            'sort_order' => 3
        ]);

        // Plano Anual Premium (com desconto)
        Plan::create([
            'name' => 'Premium Anual',
            'description' => 'Plano Premium com desconto anual',
            'price' => 299.00,
            'billing_cycle' => 'yearly',
            'max_transactions' => null, // ilimitado
            'max_users' => 1,
            'features' => [
                'Transações ilimitadas',
                'Contas bancárias ilimitadas',
                'Todos os relatórios',
                'Importação de OFX',
                'Metas avançadas',
                'Suporte prioritário',
                '2 meses grátis'
            ],
            'is_active' => true,
            'trial_days' => 7,
            'sort_order' => 4
        ]);
    }
}
