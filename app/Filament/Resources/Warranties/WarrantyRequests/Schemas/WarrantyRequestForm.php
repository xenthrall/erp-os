<?php

namespace App\Filament\Resources\Warranties\WarrantyRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WarrantyRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Campo oculto para registrar quién creó la solicitud (user_id)
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                // SECCIÓN 1: Información Principal
                Section::make('Información del Cliente y Proveedor')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Cliente')
                            // Cambiamos 'id' por 'first_name' para que el usuario vea el nombre, no el número
                            ->relationship('customer', 'first_name') 
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        Select::make('factory_id')
                            ->label('Fábrica / Proveedor')
                            ->relationship('factory', 'name')
                            ->searchable()
                            ->preload(), // Es nullable en DB, así que no lleva ->required()
                    ])->columns(2),

                // SECCIÓN 2: Detalles del Producto
                Section::make('Detalles del Producto y Facturación')
                    ->schema([
                        TextInput::make('model')
                            ->label('Modelo del Producto')
                            ->maxLength(255),
                            
                        TextInput::make('internal_code')
                            ->label('Código Interno')
                            ->maxLength(255),
                            
                        TextInput::make('invoice_number')
                            ->label('Número de Factura')
                            ->maxLength(255),
                            
                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),
                    ])->columns(2),

                // SECCIÓN 3: Fechas y Estado
                Section::make('Seguimiento y Estado')
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label('Fecha de Compra')
                            ->maxDate(now()), // Evita que pongan fechas en el futuro
                            
                        DatePicker::make('damage_date')
                            ->label('Fecha del Daño')
                            ->maxDate(now()),

                        Select::make('status')
                            ->label('Estado de la Solicitud')
                            ->options([
                                'pending' => 'Pendiente',
                                'approved' => 'Aprobada',
                                'in_process' => 'En Proceso',
                                'rejected' => 'Rechazada',
                                'completed' => 'Completada',
                            ])
                            ->default('pending')
                            ->native(false) // Selector con mejor diseño
                            ->required(),
                    ])->columns(3),

                // SECCIÓN 4: Logística y Falla
                Section::make('Logística y Descripción del Problema')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('shipping_city')
                                ->label('Ciudad de Envío')
                                ->maxLength(255),
                                
                            TextInput::make('shipping_address')
                                ->label('Dirección de Envío')
                                ->maxLength(255),
                        ]),

                        Textarea::make('failure_description')
                            ->label('Descripción detallada de la falla')
                            ->required()
                            ->rows(4) // Hace la caja de texto más grande por defecto
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}