<?php

namespace App\Filament\Actions\Warranties\Asesor;

use App\Enums\Warranties\WarrantyBatchStatus;
use App\Enums\Warranties\WarrantyRequestStatus;
use App\Models\Warranties\WarrantyBatch;
use App\Models\Warranties\WarrantyBatchItem;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CreateBatchBulkAction extends BulkAction
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'createBatch')
            ->label('Agrupar en lote')
            ->icon('heroicon-o-archive-box')
            ->color('primary')

            ->modalHeading('Crear lote de garantías')
            ->modalDescription('Se creará automáticamente un lote y se agruparán las solicitudes seleccionadas.')
            ->modalSubmitActionLabel('Crear lote')
            ->modalWidth('md')

            ->requiresConfirmation()

            ->action(function (Collection $records, Action $action): void {

                $invalid = $records->filter(
                    fn($record) =>
                    $record->status !== WarrantyRequestStatus::Approved
                );

                if ($invalid->isNotEmpty()) {
                    Notification::make()
                        ->title('Solo se pueden agrupar solicitudes aprobadas')
                        ->danger()
                        ->send();

                    $action->cancel();
                }

                DB::transaction(function () use ($records) {

                    $first = $records->first();

                    $batch = WarrantyBatch::create([
                        'customer_id' => $first->customer_id,
                        'status' => WarrantyBatchStatus::Draft,
                    ]);

                    foreach ($records as $record) {

                        WarrantyBatchItem::create([
                            'warranty_batch_id' => $batch->id,
                            'warranty_request_id' => $record->id,
                            'quantity_assigned' => $record->quantity,
                        ]);
                    }
                });
            })

            ->successNotificationTitle('Lote creado y solicitudes agrupadas correctamente')
            ->deselectRecordsAfterCompletion();
    }
}
