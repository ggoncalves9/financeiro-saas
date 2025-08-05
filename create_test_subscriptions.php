<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== CRIANDO DADOS DE ASSINATURA PARA TESTE ===\n";

// Buscar usuários ativos
$users = User::where('is_active', true)->get();

if ($users->count() > 0) {
    foreach ($users as $user) {
        // Verificar se já tem assinatura
        $existingSubscription = DB::table('subscriptions')->where('user_id', $user->id)->first();
        
        if (!$existingSubscription) {
            // Criar assinatura padrão
            DB::table('subscriptions')->insert([
                'user_id' => $user->id,
                'status' => 'active',
                'plan' => 'premium',
                'amount' => 2990, // R$ 29,90 em centavos
                'monthly_revenue' => 2990,
                'currency' => 'BRL',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "Assinatura criada para usuário: {$user->name} (ID: {$user->id})\n";
        } else {
            echo "Usuário {$user->name} já possui assinatura\n";
        }
    }
} else {
    echo "Nenhum usuário ativo encontrado!\n";
}

// Verificar total de assinaturas
$totalSubscriptions = DB::table('subscriptions')->count();
$activeSubscriptions = DB::table('subscriptions')->where('status', 'active')->count();
$totalRevenue = DB::table('subscriptions')->where('status', 'active')->sum('amount');

echo "\n=== RESUMO ===\n";
echo "Total de assinaturas: {$totalSubscriptions}\n";
echo "Assinaturas ativas: {$activeSubscriptions}\n";
echo "Receita total: R$ " . number_format($totalRevenue / 100, 2, ',', '.') . "\n";
