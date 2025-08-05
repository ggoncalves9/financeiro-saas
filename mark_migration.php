<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== MARCANDO MIGRATION COMO EXECUTADA ===\n";

$migrationName = '2025_07_22_142000_add_parent_id_to_categories_table';

// Verificar se já existe
$exists = DB::table('migrations')->where('migration', $migrationName)->exists();

if ($exists) {
    echo "Migration já está marcada como executada!\n";
} else {
    // Inserir registro
    DB::table('migrations')->insert([
        'migration' => $migrationName,
        'batch' => 2
    ]);
    echo "Migration marcada como executada!\n";
}

echo "Verificando status...\n";
$status = DB::table('migrations')->where('migration', $migrationName)->first();
if ($status) {
    echo "Migration: {$status->migration} - Batch: {$status->batch}\n";
}
