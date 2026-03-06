<?php

namespace App\Filament\Resources\Warranties\Bodega\Pages;

use App\Filament\Resources\Warranties\Bodega\WarrantyBatchResource;
use Filament\Resources\Pages\ManageRecords;

class ManageWarrantyBatches extends ManageRecords
{
    protected static string $resource = WarrantyBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
