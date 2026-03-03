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
                ->visible(function (Model $record): bool {
                    $hasTypeAndId = $record->userable_type && $record->userable_id;
                    if ($hasTypeAndId && $record->userable) {
                        return false;
                    }
                    return true;
                })
                ->tooltip(function (Model $record): ?string {
                    $hasTypeAndId = $record->userable_type && $record->userable_id;
                    if ($hasTypeAndId && !$record->userable) { return 'Perfil inconsistente detectado. Puedes eliminar este usuario.';}
                    if (!$hasTypeAndId) { return 'Usuario sin perfil asociado. Puedes eliminarlo.';}
                    return null;
                }),
        ];
    }
}
