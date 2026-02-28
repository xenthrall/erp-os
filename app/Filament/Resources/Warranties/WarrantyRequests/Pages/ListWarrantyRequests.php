<?php

namespace App\Filament\Resources\Warranties\WarrantyRequests\Pages;

use App\Filament\Resources\Warranties\WarrantyRequests\WarrantyRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWarrantyRequests extends ListRecords
{
    protected static string $resource = WarrantyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
