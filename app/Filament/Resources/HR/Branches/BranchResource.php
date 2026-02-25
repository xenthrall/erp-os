<?php

namespace App\Filament\Resources\HR\Branches;

use App\Filament\Resources\HR\Branches\Pages\ManageBranches;
use App\Models\HR\Branch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;


class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    //protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Recursos Humanos';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Sedes';

    protected static ?string $modelLabel = 'Sede';

    protected static ?string $pluralModelLabel = 'Sedes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('city')
                    ->label('Ciudad')
                    ->required(),
                TextInput::make('address')
                    ->label('Dirección'),

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
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Ciudad')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Dirección')
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
                    ->disabled(function(Branch $record) {
                        return $record->departments()->exists();
                    })
                    ->tooltip(function(Branch $record) {
                        if ($record->departments()->exists()) {
                            return 'No se puede eliminar esta sede porque tiene areas asociados.';
                        }
                        return null;
                    })
                    ->before(function (DeleteAction $action, Branch $record) {
                        $hasEmployees = $record->departments()->exists();
                        if ($hasEmployees) {
                            Notification::make()
                                ->title('No se puede eliminar la sede')
                                ->body('Esta sede tiene areas asociados y no puede ser eliminada.')
                                ->danger()
                                ->duration(5000)
                                ->send();
                        }
                        $action->cancel();
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBranches::route('/'),
        ];
    }
}
