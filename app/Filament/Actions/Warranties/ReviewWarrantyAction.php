<?php

namespace App\Filament\Actions\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use App\Models\Warranties\WarrantyRequest;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ReviewWarrantyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'reviewWarranty')
            ->label('Revisar')
            ->icon('heroicon-o-eye')
            ->color('info')
            ->modalHeading('Revisión de Solicitud de Garantía')
            ->modalWidth('7xl')
            ->mountUsing(function (Model $record) {
                if ($record->status === WarrantyRequestStatus::Pending) {
                    $record->update([
                        'status' => WarrantyRequestStatus::InReview,
                    ]);
                }

                $record->load([
                    'customer',
                    'creator',
                    'warrantyFactory',
                    'attachments',
                ]);
            })
            ->schema([
                Tabs::make('Información de la Solicitud de Garantía')
                    ->activeTab(2)
                    ->tabs([
                        Tab::make('Información del Cliente')
                            ->icon(Heroicon::Identification)
                            ->schema([
                                Section::make('Información del Cliente')
                                    ->description('Datos básicos del cliente asociado.')
                                    ->schema([
                                        TextEntry::make('customer.first_name')
                                            ->label('Cliente')
                                            ->formatStateUsing(fn($record) => "{$record->customer->first_name} {$record->customer->last_name}")
                                            ->columnSpanFull(),

                                        TextEntry::make('customer.document_number')
                                            ->label('Documento')
                                            ->placeholder('-'),

                                        TextEntry::make('customer.phone')
                                            ->label('Teléfono')
                                            ->placeholder('-'),
                                    ])
                                    ->columns(2),
                                Section::make('Auditoría del Sistema')
                                    ->schema([
                                        TextEntry::make('creator.name')
                                            ->label('Creado por'),

                                        TextEntry::make('created_at')
                                            ->label('Fecha de Creación')
                                            ->dateTime('d M Y H:i'),

                                        TextEntry::make('updated_at')
                                            ->label('Última Actualización')
                                            ->dateTime('d M Y H:i'),
                                    ])
                                    ->columns(2)
                                    ->collapsed(),
                            ]),
                        Tab::make('Información del Producto')
                            ->icon(Heroicon::Cube)
                            ->schema([
                                Section::make('Información del Producto')
                                    ->schema([
                                        TextEntry::make('model')
                                            ->label('Modelo')
                                            ->placeholder('-'),

                                        TextEntry::make('quantity')
                                            ->label('Cantidad'),

                                        TextEntry::make('invoice_number')
                                            ->label('Factura')
                                            ->placeholder('-'),

                                        TextEntry::make('internal_code')
                                            ->label('Código Interno')
                                            ->placeholder('-'),
                                    ])
                                    ->columns(2),
                                Section::make('Fechas y Estado')
                                    ->schema([
                                        TextEntry::make('purchase_date')
                                            ->label('Fecha de Compra')
                                            ->date('d M Y')
                                            ->placeholder('-'),

                                        TextEntry::make('damage_date')
                                            ->label('Fecha del Daño')
                                            ->date('d M Y')
                                            ->placeholder('-'),

                                        TextEntry::make('status')
                                            ->label('Estado')
                                            ->badge()
                                            ->formatStateUsing(fn(WarrantyRequestStatus $state): string => match ($state) {
                                                WarrantyRequestStatus::Pending => 'Pendiente',
                                                WarrantyRequestStatus::InReview => 'En Revisión',
                                                WarrantyRequestStatus::Approved => 'Aprobada',
                                                WarrantyRequestStatus::Rejected => 'Rechazada',
                                            })
                                            ->color(fn(WarrantyRequest $record) => match ($record->status) {
                                                WarrantyRequestStatus::Pending => 'gray',
                                                WarrantyRequestStatus::InReview => 'info',
                                                WarrantyRequestStatus::Approved => 'success',
                                                WarrantyRequestStatus::Rejected => 'danger',
                                            }),
                                    ])
                                    ->columns(3),
                                Section::make('Descripción de la Falla')
                                    ->schema([
                                        TextEntry::make('failure_description')
                                            ->label('')
                                            ->columnSpanFull()
                                            ->placeholder('No especificada'),
                                    ])
                                    ->collapsed(false),
                            ]),
                        Tab::make('Evidencias Adjuntas')
                            ->icon(Heroicon::PaperClip)
                            ->schema([
                                Section::make('Evidencias Adjuntas')
                                    ->description('Archivos cargados por el cliente.')
                                    ->schema([
                                        RepeatableEntry::make('attachments')
                                            ->hiddenLabel(true)
                                            ->schema([
                                                TextEntry::make('type')
                                                    ->hiddenLabel(true),
                                                ImageEntry::make('url')
                                                    ->hiddenLabel(true)
                                                    ->openUrlInNewTab()
                                                    ->alignCenter()
                                                    ->url(fn($state) => $state),
                                                TextEntry::make('url')
                                                    ->label('Abrir archivo')
                                                    ->formatStateUsing(fn($record) => 'Ver archivo')
                                                    ->icon('heroicon-m-arrow-top-right-on-square')
                                                    ->url(fn($state) => $state)
                                                    ->openUrlInNewTab(),
                                            ])

                                            ->grid(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsed(false),
                            ]),
                        Tab::make('Notas')
                            ->icon(Heroicon::ChatBubbleBottomCenterText)
                            ->schema([
                                Section::make('Bitácora de la solicitud')
                                    ->description('Registro de observaciones y acciones realizadas sobre esta garantía.')
                                    ->schema([
                                        RepeatableEntry::make('notes')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextEntry::make('user.name')
                                                    ->label('Registrado por')
                                                    ->icon('heroicon-m-user'),

                                                TextEntry::make('created_at')
                                                    ->label('Fecha')
                                                    ->dateTime('d M Y H:i')
                                                    ->icon('heroicon-m-clock'),

                                                TextEntry::make('note')
                                                    ->label('Observación')
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsed(false),
                            ])
                    ]),

            ])
            ->modalSubmitAction(false);
    }
}
