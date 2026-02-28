<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warranties\WarrantyFactory;

class WarrantyFactorySeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/WarrantyFactory.php');

        if (! file_exists($path)) {
            $this->command?->warn('Archivo database/data/WarrantyFactory.php no encontrado.');
            return;
        }

        $factories = require $path;

        foreach ($factories as $factory) {
            WarrantyFactory::updateOrCreate(
                // Buscamos por el código para evitar duplicados exactos
                ['code' => $factory['code']], 
                ['name' => $factory['name']]  
            );
        }

        $this->command?->info('Fábricas de garantía cargadas correctamente.');
    }
}