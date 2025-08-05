&lt;?php
/**
 * Script para limpar cache de rotas
 */

echo "&lt;h1&gt;Limpeza de Cache de Rotas&lt;/h1&gt;";
echo "&lt;style&gt;body{font-family:Arial,sans-serif;margin:20px;}.success{color:green;font-weight:bold;}.error{color:red;font-weight:bold;}&lt;/style&gt;";

// Tentar diferentes caminhos do PHP
$php_paths = [
    'C:\laragon\bin\php\php-8.3.16-nts-Win32-vs16-x64\php.exe',
    'C:\laragon\bin\php\php-8.3.1-nts-Win32-vs16-x64\php.exe',
    'C:\laragon\bin\php\php-8.2.15-nts-Win32-vs16-x64\php.exe',
    'C:\xampp\php\php.exe',
    'php'
];

$php_found = false;
$php_path = '';

foreach ($php_paths as $path) {
    if ($path === 'php') {
        // Tentar usar php diretamente
        $test = shell_exec('php -v 2>&1');
        if ($test && strpos($test, 'PHP') !== false) {
            $php_path = 'php';
            $php_found = true;
            break;
        }
    } else {
        if (file_exists($path)) {
            $php_path = $path;
            $php_found = true;
            break;
        }
    }
}

if (!$php_found) {
    echo "&lt;p class='error'&gt;PHP não encontrado! Tente executar manualmente:&lt;/p&gt;";
    echo "&lt;code&gt;php artisan route:clear&lt;br&gt;php artisan config:clear&lt;br&gt;php artisan cache:clear&lt;/code&gt;";
} else {
    echo "&lt;p class='success'&gt;PHP encontrado: $php_path&lt;/p&gt;";
    
    // Limpar cache de rotas
    echo "&lt;h2&gt;Limpando cache de rotas...&lt;/h2&gt;";
    $output = shell_exec("\"$php_path\" artisan route:clear 2>&1");
    echo "&lt;pre&gt;$output&lt;/pre&gt;";
    
    // Limpar cache de configuração
    echo "&lt;h2&gt;Limpando cache de configuração...&lt;/h2&gt;";
    $output = shell_exec("\"$php_path\" artisan config:clear 2>&1");
    echo "&lt;pre&gt;$output&lt;/pre&gt;";
    
    // Limpar cache geral
    echo "&lt;h2&gt;Limpando cache geral...&lt;/h2&gt;";
    $output = shell_exec("\"$php_path\" artisan cache:clear 2>&1");
    echo "&lt;pre&gt;$output&lt;/pre&gt;";
    
    // Listar rotas admin
    echo "&lt;h2&gt;Rotas admin disponíveis:&lt;/h2&gt;";
    $output = shell_exec("\"$php_path\" artisan route:list --name=admin 2>&1");
    echo "&lt;pre&gt;$output&lt;/pre&gt;";
}

echo "&lt;h2&gt;Testar Acesso:&lt;/h2&gt;";
echo "&lt;ul&gt;";
echo "&lt;li&gt;&lt;a href='/admin' target='_blank'&gt;Admin Dashboard&lt;/a&gt;&lt;/li&gt;";
echo "&lt;li&gt;&lt;a href='/admin/plans' target='_blank'&gt;Gerenciar Planos&lt;/a&gt;&lt;/li&gt;";
echo "&lt;li&gt;&lt;a href='/admin/payment-settings' target='_blank'&gt;Configurações de Pagamento&lt;/a&gt;&lt;/li&gt;";
echo "&lt;/ul&gt;";
?&gt;
