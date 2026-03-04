<?php

namespace App\Filament\Resources\Warranties\Customers\Pages;

use App\Enums\Warranties\WarrantyRequestStatus;
use App\Filament\Actions\Warranties\ApproveWarrantyAction;
use App\Filament\Actions\Warranties\RejectWarrantyAction;
use App\Filament\Actions\Warranties\ReviewWarrantyAction;
use App\Filament\Actions\Warranties\SelectFactoryAction;
use App\Filament\Actions\Warranties\SetPendingWarrantyAction;
use App\Filament\Resources\Warranties\Customers\CustomerResource;
use App\Models\Warranties\WarrantyRequest;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManageCustomerWarranties extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithRecord;

    #protected static ?string $title = '';

    protected static string $resource = CustomerResource::class;

    protected string $view = 'filament.resources.warranties.customers.pages.manage-customer-warranties';

    public function getTitle(): string
    {
        return 'Garantías de ' . $this->record->first_name . ' ' . $this->record->last_name;
    }

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(
                WarrantyRequest::query()
                    ->where('customer_id', $this->record->id)
                    ->withCount('attachments')
            )
            ->columns([
                TextColumn::make('customer.first_name')
                    ->label('Cliente')
                    ->searchable(),

                TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable(),

                TextColumn::make('factory.name')
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
                SetPendingWarrantyAction::make(),

                ActionGroup::make([
                    SelectFactoryAction::make()
                        ->visible(
                            fn($record) =>
                            $record->status !== WarrantyRequestStatus::Pending
                                && $record->status !== WarrantyRequestStatus::Approved
                                && $record->status !== WarrantyRequestStatus::Rejected
                        ),

                    ApproveWarrantyAction::make()
                        ->visible(
                            fn($record) =>
                            $record->status !== WarrantyRequestStatus::Pending
                                && $record->status !== WarrantyRequestStatus::Approved
                                && $record->factory_id !== null
                                && $record->status !== WarrantyRequestStatus::Rejected
                        ),

                    RejectWarrantyAction::make()
                        ->visible(
                            fn($record) =>
                            $record->status !== WarrantyRequestStatus::Pending
                                && $record->status !== WarrantyRequestStatus::Approved
                                && $record->factory_id === null
                                && $record->status !== WarrantyRequestStatus::Rejected
                        ),
                ])
            ])
            ->toolbarActions([
                // ...
            ]);
    }
}
