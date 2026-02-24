<?php

namespace App\Filament\Resources\ER\ErReports;

use App\Filament\Resources\ER\ErReports\Pages\ManageErReports;
use App\Models\ER\ErReport;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ErReportResource extends Resource
{
    protected static ?string $model = ErReport::class;

    protected static ?string $recordTitleAttribute = 'status';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión Disciplinaria';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Reportes';

    protected static ?string $modelLabel = 'Reporte de error';

    protected static ?string $pluralModelLabel = 'Reportes de errores';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('er_type_id')
                    ->label('Tipo de ER')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('employee_id')
                    ->label('Empleado involucrado')
                    ->relationship('employee', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('event_date')
                    ->label('Fecha del evento')
                    ->required(),
                TextInput::make('discount_amount')
                    ->label('Monto de descuento')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->columnSpanFull(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Empleado')
                    ->searchable(),

                TextColumn::make('type.name')
                    ->label('Tipo de ER')
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('type.severity')
                    ->label('Gravedad')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'leve' => '🟢 Leve',
                        'moderado' => '🟡 Moderado',
                        'grave' => '🔴 Grave',
                        'critico' => '💥 Crítico',
                        default => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'leve' => 'success',     // verde
                        'moderado' => 'warning', // amarillo
                        'grave' => 'danger',     // rojo
                        'critico' => 'gray',     // oscuro
                    })
                    ->searchable(),

                TextColumn::make('reporter.name')
                    ->label('Reportado por')
                    ->searchable(),
                
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'abierto' => 'Abierto',
                        'en_proceso' => 'En proceso',
                        'cerrado' => 'Cerrado',
                        default => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'abierto' => 'danger',     // rojo
                        'en_proceso' => 'warning', // amarillo
                        'cerrado' => 'success',     // verde
                        default => 'gray',
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('event_date')
                    ->label('Fecha del evento')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('discount_amount')
                    ->label('Monto de descuento')
                    ->money('COP')
                    ->color(fn ($state) => $state > 0 ? 'danger' : null)
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado en')
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
                BulkActionGroup::make([

                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageErReports::route('/'),
        ];
    }
}
