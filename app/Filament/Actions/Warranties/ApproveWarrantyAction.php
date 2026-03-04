<?php

namespace App\Filament\Actions\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class ApproveWarrantyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'approveWarranty')
            ->label('Aprobar Garantía')
            ->icon('heroicon-o-check-circle')
            ->color('success')

            ->requiresConfirmation()
            ->modalHeading('Aprobar solicitud de garantía')
            ->modalDescription('Esta acción aprobará la solicitud')
            ->modalSubmitActionLabel('Sí, aprobar')

            ->action(function (Model $record, Action $action): void {

                if (! $record->factory_id) {
                    Notification::make()
                        ->title('Debes asignar una fábrica antes de aprobar la garantía.')
                        ->danger()
                        ->send();

                    $action->cancel();
                }

                $record->update([
                    'status' => WarrantyRequestStatus::Approved,
                ]);
            })

            ->successNotificationTitle('La garantía fue aprobada correctamente');
    }
}