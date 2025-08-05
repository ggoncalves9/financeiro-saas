<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin se não existir
        $admin = User::firstOrCreate(
            ['email' => 'admin@financeirosass.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@financeirosass.com',
                'password' => Hash::make('admin123'),
                'type' => 'pj',
                'company_name' => 'Empresa Teste LTDA',
                'is_active' => true,
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuário admin criado: admin@financeirosass.com / admin123');
    }
}
