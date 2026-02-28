<?php

namespace App\Filament\Resources\Warranties\WarrantyFactories;

use App\Filament\Resources\Warranties\WarrantyFactories\Pages\ManageWarrantyFactories;
use App\Models\Warranties\WarrantyFactory;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WarrantyFactoryResource extends Resource
{
    protected static ?string $model = WarrantyFactory::class;

    protected static ?string $recordTitleAttribute = 'code';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión de Garantías';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Fábricas';

    protected static ?string $modelLabel = 'Fábrica de Garantía';

    protected static ?string $pluralModelLabel = 'Fábricas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código')
                    ->placeholder('FAC001')
                    ->maxLength(10)
                    ->disabled(fn(string $operation): bool => $operation === 'edit')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255)
                    ->placeholder('Fábrica XYZ')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('current_sequence')
                    ->label('Secuencia Actual')
                    ->alignCenter(),

                TextColumn::make('warranties_requests_count')
                    ->counts('warrantiesRequests')
                    ->label('Garantías Asociadas')
                    ->badge()
                    ->color(fn(int $state): string => $state > 0 ? 'info' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->alignCenter(),
                    
                TextColumn::make('created_at')
                    ->label('Creado El')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado El')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->disabled(fn(WarrantyFactory $record) => $record->warrantiesRequests()->exists())
                        ->tooltip(fn(WarrantyFactory $record) => $record->warrantiesRequests()->exists() ? 'No se puede eliminar una fábrica con solicitudes de garantía asociadas.' : 'Eliminar Fábrica'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWarrantyFactories::route('/'),
        ];
    }
}
