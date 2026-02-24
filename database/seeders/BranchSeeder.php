<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HR\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/branches.php');

        if (! file_exists($path)) {
            $this->command?->warn('Archivo database/data/branches.php no encontrado.');
            return;
        }

        $branches = require $path;

        foreach ($branches as $branch) {

            Branch::updateOrCreate(
                ['name' => $branch['name']], 
                [
                    'city' => $branch['city'],
                    'address' => $branch['address'],
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info('Sedes cargadas correctamente.');
    }
}