<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (Model $record): bool => $record->userable_id !== null)
                ->tooltip(fn (Model $record): ?string => 
                    $record->userable_id !== null 
                        ? 'No se puede eliminar porque tiene un perfil (Cliente/Empleado) vinculado.' 
                        : null
                ),
        ];
    }
}
