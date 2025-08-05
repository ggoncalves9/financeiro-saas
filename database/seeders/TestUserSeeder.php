<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar ou atualizar usuário de teste
        $user = User::updateOrCreate(
            ['email' => 'admin@financeirosass.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password123'),
                'type' => 'pj',
                'company_name' => 'Empresa Teste LTDA',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Verificar se tem tenant
        if (!$user->tenant_id) {
            $tenant = Tenant::create([
                'name' => 'Empresa Teste LTDA',
                'slug' => 'empresa-teste-' . Str::random(8),
                'plan' => 'premium',
                'is_active' => true,
            ]);

            $user->update(['tenant_id' => $tenant->id]);
        }

        $this->command->info('Usuário de teste criado/atualizado:');
        $this->command->info('Email: admin@financeirosass.com');
        $this->command->info('Senha: password123');
    }
}
