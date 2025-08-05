<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email} {password} {name=Admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar um usuário administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Verificar se o usuário já existe
        if (User::where('email', $email)->exists()) {
            $this->error("Usuário com email {$email} já existe!");
            return 1;
        }

        // Criar usuário admin
        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'type' => 'pj',
            'company_name' => 'Empresa Admin LTDA',
            'is_active' => true,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Criar tenant para o admin
        $tenant = Tenant::create([
            'name' => $name . ' - Admin',
            'domain' => 'admin',
            'database' => 'admin_db',
            'is_active' => true,
        ]);

        $admin->tenant_id = $tenant->id;
        $admin->save();

        $this->info("Usuário administrador criado com sucesso!");
        $this->info("Email: {$email}");
        $this->info("Senha: {$password}");
        $this->info("Acesse: /admin para acessar o painel administrativo");

        return 0;
    }
}
