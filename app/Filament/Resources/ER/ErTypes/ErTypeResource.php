<?php

namespace App\Filament\Resources\ER\ErTypes;

use App\Filament\Resources\ER\ErTypes\Pages\ManageErTypes;
use App\Models\ER\ErType;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ErTypeResource extends Resource
{
    protected static ?string $model = ErType::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión Disciplinaria';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Tipos de ER';

    protected static ?string $modelLabel = 'Tipo de ER';

    protected static ?string $pluralModelLabel = 'Tipos de ER';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                Select::make('severity')
                    ->label('Gravedad')
                    ->options([
                        'leve' => '🟢 Leve',
                        'moderado' => '🟡 Moderado',
                        'grave' => '🔴 Grave',
                        'critico' => '💥 Crítico',
                    ])
                    ->required()
                    ->placeholder('Selecciona la gravedad'),
                Toggle::make('has_commission_penalty')
                    ->label('¿Aplica penalización de comisión?')
                    ->default(false)
                    ->reactive(),

                TextInput::make('commission_penalty_percentage')
                    ->label('Porcentaje de penalización de comisión')
                    ->numeric()
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->visible(fn($get) => $get('has_commission_penalty'))
                    ->required(fn($get) => $get('has_commission_penalty'))
                    ->rules([
                        'nullable',
                        'numeric',
                        'min:0',
                        'max:100',
                    ]),

                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('¿Activo?')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('severity')
                    ->label('Gravedad')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'leve' => 'Leve',
                        'moderado' => 'Moderado',
                        'grave' => 'Grave',
                        'critico' => 'Crítico',
                        default => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'leve' => 'success',     // verde
                        'moderado' => 'warning', // amarillo
                        'grave' => 'danger',     // rojo
                        'critico' => 'gray',     // oscuro
                    })
                    ->searchable(),
                TextColumn::make('has_commission_penalty')
                    ->label('Penalización Comisión')
                    ->formatStateUsing(function ($state, $record) {
                        return $state
                            ? number_format($record->commission_penalty_percentage, 2) . '%'
                            : 'N/A';
                    })
                    ->color(fn($state) => $state ? 'danger' : 'gray')
                    ->alignCenter(),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageErTypes::route('/'),
        ];
    }
}
