<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\HR\Employee;
use App\Models\HR\Department;
use App\Models\HR\Branch;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/employees.php');

        if (!file_exists($path)) {
            $this->command?->warn('Archivo database/data/employees.php no encontrado.');
            return;
        }

        $employees = require $path;

        foreach ($employees as $data) {

            $branchName = trim($data['branch']);
            $departmentName = trim($data['department']);
            $email = isset($data['email']) ? trim($data['email']) : null;

            // 1. Buscar la sucursal
            $branch = Branch::where('name', $branchName)->first();

            if (!$branch) {
                $this->command?->error("Sucursal no encontrada: {$branchName}");
                continue;
            }

            // 2. Buscar el departamento dentro de esa sucursal
            $department = Department::where('name', $departmentName)
                ->where('branch_id', $branch->id)
                ->first();

            if (!$department) {
                $this->command?->error("Departamento no encontrado: {$departmentName} en {$branchName}");
                continue;
            }

            // 3. Crear o actualizar el empleado (Módulo HR)
            $employee = Employee::updateOrCreate(
                [
                    'document_number' => trim($data['document_number']),
                ],
                [
                    'department_id' => $department->id,
                    'first_name'    => trim($data['first_name']),
                    'last_name'     => trim($data['last_name']),
                    'position'      => trim($data['position']),
                    'document_type' => 'CC', 
                    'is_active'     => true,
                ]
            );

            // 4. Sincronizar con la tabla Users (Polimorfismo)
            if ($email) {
                $employee->user()->updateOrCreate(
                    [
                        'email' => $email
                    ],
                    [
                        'name'     => trim($data['first_name']) . ' ' . trim($data['last_name']),
                        'password' => trim($data['document_number']),
                    ]
                );
            }
        }

        $this->command?->info('Sincronización de empleados y usuarios completada.');
    }
}