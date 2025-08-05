<?php

// Bootstrap Laravel
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Criar usuário de teste PF
$user = User::create([
    'name' => 'João Silva',
    'email' => 'joao@teste.com',
    'password' => Hash::make('123456'),
    'type' => 'pf',
    'email_verified_at' => now(),
]);

$user->assignRole('user');

echo "Usuário de teste criado: joao@teste.com / 123456\n";

// Criar algumas receitas e despesas de exemplo
$user->revenues()->create([
    'title' => 'Salário',
    'description' => 'Salário mensal',
    'amount' => 5000.00,
    'date' => now()->format('Y-m-d'),
    'category' => 'Salário',
    'status' => 'received',
]);

$user->expenses()->create([
    'title' => 'Aluguel',
    'description' => 'Aluguel da casa',
    'amount' => 1500.00,
    'date' => now()->format('Y-m-d'),
    'category' => 'Moradia',
    'status' => 'paid',
]);

$user->goals()->create([
    'title' => 'Reserva de Emergência',
    'description' => 'Meta para reserva de emergência',
    'target_amount' => 30000.00,
    'current_amount' => 5000.00,
    'deadline' => now()->addYear()->format('Y-m-d'),
    'is_active' => true,
]);

echo "Dados de exemplo criados!\n";
