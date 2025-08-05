<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['name' => 'pessoa_fisica', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'empresa', 'guard_name' => 'web']);
    }
}
