<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\HR\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::firstOrCreate([
            'name' => 'SUPER ADMIN',
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(Permission::all());

        $employee = Employee::firstOrCreate(
            ['document_number' => '00000000'], 
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'document_type' => 'ID',
                'phone' => '3000000000',
                'birth_date' => now()->subYears(30),
                'hire_date' => now(),
                'position' => 'Administrador General',
                'is_active' => true,
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => 'Pas#word2341',
                'userable_id' => $employee->id,
                'userable_type' => Employee::class,
            ]
        );

        $user->assignRole($role);
    }
}