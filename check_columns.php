<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "=== VERIFICAÇÃO DE COLUNAS ===\n";

// Categories
$categoriesColumns = Schema::getColumnListing('categories');
echo "Colunas da tabela categories:\n";
foreach ($categoriesColumns as $column) {
    echo "- {$column}\n";
}

// Subscriptions
$subscriptionsColumns = Schema::getColumnListing('subscriptions');
echo "\nColunas da tabela subscriptions:\n";
foreach ($subscriptionsColumns as $column) {
    echo "- {$column}\n";
}
