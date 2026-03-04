<?php

namespace App\Filament\Actions\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class SetPendingWarrantyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'setPending')
            ->label('Reset')
            ->icon('heroicon-o-arrow-path')
            ->color('gray')

            ->requiresConfirmation()
            ->modalHeading('Restablecer estado')
            ->modalDescription('Esto devolverá la garantía a estado Pendiente y eliminará la fábrica asignada.')
            ->modalSubmitActionLabel('Confirmar')

            ->action(function (Model $record): void {
                $record->update([
                    'status' => WarrantyRequestStatus::Pending,
                    'factory_id' => null,
                ]);
            })

            ->successNotificationTitle('Garantía restablecida a estado Pendiente');
    }
}