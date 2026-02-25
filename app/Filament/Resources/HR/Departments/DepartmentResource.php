<?php

namespace App\Filament\Resources\HR\Departments;

use App\Filament\Resources\HR\Departments\Pages\ManageDepartments;
use App\Models\HR\Department;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;


class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    //protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Recursos Humanos';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Areas';

    protected static ?string $modelLabel = 'Area';

    protected static ?string $pluralModelLabel = 'Areas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->label('Sede')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
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
                TextColumn::make('branch.name')
                    ->label('Sede')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nombre')
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
                DeleteAction::make()
                    ->disabled(function(Department $record) {
                        return $record->employees()->exists();
                    })
                    ->tooltip(function(Department $record) {
                        if ($record->employees()->exists()) {
                            return 'No se puede eliminar este area porque tiene empleados asociados.';
                        }
                        return null;
                    })
                    ->before(function (DeleteAction $action, Department $record) {
                        $hasEmployees = $record->employees()->exists();
                        if ($hasEmployees) {
                            Notification::make()
                                ->title('No se puede eliminar el area')
                                ->body('Este area tiene empleados asociados y no puede ser eliminado.')
                                ->danger()
                                ->duration(5000)
                                ->send();
                        }
                        $action->cancel();
                    }),
                    
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDepartments::route('/'),
        ];
    }
}
