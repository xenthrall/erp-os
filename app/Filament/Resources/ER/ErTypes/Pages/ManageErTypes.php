<?php

namespace App\Filament\Resources\ER\ErTypes\Pages;

use App\Filament\Resources\ER\ErTypes\ErTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageErTypes extends ManageRecords
{
    protected static string $resource = ErTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
