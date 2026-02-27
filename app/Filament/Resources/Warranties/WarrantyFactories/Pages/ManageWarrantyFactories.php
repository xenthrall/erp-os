<?php

namespace App\Filament\Resources\Warranties\WarrantyFactories\Pages;

use App\Filament\Resources\Warranties\WarrantyFactories\WarrantyFactoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWarrantyFactories extends ManageRecords
{
    protected static string $resource = WarrantyFactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
