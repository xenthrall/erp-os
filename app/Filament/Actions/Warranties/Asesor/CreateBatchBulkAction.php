<?php

namespace App\Filament\Actions\Warranties\Asesor;

use App\Enums\Warranties\WarrantyBatchStatus;
use App\Enums\Warranties\WarrantyRequestStatus;
use App\Models\Warranties\WarrantyBatch;
use App\Models\Warranties\WarrantyBatchItem;
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
            ->modalDescription('Se creará automáticamente un lote y se agruparán las solicitudes seleccionadas con su cantidad restante disponible.')
            ->modalSubmitActionLabel('Crear lote')
            ->modalWidth('md')

            ->requiresConfirmation()

            ->action(function (Collection $records, BulkAction $action): void {

                // 1. Validar que todas estén en estado Aprobado
                $invalidStatus = $records->filter(
                    fn($record) => $record->status !== WarrantyRequestStatus::Approved
                );

                if ($invalidStatus->isNotEmpty()) {
                    Notification::make()
                        ->title('Error de estado')
                        ->body('Solo se pueden agrupar solicitudes que tengan el estado Aprobado.')
                        ->danger()
                        ->send();

                    $action->halt(); // halt() detiene la ejecución limpiamente en Filament V3
                }

                // 2. Validar que la cantidad no supere la disponible
                // Cargamos la suma de las cantidades ya asignadas en la base de datos
                $records->loadSum('batchItems', 'quantity_assigned');

                foreach ($records as $record) {
                    $assigned = $record->batch_items_sum_quantity_assigned ?? 0;
                    $available = $record->quantity - $assigned;

                    // Si ya no le queda cantidad por asignar, lanzamos el error
                    if ($available <= 0) {
                        Notification::make()
                            ->title('Cantidad superada')
                            ->body("La solicitud del modelo {$record->model} ya no tiene cantidad disponible para asignar.")
                            ->danger()
                            ->send();

                        $action->cancel();
                    }

                    // Guardamos temporalmente la cantidad disponible en el objeto 
                    // para usarla justo abajo en la transacción
                    $record->qty_to_assign = $available;
                }

                // 3. Crear el lote y asignar items si todas las validaciones pasaron
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
                            // Usamos lo que calculamos, NO la cantidad total de la solicitud
                            'quantity_assigned' => $record->qty_to_assign, 
                        ]);
                    }
                });
            })
            ->after(function ($livewire) {
                $livewire->dispatch('warranty-batch-created');
            })
            ->successNotificationTitle('Lote creado y solicitudes agrupadas correctamente')
            ->deselectRecordsAfterCompletion();
    }
}