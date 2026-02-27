<?php

namespace App\Filament\Resources\Warranties\WarrantyRequests;

use App\Filament\Resources\Warranties\WarrantyRequests\Pages\CreateWarrantyRequest;
use App\Filament\Resources\Warranties\WarrantyRequests\Pages\EditWarrantyRequest;
use App\Filament\Resources\Warranties\WarrantyRequests\Pages\ListWarrantyRequests;
use App\Filament\Resources\Warranties\WarrantyRequests\Schemas\WarrantyRequestForm;
use App\Filament\Resources\Warranties\WarrantyRequests\Tables\WarrantyRequestsTable;
use App\Models\Warranties\WarrantyRequest;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WarrantyRequestResource extends Resource
{
    protected static ?string $model = WarrantyRequest::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión de Garantías';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Solicitudes de Garantía';

    protected static ?string $modelLabel = 'Solicitud de Garantía';

    protected static ?string $pluralModelLabel = 'Solicitudes de Garantía';


    public static function form(Schema $schema): Schema
    {
        return WarrantyRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarrantyRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarrantyRequests::route('/'),
            'create' => CreateWarrantyRequest::route('/create'),
            'edit' => EditWarrantyRequest::route('/{record}/edit'),
        ];
    }
}
