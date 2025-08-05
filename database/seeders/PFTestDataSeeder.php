<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Goal;
use Carbon\Carbon;

class PFTestDataSeeder extends Seeder
{
    public function run()
    {
        // Buscar usuário PF
        $user = User::where('email', 'pf@teste.com')->first();
        
        if (!$user) {
            $this->command->error('Usuário PF não encontrado!');
            return;
        }

        // Criar contas
        $contaCorrente = Account::create([
            'user_id' => $user->id,
            'name' => 'Conta Corrente',
            'type' => 'checking',
            'balance' => 2500.00,
            'bank_name' => 'Banco do Brasil',
            'agency' => '1234',
            'account_number' => '56789-0',
            'is_active' => true,
        ]);

        $poupanca = Account::create([
            'user_id' => $user->id,
            'name' => 'Poupança',
            'type' => 'savings',
            'balance' => 5000.00,
            'bank_name' => 'Caixa Econômica',
            'agency' => '0987',
            'account_number' => '12345-6',
            'is_active' => true,
        ]);

        // Criar receitas
        Revenue::create([
            'user_id' => $user->id,
            'account_id' => $contaCorrente->id,
            'title' => 'Salário',
            'description' => 'Salário mensal',
            'amount' => 3500.00,
            'category' => 'salary',
            'date' => Carbon::now()->startOfMonth(),
            'status' => 'confirmed',
            'recurring' => true,
            'recurring_type' => 'monthly',
        ]);

        Revenue::create([
            'user_id' => $user->id,
            'account_id' => $contaCorrente->id,
            'title' => 'Freelance',
            'description' => 'Projeto de desenvolvimento',
            'amount' => 1200.00,
            'category' => 'freelance',
            'date' => Carbon::now()->subDays(5),
            'status' => 'confirmed',
        ]);

        Revenue::create([
            'user_id' => $user->id,
            'account_id' => $poupanca->id,
            'title' => 'Rendimento Poupança',
            'description' => 'Rendimento mensal da poupança',
            'amount' => 25.50,
            'category' => 'investment',
            'date' => Carbon::now()->endOfMonth(),
            'status' => 'pending',
        ]);

        // Criar despesas
        Expense::create([
            'user_id' => $user->id,
            'account_id' => $contaCorrente->id,
            'title' => 'Aluguel',
            'description' => 'Aluguel do apartamento',
            'amount' => 1200.00,
            'category' => 'housing',
            'date' => Carbon::now()->startOfMonth(),
            'due_date' => Carbon::now()->startOfMonth()->addDays(5),
            'status' => 'paid',
            'recurring' => true,
            'recurring_type' => 'monthly',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'account_id' => $contaCorrente->id,
            'title' => 'Conta de Luz',
            'description' => 'Energia elétrica',
            'amount' => 180.00,
            'category' => 'utilities',
            'date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(10),
            'status' => 'pending',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'account_id' => $contaCorrente->id,
            'title' => 'Mercado',
            'description' => 'Compras do mês',
            'amount' => 450.00,
            'category' => 'food',
            'date' => Carbon::now()->subDays(2),
            'status' => 'paid',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'account_id' => $contaCorrente->id,
            'title' => 'Academia',
            'description' => 'Mensalidade da academia',
            'amount' => 80.00,
            'category' => 'health',
            'date' => Carbon::now()->subDays(15),
            'due_date' => Carbon::now()->subDays(10),
            'status' => 'overdue',
        ]);

        // Criar metas
        Goal::create([
            'user_id' => $user->id,
            'title' => 'Viagem para Europa',
            'description' => 'Economizar para uma viagem de 15 dias',
            'target_amount' => 15000.00,
            'current_amount' => 2500.00,
            'target_date' => Carbon::now()->addMonths(18),
            'status' => 'active',
            'category' => 'travel',
        ]);

        Goal::create([
            'user_id' => $user->id,
            'title' => 'Reserva de Emergência',
            'description' => 'Reserva para 6 meses de gastos',
            'target_amount' => 12000.00,
            'current_amount' => 5000.00,
            'target_date' => Carbon::now()->addYear(),
            'status' => 'active',
            'category' => 'emergency',
        ]);

        $this->command->info('Dados de teste PF criados com sucesso!');
    }
}
