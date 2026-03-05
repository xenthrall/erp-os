<?php

namespace App\Filament\Actions\Warranties;

use App\Models\Warranties\WarrantyFactory;
use App\Models\Warranties\WarrantyRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class SelectFactoryAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'selectFactory')
            ->label('Asignar Fábrica')
            ->icon('heroicon-o-building-office-2')
            ->color('info')
            ->modalHeading('Asignar fábrica a la garantía')
            ->modalDescription('Selecciona la fábrica antes de procesar esta solicitud de garantía.')
            ->modalSubmitActionLabel('Asignar fábrica')
            ->modalWidth('md')

            ->schema([
                Select::make('factory_id')
                    ->label('Fábrica')
                    ->relationship('warrantyFactory', 'name')
                    ->searchable()
                    ->preload(),
            ])

            ->action(function (array $data, WarrantyRequest $record): void {
                $record->update([
                    'factory_id' => $data['factory_id'],
                ]);

                $record->load('warrantyFactory');
            })

            ->successNotificationTitle('Fábrica asignada correctamente');
    }
}