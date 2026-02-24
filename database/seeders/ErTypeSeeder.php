<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ER\ErType;

class ErTypeSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/ErType.php');

        if (! file_exists($path)) {
            $this->command?->warn('Archivo database/data/ErType.php no encontrado.');
            return;
        }

        $types = require $path;

        foreach ($types as $type) {

            ErType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'severity' => $type['severity'],
                    'has_commission_penalty' => isset($type['commission_penalty_percentage']),
                    'commission_penalty_percentage' => $type['commission_penalty_percentage'] ?? null,
                    'description' => $type['description'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info('Tipos de ER cargados correctamente.');
    }
}