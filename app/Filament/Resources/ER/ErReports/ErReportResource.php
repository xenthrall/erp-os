<?php

namespace App\Filament\Resources\ER\ErReports;

use App\Filament\Actions\ER\UploadEvidenceAction;
use App\Filament\Resources\ER\ErReports\Pages\ManageErReports;
use App\Models\ER\ErReport;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'leve' => '🟢 Leve',
                        'moderado' => '🟡 Moderado',
                        'grave' => '🔴 Grave',
                        'critico' => '💥 Crítico',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'leve' => 'success',     // verde
                        'moderado' => 'warning', // amarillo
                        'grave' => 'danger',     // rojo
                        'critico' => 'gray',     // oscuro
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('reporter.name')
                    ->label('Reportado por')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'abierto' => 'Abierto',
                        'en_proceso' => 'En proceso',
                        'cerrado' => 'Cerrado',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
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
                    ->label('Monto')
                    ->money('COP')
                    ->color(fn ($state) => $state > 0 ? 'danger' : null)
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('type.has_commission_penalty')
                    ->label('Penalización')
                    ->formatStateUsing(function ($state, $record) {
                        return $state
                            ? number_format($record->type->commission_penalty_percentage, 2).'%'
                            : 'N/A';
                    })
                    ->color(fn ($state) => $state ? 'danger' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->alignCenter(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('attachments_count')
                    ->counts('attachments')
                    ->label('Evidencias')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'info' : 'gray')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: false),

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
                ActionGroup::make([
                    EditAction::make(),
                    UploadEvidenceAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageErReports::route('/'),
        ];
    }
}
