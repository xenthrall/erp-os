<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HR\Department;
use App\Models\HR\Branch;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/departments.php');

        if (! file_exists($path)) {
            $this->command?->warn('Archivo database/data/departments.php no encontrado.');
            return;
        }

        $departments = require $path;

        foreach ($departments as $department) {

            $branch = Branch::where('name', $department['branch'])->first();

            if (! $branch) {
                $this->command?->warn("Sucursal no encontrada: {$department['branch']}");
                continue;
            }

            Department::updateOrCreate(
                [
                    'name' => $department['name'],
                    'branch_id' => $branch->id,
                ],
                [
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info('Áreas cargadas correctamente.');
    }
}