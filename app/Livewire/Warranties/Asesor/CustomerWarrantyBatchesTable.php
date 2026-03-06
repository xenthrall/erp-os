<?php

namespace App\Livewire\Warranties\Asesor;

use App\Models\Warranties\WarrantyBatch;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Livewire\Attributes\On;

class CustomerWarrantyBatchesTable extends TableWidget
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
                fn() =>
                WarrantyBatch::query()
                    ->where('customer_id', $this->customer->id)
            )

            ->columns([
                TextColumn::make('id')
                    ->label('Lote'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->color(fn($state) => $state->color()),

                TextColumn::make('servientrega_guide')
                    ->label('Guía'),

                TextColumn::make('sent_at')
                    ->dateTime(),

                TextColumn::make('closed_at')
                    ->dateTime(),
            ])

            ->recordActions([
                Action::make('verSolicitudes')
                    ->label('Solicitudes')
                    ->icon('heroicon-o-eye')

                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('Solicitudes del lote')
                            ->schema([

                                TextEntry::make('id')
                                    ->label('Solicitud'),

                                TextEntry::make('status')
                                    ->badge(),

                                TextEntry::make('product_name')
                                    ->label('Producto'),

                                TextEntry::make('created_at')
                                    ->label('Creada')
                                    ->dateTime(),
                            ])
                            ->columns(4),
                    ])
                    ->modalHeading(fn($record) => "Solicitudes del lote #{$record->id}")
                    ->modalSubmitAction(false),
            ]);
    }
}
