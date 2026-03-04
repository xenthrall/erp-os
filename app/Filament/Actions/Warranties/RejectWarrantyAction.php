<?php

namespace App\Filament\Actions\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RejectWarrantyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'rejectWarranty')
            ->label('Rechazar Garantía')
            ->icon('heroicon-o-x-circle')
            ->color('danger')

            ->modalHeading('Rechazar solicitud de garantía')
            ->modalDescription('Debes indicar el motivo del rechazo para que quede registrado.')
            ->modalSubmitActionLabel('Confirmar rechazo')
            ->requiresConfirmation()

            ->schema([
                Textarea::make('reason')
                    ->label('Motivo del rechazo')
                    ->placeholder('Describe por qué la garantía fue rechazada...')
                    ->required()
                    ->rows(4)
                    ->maxLength(1000),
            ])

            ->action(function (array $data, Model $record): void {

                // Cambiar estado
                $record->update([
                    'status' => WarrantyRequestStatus::Rejected,
                ]);

                // Registrar nota en bitácora
                $record->notes()->create([
                    'user_id' => Auth::id(),
                    'note' => 'Garantía rechazada: ' . $data['reason'],
                ]);

                $record->load('notes');
            })

            ->successNotificationTitle('La garantía fue rechazada correctamente');
    }
}