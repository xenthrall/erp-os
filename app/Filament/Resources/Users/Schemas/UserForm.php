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
                    ->required(),
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Verificado el'),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->revealable()
                    ->password()
                    ->required(),
                Select::make('roles')
                    ->label('Rol')
                    //->visible(fn() => auth()->user()?->can('user.manage_roles'))
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required(),
            ]);
    }
}
