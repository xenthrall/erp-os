<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->unique(ignoreRecord: true)
                    ->placeholder('nombre@ejemplo.com')
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->label('Rol')
                    ->visible(fn() => Auth::user()?->can('users.manage_roles'))
                    ->disabled(function ($record) {

                        if (! $record) {
                            return false;
                        }

                        return ! Auth::user()->can('manageRoles', $record);
                    })
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            if (! Auth::user()?->hasRole('SUPER ADMIN')) {
                                $query->where('name', '!=', 'SUPER ADMIN');
                            }
                        }
                    )
                    ->preload()
                    ->native(false)
                    ->required(),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->revealable()
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->disabled(fn(string $operation): bool => $operation === 'edit')
                    ->dehydrated(fn(?string $state): bool => filled($state)),
            ]);
    }
}
