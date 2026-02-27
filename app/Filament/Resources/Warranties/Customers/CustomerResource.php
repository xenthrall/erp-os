<?php

namespace App\Filament\Resources\Warranties\Customers;

use App\Filament\Resources\Warranties\Customers\Pages\ManageCustomers;
use App\Models\Warranties\Customer;
use BackedEnum;
use Dom\Text;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $recordTitleAttribute = 'document_number';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión de Garantías';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?string $modelLabel = 'Cliente';

    protected static ?string $pluralModelLabel = 'Clientes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('Nombres')
                    ->required(),
                TextInput::make('last_name')
                    ->label('Apellidos')
                    ->required(),
                Select::make('document_type')
                    ->label('Tipo de Documento')
                    ->default('CC')
                    ->options([
                        'CC' => 'Cédula de Ciudadanía',
                    ])
                    ->required(),
                TextInput::make('document_number')
                    ->label('Número de Documento')
                    ->required(),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->prefix('+57')
                    ->maxLength(10),
                TextInput::make('address')
                    ->label('Dirección')
                    ->placeholder('Calle 123 #45-67'),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema

    {
        return $schema
            ->schema([
                // SECCIÓN 1: Datos Personales
                Section::make('Información Personal')
                    ->description('Datos básicos y de identificación del cliente.')
                    ->schema([
                        TextEntry::make('first_name')
                            ->label('Nombre Completo')
                            ->columnSpanFull()
                            ->formatStateUsing(fn(Customer $record) => "{$record->first_name} {$record->last_name}"),

                        TextEntry::make('document_type')
                            ->label('Tipo de Documento')
                            ->placeholder('-'),

                        TextEntry::make('document_number')
                            ->label('Número de Documento'),
                    ])->columns(2),

                // SECCIÓN 2: Contacto
                Section::make('Datos de Contacto')
                    ->schema([
                        TextEntry::make('phone')
                            ->label('Teléfono')
                            ->icon('heroicon-m-phone') 
                            ->placeholder('-'),

                        TextEntry::make('address')
                            ->label('Dirección')
                            ->icon('heroicon-m-map-pin')
                            ->placeholder('-'),
                    ])->columns(2),

                // SECCIÓN 3: Relación Comercial
                Section::make('Estado y Relación Comercial')
                    ->schema([
                        TextEntry::make('warranty_requests_count')
                            ->counts('warrantyRequests')
                            ->label('Número de Garantías')
                            ->badge()
                            ->color(fn(int $state): string => $state > 0 ? 'info' : 'gray'),

                        IconEntry::make('is_active')
                            ->label('Cuenta Activa')
                            ->boolean(),
                    ])->columns(2),

                // SECCIÓN 4: Sistema (Colapsada por defecto para no saturar)
                Section::make('Auditoría del Sistema')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Registro Creado el')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number')
            ->columns([
                TextColumn::make('first_name')
                    ->label('Cliente')
                    ->wrap()
                    ->formatStateUsing(fn(Customer $record) => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('document_number')
                    ->label('Número de Documento')
                    ->formatStateUsing(fn(Customer $record) => "{$record->document_type} {$record->document_number}")
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),

                TextColumn::make('warranty_requests_count')
                    ->counts('warrantyRequests')
                    ->label('Garantías')
                    ->badge()
                    ->color(fn(int $state): string => $state > 0 ? 'info' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('address')
                    ->label('Dirección')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->disabled(fn(Customer $record) => $record->warrantyRequests()->exists())
                        ->tooltip(fn(Customer $record) => $record->warrantyRequests()->exists() ? 'No se puede eliminar un cliente con solicitudes de garantía asociadas.' : 'Eliminar Cliente'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCustomers::route('/'),
        ];
    }
}
