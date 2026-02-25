<?php

namespace App\Filament\Actions\ER;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AddNoteAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'addNote')
            ->label('Agregar Observación')
            ->icon('heroicon-o-chat-bubble-bottom-center-text')
            ->color('primary')
            ->modalHeading('Nueva observación para la bitácora')
            ->modalDescription('Esta nota quedará registrada con tu usuario y la fecha actual.')
            ->modalSubmitActionLabel('Guardar en bitácora')
            ->modalWidth('md')            
            ->disabled(fn (Model $record) => $record->notes()->where('user_id', Auth::id())->count() >= 3)            
            ->tooltip(fn (Model $record) => $record->notes()->where('user_id', Auth::id())->count() >= 3 
                ? 'Has alcanzado el límite de 3 observaciones para este reporte.' 
                : 'Añadir nueva observación'
            )
            
            ->schema([
                Textarea::make('note')
                    ->label('Observación')
                    ->placeholder('Escribe los detalles del seguimiento o novedad aquí...')
                    ->required()
                    ->rows(4)
                    ->maxLength(1000),
            ])
            ->action(function (array $data, Model $record, Action $action): void {
                if (! $record->exists) {
                    return;
                }
                $record->notes()->create([
                    'user_id' => Auth::id(),
                    'note'    => $data['note'],
                ]);

                // Actualizamos el estado automáticamente si es el primer contacto
                if ($record->status === 'abierto') {
                    $record->update([
                        'status' => 'cerrado' 
                    ]);
                }

                // Refrescamos las relaciones
                $record->load('notes');
            })
            ->successNotificationTitle('Observación agregada y reporte actualizado');
    }
}