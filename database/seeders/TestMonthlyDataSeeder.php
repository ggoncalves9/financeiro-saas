<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Revenue;
use Carbon\Carbon;

class TestMonthlyDataSeeder extends Seeder
{
    public function run()
    {
        $users = User::whereIn('type', ['pf', 'pj'])->get();
        foreach ($users as $user) {
            $account = $user->accounts()->first();
            if (!$account) {
                $account = Account::create([
                    'user_id' => $user->id,
                    'name' => 'Conta Teste',
                    'balance' => 1000,
                    'is_active' => true,
                ]);
            }
            for ($i = 0; $i < 12; $i++) {
                $date = Carbon::now()->subMonths(11 - $i)->startOfMonth()->addDays(rand(0, 27));
                // Receita
                Revenue::create([
                    'user_id' => $user->id,
                    'account_id' => $account->id,
                    'title' => 'Receita Teste ' . ($i+1),
                    'amount' => rand(500, 2000),
                    'date' => $date,
                    'category' => 'Salário',
                    'status' => 'confirmed',
                ]);
                // Despesa
                Expense::create([
                    'user_id' => $user->id,
                    'account_id' => $account->id,
                    'title' => 'Despesa Teste ' . ($i+1),
                    'amount' => rand(200, 1500),
                    'date' => $date,
                    'due_date' => $date,
                    'category' => 'Moradia',
                    'status' => 'paid',
                ]);
            }
        }
    }
}
