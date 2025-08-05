<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Goal;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário de teste PF
        $userPf = User::where('email', 'joao@teste.com')->first();
        if (!$userPf) {
            $userPf = User::create([
                'name' => 'João Silva',
                'email' => 'joao@teste.com',
                'password' => Hash::make('123456'),
                'type' => 'pf',
                'email_verified_at' => now(),
            ]);
        }

        // Criar usuário de teste PJ
        $userPj = User::where('email', 'empresa@teste.com')->first();
        if (!$userPj) {
            $userPj = User::create([
                'name' => 'Empresa Teste LTDA',
                'email' => 'empresa@teste.com',
                'password' => Hash::make('123456'),
                'type' => 'pj',
                'email_verified_at' => now(),
            ]);
        }

        // Atribuir role user se existir
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            if (!$userPf->hasRole('user')) {
                $userPf->assignRole($userRole);
            }
            if (!$userPj->hasRole('user')) {
                $userPj->assignRole($userRole);
            }
        }

        // Criar categorias de exemplo
        $categories = [
            ['name' => 'Salário', 'type' => 'revenue', 'user_id' => $userPf->id],
            ['name' => 'Freelance', 'type' => 'revenue', 'user_id' => $userPf->id],
            ['name' => 'Moradia', 'type' => 'expense', 'user_id' => $userPf->id],
            ['name' => 'Alimentação', 'type' => 'expense', 'user_id' => $userPf->id],
            ['name' => 'Transporte', 'type' => 'expense', 'user_id' => $userPf->id],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name'], 'user_id' => $category['user_id']],
                $category
            );
        }

        // Criar contas de exemplo
        $accounts = [
            ['name' => 'Conta Corrente', 'bank_name' => 'Banco do Brasil', 'balance' => 5000.00, 'user_id' => $userPf->id],
            ['name' => 'Poupança', 'bank_name' => 'Caixa Econômica', 'balance' => 10000.00, 'user_id' => $userPf->id],
        ];

        foreach ($accounts as $account) {
            Account::firstOrCreate(
                ['name' => $account['name'], 'user_id' => $account['user_id']],
                $account
            );
        }

        // Criar receitas de exemplo
        $revenues = [
            [
                'title' => 'Salário Janeiro',
                'description' => 'Salário mensal',
                'amount' => 5000.00,
                'date' => now()->subDays(15)->format('Y-m-d'),
                'category' => 'Salário',
                'status' => 'confirmed',
                'user_id' => $userPf->id,
            ],
            [
                'title' => 'Freelance Web',
                'description' => 'Projeto de website',
                'amount' => 2500.00,
                'date' => now()->subDays(5)->format('Y-m-d'),
                'category' => 'Freelance',
                'status' => 'confirmed',
                'user_id' => $userPf->id,
            ],
            [
                'title' => 'Vendas Produtos',
                'description' => 'Vendas do mês',
                'amount' => 15000.00,
                'date' => now()->subDays(10)->format('Y-m-d'),
                'category' => 'Vendas',
                'status' => 'confirmed',
                'user_id' => $userPj->id,
            ],
        ];

        foreach ($revenues as $revenue) {
            Revenue::firstOrCreate(
                ['title' => $revenue['title'], 'user_id' => $revenue['user_id']],
                $revenue
            );
        }

        // Criar despesas de exemplo
        $expenses = [
            [
                'title' => 'Aluguel Janeiro',
                'description' => 'Aluguel da casa',
                'amount' => 1500.00,
                'date' => now()->subDays(20)->format('Y-m-d'),
                'category' => 'Moradia',
                'status' => 'paid',
                'user_id' => $userPf->id,
            ],
            [
                'title' => 'Supermercado',
                'description' => 'Compras do mês',
                'amount' => 800.00,
                'date' => now()->subDays(8)->format('Y-m-d'),
                'category' => 'Alimentação',
                'status' => 'paid',
                'user_id' => $userPf->id,
            ],
            [
                'title' => 'Gasolina',
                'description' => 'Combustível do carro',
                'amount' => 300.00,
                'date' => now()->subDays(3)->format('Y-m-d'),
                'category' => 'Transporte',
                'status' => 'paid',
                'user_id' => $userPf->id,
            ],
            [
                'title' => 'Aluguel Escritório',
                'description' => 'Aluguel do escritório',
                'amount' => 3000.00,
                'date' => now()->subDays(15)->format('Y-m-d'),
                'category' => 'Infraestrutura',
                'status' => 'paid',
                'user_id' => $userPj->id,
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::firstOrCreate(
                ['title' => $expense['title'], 'user_id' => $expense['user_id']],
                $expense
            );
        }

        // Criar metas de exemplo
        $goals = [
            [
                'title' => 'Reserva de Emergência',
                'description' => 'Meta para reserva de emergência de 6 meses',
                'target_amount' => 30000.00,
                'current_amount' => 8500.00,
                'target_date' => now()->addYear()->format('Y-m-d'),
                'category' => 'Reserva',
                'type' => 'saving',
                'status' => 'active',
                'user_id' => $userPf->id,
            ],
            [
                'title' => 'Viagem Europa',
                'description' => 'Viagem de férias para Europa',
                'target_amount' => 15000.00,
                'current_amount' => 3500.00,
                'target_date' => now()->addMonths(8)->format('Y-m-d'),
                'category' => 'Lazer',
                'type' => 'saving',
                'status' => 'active',
                'user_id' => $userPf->id,
            ],
            [
                'title' => 'Expansão Empresa',
                'description' => 'Capital para expansão dos negócios',
                'target_amount' => 100000.00,
                'current_amount' => 25000.00,
                'target_date' => now()->addMonths(18)->format('Y-m-d'),
                'category' => 'Investimento',
                'type' => 'investment',
                'status' => 'active',
                'user_id' => $userPj->id,
            ],
        ];

        foreach ($goals as $goal) {
            Goal::firstOrCreate(
                ['title' => $goal['title'], 'user_id' => $goal['user_id']],
                $goal
            );
        }

        $this->command->info('Dados de teste criados com sucesso!');
        $this->command->info('Usuários criados:');
        $this->command->info('- PF: joao@teste.com / 123456');
        $this->command->info('- PJ: empresa@teste.com / 123456');
        $this->command->info('- Admin: admin@financeirosass.com / admin123');
    }
}
