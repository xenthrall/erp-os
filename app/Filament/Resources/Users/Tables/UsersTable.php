<?php

namespace App\Filament\Resources\Users\Tables;

use Dom\Text;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                //Rol de spatie
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->searchable(),
                //Tipo de perfil
                TextColumn::make('userable_type')
                    ->label('Tipo de Perfil')
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        \App\Models\Warranties\Customer::class => 'Cliente',
                        \App\Models\HR\Employee::class => 'Empleado',
                        default => 'Sin perfil asignado', 
                    }),

                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                IconColumn::make('email_verified_at')
                    ->label('Email Verificado')
                    ->default(false)
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('userable_type')
                    ->label('Tipo de Perfil')
                    ->options([
                        \App\Models\Warranties\Customer::class => 'Cliente',
                        \App\Models\HR\Employee::class => 'Empleado',
                    ])
                    ->native(false),
                SelectFilter::make('role')
                    ->label('Rol del Sistema')
                    ->options(function () {
                        $roles = Role::pluck('name', 'id')->toArray();
                        return ['none' => 'Sin rol asignado'] + $roles;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        if (blank($value)) {
                            return $query;
                        }
                        if ($value === 'none') {
                            return $query->doesntHave('roles');
                        }
                        return $query->whereHas('roles', function (Builder $query) use ($value) {
                            $query->where('roles.id', $value);
                        });
                    })
                    ->native(false)
                    ->searchable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
