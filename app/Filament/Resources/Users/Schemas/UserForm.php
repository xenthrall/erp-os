<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->placeholder('John Doe')
                    ->required(),
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->unique(ignoreRecord: true)
                    ->placeholder('nombre@ejemplo.com')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->revealable()
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->disabled(fn(string $operation): bool => $operation === 'edit')
                    ->dehydrated(fn(?string $state): bool => filled($state)),

                Select::make('roles')
                    ->label('Rol')
                    //->visible(fn() => auth()->user()?->can('user.manage_roles'))
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required(),
            ]);
    }
}
