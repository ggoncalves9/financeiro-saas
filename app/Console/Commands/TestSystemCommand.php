<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Account;
use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Goal;
use Illuminate\Support\Facades\Hash;

class TestSystemCommand extends Command
{
    protected $signature = 'test:system';
    protected $description = 'Testa o sistema criando dados de exemplo';

    public function handle()
    {
        $this->info('🚀 Iniciando teste completo do sistema...');
        
        // Buscar usuário de teste
        $user = User::where('email', 'admin@financeirosass.com')->first();
        
        if (!$user) {
            $this->error('❌ Usuário de teste não encontrado!');
            return 1;
        }
        
        $this->info("✅ Usuário encontrado: {$user->name} ({$user->email})");
        $this->info("✅ Tipo: {$user->type}");
        $this->info("✅ Tenant ID: {$user->tenant_id}");
        
        // Testar criação de conta
        $this->info('📊 Testando criação de contas...');
        
        // Buscar ou criar conta de teste
        $account = Account::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Conta Teste - Banco do Brasil'
        ], [
            'type' => 'checking',
            'bank' => 'banco_brasil',
            'balance' => 5000.00,
            'currency' => 'BRL',
            'is_active' => true
        ]);
        
        $this->info("✅ Conta criada/encontrada: {$account->name} (ID: {$account->id})");
        
        // Testar criação de receita
        $this->info('💰 Testando criação de receitas...');
        
        $revenue = Revenue::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'Salário Mensal'
        ], [
            'amount' => 8500.00,
            'category' => 'salary',
            'date' => now(),
            'account_id' => $account->id,
            'recurring' => true,
            'status' => 'confirmed'
        ]);
        
        $this->info("✅ Receita criada/encontrada: {$revenue->title} (R$ {$revenue->amount})");
        
        // Testar criação de despesa
        $this->info('💸 Testando criação de despesas...');
        
        $expense = Expense::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'Aluguel do Escritório'
        ], [
            'amount' => 2500.00,
            'category' => 'rent',
            'date' => now(),
            'due_date' => now()->addDays(10),
            'account_id' => $account->id,
            'status' => 'pending'
        ]);
        
        $this->info("✅ Despesa criada/encontrada: {$expense->title} (R$ {$expense->amount})");
        
        // Testar criação de meta
        $this->info('🎯 Testando criação de metas...');
        
        $goal = Goal::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'Viagem Europa 2024'
        ], [
            'target_amount' => 15000.00,
            'current_amount' => 8500.00,
            'target_date' => now()->addMonths(10),
            'category' => 'travel',
            'status' => 'active'
        ]);
        
        $this->info("✅ Meta criada/encontrada: {$goal->title} (R$ {$goal->current_amount} de R$ {$goal->target_amount})");
        
        // Estatísticas finais
        $this->info('📈 Estatísticas do sistema:');
        $this->info('   👥 Usuários: ' . User::count());
        $this->info('   🏦 Contas: ' . Account::count());
        $this->info('   💰 Receitas: ' . Revenue::count());
        $this->info('   💸 Despesas: ' . Expense::count());
        $this->info('   🎯 Metas: ' . Goal::count());
        
        $this->info('');
        $this->info('🎉 Teste completo finalizado!');
        $this->info('📝 Dados de login:');
        $this->info("   📧 Email: admin@financeirosass.com");
        $this->info("   🔑 Senha: password123");
        $this->info("   🌐 URL: http://127.0.0.1:9000/login");
        
        return 0;
    }
}
