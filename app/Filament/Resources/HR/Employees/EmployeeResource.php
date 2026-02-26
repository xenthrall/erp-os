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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use App\Models\HR\Branch;
use Illuminate\Database\Eloquent\Builder;

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
                Select::make('branch_id')
                    ->label('Sede / Sucursal')
                    ->options(Branch::pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn($set) => $set('department_id', null))
                    ->afterStateHydrated(function ($set, $record) {
                        if ($record && $record->department) {
                            $set('branch_id', $record->department->branch_id);
                        }
                    }),

                Select::make('department_id')
                    ->label('Área / Departamento')
                    ->relationship(
                        name: 'department',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, $get) =>
                        $query->where('branch_id', $get('branch_id'))
                    )
                    ->searchable()
                    ->preload(),

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
                        'CE' => 'Cédula de Extranjería',
                        'PP' => 'Pasaporte',
                        'TI' => 'Tarjeta de Identidad',
                    ])
                    ->required(),
                TextInput::make('document_number')
                    ->label('Número de Documento')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->prefix('+57')
                    ->maxLength(10),
                DatePicker::make('birth_date')
                    ->label('Fecha de Nacimiento'),
                DatePicker::make('hire_date')
                    ->label('Fecha de Contratación'),
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
                    ->searchable(['first_name', 'last_name'])
                    ->wrap(),

                TextColumn::make('document_number')
                    ->label('Documento')
                    ->formatStateUsing(fn(Employee $record) => "{$record->document_type} {$record->document_number}")
                    ->searchable(),

                TextColumn::make('user.email')
                    ->label('Correo')
                    ->searchable()
                    ->default('Sin usuario de ingreso')
                    ->color(fn($record) => $record->user? 'success' : 'danger')
                    ->tooltip(
                        fn($record) =>
                        $record->user
                            ? $record->user->email
                            : 'Este Empleado no tiene usuario para ingresar al sistema'
                    )
                    ->toggleable(),

                TextColumn::make('department.name')
                    ->label('Area')
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Cargo')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->toggleable(isToggledHiddenByDefault: true)
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
                DeleteAction::make()
                    ->disabled(function (Employee $record) {
                        return $record->errorReports()->exists();
                    })
                    ->tooltip(function (Employee $record) {
                        if ($record->errorReports()->exists()) {
                            return 'No se puede eliminar este empleado porque tiene reportes de errores asociados.';
                        }
                        return null;
                    })
                    ->before(function (DeleteAction $action, Employee $record) {
                        $hasErrorReports = $record->errorReports()->exists();
                        if ($hasErrorReports) {
                            Notification::make()
                                ->title('No se puede eliminar el empleado')
                                ->body('Este empleado tiene reportes de errores asociados y no puede ser eliminado.')
                                ->danger()
                                ->duration(5000)
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEmployees::route('/'),
        ];
    }
}
