<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles and permissions
        $this->createRolesAndPermissions();

        // Create admin user
        $this->createAdminUser();
        
        // Run admin user seeder
        $this->call(AdminUserSeeder::class);

        // Create sample users
        $this->createSampleUsers();
    }

    private function createRolesAndPermissions(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Financial data
            'view revenues',
            'create revenues',
            'edit revenues',
            'delete revenues',
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'view goals',
            'create goals',
            'edit goals',
            'delete goals',
            'view accounts',
            'create accounts',
            'edit accounts',
            'delete accounts',
            
            // Reports
            'view reports',
            'export reports',
            
            // Team management (PJ only)
            'manage team',
            'invite members',
            'approve expenses',
            
            // Admin functions
            'view admin panel',
            'manage subscriptions',
            'view system stats',
            'manage system settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web'
        ]);
        $teamMemberRole = Role::firstOrCreate([
            'name' => 'team_member',
            'guard_name' => 'web'
        ]);
        $managerRole = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'web'
        ]);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $userRole->givePermissionTo([
            'view revenues',
            'create revenues',
            'edit revenues',
            'delete revenues',
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'view goals',
            'create goals',
            'edit goals',
            'delete goals',
            'view accounts',
            'create accounts',
            'edit accounts',
            'delete accounts',
            'view reports',
            'export reports',
        ]);

        $managerRole->givePermissionTo([
            'view revenues',
            'create revenues',
            'edit revenues',
            'delete revenues',
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'view goals',
            'create goals',
            'edit goals',
            'delete goals',
            'view accounts',
            'create accounts',
            'edit accounts',
            'delete accounts',
            'view reports',
            'export reports',
            'manage team',
            'invite members',
            'approve expenses',
        ]);

        $teamMemberRole->givePermissionTo([
            'view revenues',
            'create revenues',
            'view expenses',
            'create expenses',
            'view reports',
        ]);
    }

    private function createAdminUser(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@financeirosass.com'],
            [
                'name' => 'Administrador do Sistema',
                'password' => Hash::make('admin123'),
                'type' => 'pf',
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        // Create tenant for admin
        $tenant = Tenant::firstOrCreate([
            'slug' => 'admin-tenant',
        ], [
            'created_by' => $admin->id,
            'name' => 'Admin Tenant',
            'plan' => 'premium_pj',
            'is_active' => true,
        ]);

        $admin->update(['tenant_id' => $tenant->id]);

        $this->command->info('Usuário administrador criado: admin@financeirosass.com / admin123');
    }

    private function createSampleUsers(): void
    {
        // Create PF user
        $pfUser = User::create([
            'name' => 'João Silva',
            'email' => 'joao@exemplo.com',
            'password' => Hash::make('123456'),
            'type' => 'pf',
            'cpf' => '123.456.789-00',
            'phone' => '(11) 99999-9999',
            'birth_date' => '1985-06-15',
            'email_verified_at' => now(),
        ]);

        $pfTenant = Tenant::create([
            'created_by' => $pfUser->id,
            'name' => 'João Silva',
            'slug' => 'joao-silva',
            'plan' => 'pro_pf',
            'is_active' => true,
        ]);

        $pfUser->update(['tenant_id' => $pfTenant->id]);
        $pfUser->assignRole('user');

        // Create PJ user
        $pjUser = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@empresa.com',
            'password' => Hash::make('123456'),
            'type' => 'pj',
            'company_name' => 'Empresa Exemplo Ltda',
            'cnpj' => '12.345.678/0001-90',
            'phone' => '(11) 88888-8888',
            'email_verified_at' => now(),
        ]);

        $pjTenant = Tenant::create([
            'created_by' => $pjUser->id,
            'name' => 'Empresa Exemplo Ltda',
            'slug' => 'empresa-exemplo',
            'plan' => 'empresarial',
            'is_active' => true,
        ]);

        $pjUser->update(['tenant_id' => $pjTenant->id]);
        $pjUser->assignRole('manager');

        // Create sample financial data for PF user
        $this->createSampleFinancialData($pfUser);
        
        // Create sample financial data for PJ user
        $this->createSampleFinancialData($pjUser);

        $this->command->info('Usuários de exemplo criados:');
        $this->command->info('PF: joao@exemplo.com / 123456');
        $this->command->info('PJ: maria@empresa.com / 123456');
    }

    private function createSampleFinancialData(User $user): void
    {
        // Create accounts
        $checkingAccount = $user->accounts()->create([
            'name' => 'Conta Corrente Principal',
            'type' => 'checking',
            'bank_name' => 'Banco do Brasil',
            'account_number' => '12345-6',
            'balance' => 5000.00,
            'is_active' => true,
        ]);

        $savingsAccount = $user->accounts()->create([
            'name' => 'Poupança',
            'type' => 'savings',
            'bank_name' => 'Banco do Brasil',
            'account_number' => '98765-4',
            'balance' => 10000.00,
            'is_active' => true,
        ]);

        // Create revenues
        $user->revenues()->create([
            'title' => 'Salário',
            'description' => 'Salário mensal',
            'amount' => 5000.00,
            'date' => now()->startOfMonth(),
            'category' => 'salary',
            'status' => 'received',
            'is_recurring' => true,
            'recurring_period' => 'monthly',
            'account_id' => $checkingAccount->id,
        ]);

        $user->revenues()->create([
            'title' => 'Freelance',
            'description' => 'Projeto de desenvolvimento',
            'amount' => 2000.00,
            'date' => now()->subDays(5),
            'category' => 'freelance',
            'status' => 'received',
            'account_id' => $checkingAccount->id,
        ]);

        // Create expenses
        $user->expenses()->create([
            'title' => 'Aluguel',
            'description' => 'Aluguel mensal do apartamento',
            'amount' => 1200.00,
            'date' => now()->startOfMonth(),
            'due_date' => now()->startOfMonth()->addDays(10),
            'category' => 'housing',
            'status' => 'paid',
            'is_recurring' => true,
            'recurring_period' => 'monthly',
            'account_id' => $checkingAccount->id,
        ]);

        $user->expenses()->create([
            'title' => 'Supermercado',
            'description' => 'Compras da semana',
            'amount' => 300.00,
            'date' => now()->subDays(3),
            'category' => 'food',
            'status' => 'paid',
            'account_id' => $checkingAccount->id,
        ]);

        $user->expenses()->create([
            'title' => 'Conta de Luz',
            'description' => 'Energia elétrica',
            'amount' => 150.00,
            'date' => now(),
            'due_date' => now()->addDays(10),
            'category' => 'utilities',
            'status' => 'pending',
            'account_id' => $checkingAccount->id,
        ]);

        // Create goals
        $user->goals()->create([
            'title' => 'Viagem de Férias',
            'description' => 'Economizar para viagem em família',
            'target_amount' => 8000.00,
            'current_amount' => 2500.00,
            'target_date' => now()->addMonths(8),
            'type' => 'savings',
            'status' => 'active',
            'auto_save_enabled' => true,
            'auto_save_amount' => 500.00,
            'auto_save_frequency' => 'monthly',
        ]);

        $user->goals()->create([
            'title' => 'Reserva de Emergência',
            'description' => 'Fundo para emergências (6 meses de gastos)',
            'target_amount' => 18000.00,
            'current_amount' => 5000.00,
            'type' => 'savings',
            'status' => 'active',
            'auto_save_enabled' => true,
            'auto_save_amount' => 300.00,
            'auto_save_frequency' => 'monthly',
        ]);
    }
}
