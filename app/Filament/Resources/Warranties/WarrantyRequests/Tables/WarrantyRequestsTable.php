<?php

namespace App\Filament\Resources\Warranties\WarrantyRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction; // Recomendado para ver detalles sin editar
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\Warranties\WarrantyRequestStatus;
class WarrantyRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.first_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('factory.name')
                    ->label('Fábrica')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (WarrantyRequestStatus $state): string => match ($state) {
                        WarrantyRequestStatus::Pending    => 'warning',
                        WarrantyRequestStatus::Approved   => 'success',
                        WarrantyRequestStatus::Rejected   => 'danger',
                    })
                    ->formatStateUsing(fn (WarrantyRequestStatus $state): string => match ($state) {
                        WarrantyRequestStatus::Pending    => 'Pendiente',
                        WarrantyRequestStatus::Approved   => 'Aprobada',
                        WarrantyRequestStatus::Rejected   => 'Rechazada',
                    }),

                TextColumn::make('quantity')
                    ->label('Cant.')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('damage_date')
                    ->label('Fecha Daño')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('purchase_date')
                    ->label('Fecha Compra')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('invoice_number')
                    ->label('N° Factura')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('internal_code')
                    ->label('Cód. Interno')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('shipping_city')
                    ->label('Ciudad Envío')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('creator.name')
                    ->label('Creado por')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Registro Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                
            ])
            ->recordActions([
                ViewAction::make(), 
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            // Opcional: Ordenar por defecto para que las más recientes salgan primero
            ->defaultSort('created_at', 'desc'); 
    }
}