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
            ->modifyQueryUsing(
                fn(Builder $query) => $query->with('userable')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),


                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),
                TextColumn::make('profile_document')
                    ->label('Documento')
                    ->getStateUsing(function ($record) {

                        $profile = $record->userable;

                        if (!$record->userable_type || !$record->userable_id) {
                            return 'Sin perfil asignado';
                        }

                        if (!$profile) {
                            return 'Perfil eliminado o inconsistente';
                        }

                        return $profile->document_number ?? 'Sin número de documento';
                    })
                    ->color(function ($record) {

                        if (!$record->userable_type || !$record->userable_id) {
                            return 'warning';
                        }

                        return $record->userable ? 'success' : 'danger';
                    })
                    ->tooltip(function ($record) {

                        if (!$record->userable_type || !$record->userable_id) {
                            return 'Este usuario no tiene ningún perfil asociado';
                        }

                        if (!$record->userable) {
                            return 'El perfil asociado fue eliminado pero el usuario conserva la referencia';
                        }

                        return 'Documento cargado correctamente';
                    })
                    ->badge()
                    ->wrap()
                    ->toggleable(),

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
                SelectFilter::make('profile_status')
                    ->label('Estado del Perfil')
                    ->options([
                        'valid' => 'Perfil válido',
                        'without' => 'Sin perfil asignado',
                        'orphan' => 'Perfil eliminado',
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        $value = $data['value'] ?? null;

                        if (!$value) {
                            return $query;
                        }

                        return match ($value) {

                            // Perfil correcto
                            'valid' => $query->whereNotNull('userable_type')
                                ->whereNotNull('userable_id')
                                ->whereHas('userable'),

                            // Sin perfil asignado
                            'without' => $query->whereNull('userable_type')
                                ->orWhereNull('userable_id'),

                            // Perfil eliminado pero referencia existe
                            'orphan' => $query->whereNotNull('userable_type')
                                ->whereNotNull('userable_id')
                                ->whereDoesntHave('userable'),

                            default => $query,
                        };
                    })
                    ->native(false),
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
