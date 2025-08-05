<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

echo "=== VERIFICAÇÃO DE USUÁRIOS ADMIN ===\n";

// Verificar usuários admin existentes
$adminUsers = User::where('is_admin', true)->get();

if ($adminUsers->count() > 0) {
    echo "\n✅ Usuários admin encontrados:\n";
    foreach ($adminUsers as $user) {
        echo "ID: {$user->id} | Nome: {$user->name} | Email: {$user->email}\n";
    }
} else {
    echo "\n❌ Nenhum usuário admin encontrado!\n";
    echo "Criando usuário admin padrão...\n";
    
    // Criar usuário admin
    $admin = User::create([
        'name' => 'Administrador',
        'email' => 'admin@financeiro.com',
        'password' => Hash::make('admin123'),
        'is_admin' => true,
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Usuário admin criado!\n";
    echo "Email: admin@financeiro.com\n";
    echo "Senha: admin123\n";
}

echo "\n=== URL DE ACESSO ===\n";
echo "Login Admin: http://localhost:9015/admin/login\n";
echo "Dashboard Admin: http://localhost:9015/admin\n";

echo "\n=== TESTE DE ESTRUTURA ===\n";
// Verificar se as colunas existem
$user = User::first();
if ($user) {
    echo "Colunas da tabela users:\n";
    $columns = Schema::getColumnListing('users');
    foreach ($columns as $column) {
        echo "- {$column}\n";
    }
    
    echo "\nValores do primeiro usuário:\n";
    echo "ID: {$user->id}\n";
    echo "Nome: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "is_admin: " . ($user->is_admin ? 'true' : 'false') . "\n";
    echo "is_active: " . ($user->is_active ? 'true' : 'false') . "\n";
}
