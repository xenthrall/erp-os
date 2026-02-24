<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
            $email = $data['email'] ?? null;

            // Buscar sucursal
            $branch = Branch::where('name', $branchName)->first();

            if (!$branch) {
                $this->command?->warn("Sucursal no encontrada: {$branchName}");
                continue;
            }

            // Buscar departamento dentro de la sucursal correcta
            $department = Department::where('name', $departmentName)
                ->where('branch_id', $branch->id)
                ->first();

            if (!$department) {
                $this->command?->warn("Departamento no encontrado: {$departmentName} en {$branchName}");
                continue;
            }

            // Buscar usuario por email (si existe)
            $userId = null;

            if (!empty($email)) {
                $user = User::where('email', trim($email))->first();
                $userId = $user?->id;
            }

            Employee::updateOrCreate(
                [
                    'document_number' => trim($data['document_number']),
                ],
                [
                    'user_id' => $userId,
                    'department_id' => $department->id,
                    'full_name' => trim($data['full_name']),
                    'position' => trim($data['position']),
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info('Empleados cargados correctamente.');
    }
}