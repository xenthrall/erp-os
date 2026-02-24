<?php

namespace App\Filament\Resources\ER\ErReports\Pages;

use App\Filament\Resources\ER\ErReports\ErReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;


class ManageErReports extends ManageRecords
{
    protected static string $resource = ErReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['reporter_id'] = Auth::id();
                    $data['status'] = 'abierto'; 

                    return $data;
                }),
        ];
    }
}
