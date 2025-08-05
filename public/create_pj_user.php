<?php
// Script para criar usuário PJ de teste
echo "<h2>Criação de Usuário PJ para Teste</h2>";
echo "<pre>";

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // Verificar se já existe um usuário PJ
    $existingPJ = DB::table('users')->where('email', 'empresa@teste.com')->first();
    
    if (!$existingPJ) {
        // Criar usuário PJ
        $userId = DB::table('users')->insertGetId([
            'name' => 'Empresa Teste LTDA',
            'email' => 'empresa@teste.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'type' => 'pj',
            'company_name' => 'Empresa Teste LTDA',
            'cnpj' => '12.345.678/0001-90',
            'company_size' => 'small',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Usuário PJ criado com sucesso!\n";
        echo "📧 Email: empresa@teste.com\n";
        echo "🔑 Senha: 123456\n";
        echo "🏢 Tipo: PJ (Pessoa Jurídica)\n";
    } else {
        echo "ℹ️ Usuário PJ já existe!\n";
        echo "📧 Email: empresa@teste.com\n";
        echo "🔑 Senha: 123456\n";
        echo "🏢 Tipo: PJ (Pessoa Jurídica)\n";
    }
    
    echo "\n📋 INSTRUÇÕES PARA TESTE:\n";
    echo "1. Faça logout do usuário atual\n";
    echo "2. Faça login com empresa@teste.com / 123456\n";
    echo "3. Teste as categorias e subcategorias como usuário PJ\n";
    echo "4. Teste criação de despesas e receitas\n";
    echo "5. Verifique relatórios específicos de PJ (DRE, Impostos)\n";
    
    echo "\n🔗 <a href='/logout'>Fazer Logout</a>\n";
    echo "🔗 <a href='/login'>Fazer Login</a>\n";
    echo "🔗 <a href='/categories'>Testar Categorias</a>\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
