<?php

namespace App\Filament\Resources\HR\Employees;

use App\Filament\Resources\HR\Employees\Pages\ManageEmployees;
use App\Models\HR\Employee;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    //protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Recursos Humanos';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Empleados';

    protected static ?string $modelLabel = 'Empleado';

    protected static ?string $pluralModelLabel = 'Empleados';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('department_id')
                    ->label('Area')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('full_name')
                    ->label('Nombre Completo')
                    ->required(),
                TextInput::make('document_number')
                    ->label('Número de Documento')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('position')
                    ->label('Cargo'),

                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Empleado')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('document_number')
                    ->label('Documento')
                    ->searchable(),

                TextColumn::make('user.email')
                    ->label('Correo')
                    ->searchable()
                    ->default('Sin usuario de ingreso')
                    ->color(fn($record) => $record->user_id ? 'success' : 'danger')
                    ->tooltip(
                        fn($record) =>
                        $record->user_id
                            ? $record->user->email
                            : 'Este Empleado no tiene usuario para ingresar al sistema'
                    )
                    ->toggleable(),

                TextColumn::make('department.name')
                    ->label('Area')
                    ->searchable(),
                
                TextColumn::make('position')
                    ->label('Cargo')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
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
            'index' => ManageEmployees::route('/'),
        ];
    }
}
