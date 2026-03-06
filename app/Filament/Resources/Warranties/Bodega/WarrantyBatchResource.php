<?php

namespace App\Filament\Resources\Warranties\Bodega;

use App\Filament\Resources\Warranties\Bodega\Pages\ManageWarrantyBatches;
use App\Models\Warranties\WarrantyBatch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WarrantyBatchResource extends Resource
{
    protected static ?string $model = WarrantyBatch::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión de Garantías';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Bodega';

    protected static ?string $modelLabel = 'Lote de Garantía';

    protected static ?string $pluralModelLabel = 'Lotes de Garantías';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.first_name')
                    ->label('Cliente'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->color(fn($state) => $state->color()),
                TextColumn::make('out_sequence')
                    ->label('Secuencia OUT')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('servientrega_guide')
                    ->label('Guia Servientrega')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sent_at')
                    ->label('Enviado el')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('closed_at')
                    ->label('Cerrado el')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWarrantyBatches::route('/'),
        ];
    }
}
