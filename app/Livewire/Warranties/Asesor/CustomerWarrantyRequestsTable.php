<?php

namespace App\Livewire\Warranties\Asesor;

use App\Enums\Warranties\WarrantyRequestStatus;
use App\Filament\Actions\Warranties\ApproveWarrantyAction;
use App\Filament\Actions\Warranties\Asesor\CreateBatchBulkAction;
use App\Filament\Actions\Warranties\RejectWarrantyAction;
use App\Filament\Actions\Warranties\ReviewWarrantyAction;
use App\Filament\Actions\Warranties\SelectFactoryAction;
use App\Models\Warranties\WarrantyRequest;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Livewire\Attributes\On;

class CustomerWarrantyRequestsTable extends TableWidget
{
    public $customer;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return '';
    }

    #[On('warranty-batch-created')]
    public function refreshBatches()
    {
        $this->resetTable();
    }

    public function table(Table $table): Table
    {

        return $table         
            ->query(
                WarrantyRequest::query()
                    ->where('customer_id', $this->customer->id)
                    ->withCount('attachments')
            )
            ->columns([
                TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable(),

                TextColumn::make('warrantyFactory.name')
                    ->label('Fábrica')
                    ->placeholder('Pendiente de asignar')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(WarrantyRequestStatus $state): string => match ($state) {
                        WarrantyRequestStatus::Pending    => 'warning',
                        WarrantyRequestStatus::InReview   => 'info',
                        WarrantyRequestStatus::Approved   => 'success',
                        WarrantyRequestStatus::Rejected   => 'danger',
                    })
                    ->formatStateUsing(fn(WarrantyRequestStatus $state): string => match ($state) {
                        WarrantyRequestStatus::Pending    => 'Pendiente',
                        WarrantyRequestStatus::InReview   => 'En Revisión',
                        WarrantyRequestStatus::Approved   => 'Aprobada',
                        WarrantyRequestStatus::Rejected   => 'Rechazada',
                    }),

                TextColumn::make('quantity')
                    ->label('Cant.')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('attachments_count')
                    ->label('N° Archivos')
                    ->numeric()
                    ->badge()
                    ->color(
                        fn($state, $record) => ($record->attachments_count > 0)
                            ? 'success'
                            : 'danger'
                    )
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('damage_date')
                    ->label('Fecha Daño')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('purchase_date')
                    ->label('Fecha Compra')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('invoice_number')
                    ->label('N° Factura')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('internal_code')
                    ->label('Cód. Interno')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('shipping_city')
                    ->label('Ciudad Envío')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('creator.name')
                    ->label('Creado por')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Registro Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                ReviewWarrantyAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
