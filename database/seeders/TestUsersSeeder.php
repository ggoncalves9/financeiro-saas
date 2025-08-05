<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Usuário PF para teste
        User::updateOrCreate(
            ['email' => 'pf@teste.com'],
            [
                'name' => 'Usuário PF Teste',
                'email' => 'pf@teste.com',
                'password' => Hash::make('123456'),
                'type' => 'pf',
                'email_verified_at' => now(),
            ]
        );

        // Usuário PJ para teste
        User::updateOrCreate(
            ['email' => 'pj@teste.com'],
            [
                'name' => 'Empresa PJ Teste',
                'email' => 'pj@teste.com',
                'password' => Hash::make('123456'),
                'type' => 'pj',
                'email_verified_at' => now(),
            ]
        );

        // Usuário Admin para teste
        User::updateOrCreate(
            ['email' => 'admin@teste.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@teste.com',
                'password' => Hash::make('123456'),
                'type' => 'pj',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuários de teste criados com sucesso!');
        $this->command->info('PF: pf@teste.com / 123456');
        $this->command->info('PJ: pj@teste.com / 123456');
        $this->command->info('Admin: admin@teste.com / 123456');
    }
}
