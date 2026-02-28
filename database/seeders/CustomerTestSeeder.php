<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warranties\Customer;
use Spatie\Permission\Models\Role;

class CustomerTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el rol de CLIENTE si no existe
        Role::firstOrCreate(['name' => 'CLIENTE', 'guard_name' => 'web']);

        // 2. Creamos el registro del Cliente
        $customer = Customer::updateOrCreate(
            [
                'document_number' => '1020304050',
            ],
            [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'document_type' => 'CC',
            
            'phone' => '3001234567',
            'address' => 'Calle Falsa 123',
            'is_active' => true,
        ]);

       
        $user = $customer->user()->updateOrCreate(
            [
                'email' => 'cliente@prueba.com',
            ],
            [
            'name' => $customer->first_name . ' ' . $customer->last_name,
            
            'password' => 'password123',
        ]);

        $user->assignRole('CLIENTE');
    }
}