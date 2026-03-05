<?php

namespace App\Observers\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use App\Models\Warranties\WarrantyRequest;
use Illuminate\Validation\ValidationException;

class WarrantyRequestObserver
{
    public function updating(WarrantyRequest $warrantyRequest): void
    {
        $originalStatus = $warrantyRequest->getOriginal('status');
        $newStatus = $warrantyRequest->status;

        /*
        |------------------------------------------------------------
        | 1. No permitir volver a Pending
        |------------------------------------------------------------
        */
        if ($newStatus === WarrantyRequestStatus::Pending && $originalStatus !== WarrantyRequestStatus::Pending) {
            throw ValidationException::withMessages([
                'status' => 'No se puede volver al estado pendiente.'
            ]);
        }

        /*
        |------------------------------------------------------------
        | 2. Approved o Rejected son estados finales
        |------------------------------------------------------------
        */
        if (in_array($originalStatus, [
            WarrantyRequestStatus::Approved,
            WarrantyRequestStatus::Rejected
        ])) {
            throw ValidationException::withMessages([
                'status' => 'Las solicitudes aprobadas o rechazadas no pueden modificarse.'
            ]);
        }

        /*
        |------------------------------------------------------------
        | 3. Si ya no está pendiente limitar campos editables
        |------------------------------------------------------------
        */
        if ($originalStatus !== WarrantyRequestStatus::Pending) {

            $allowed = [
                'status',
                'factory_id',
            ];

            $dirtyFields = array_keys($warrantyRequest->getDirty());
            $invalidChanges = array_diff($dirtyFields, $allowed);

            if (!empty($invalidChanges)) {
                throw ValidationException::withMessages([
                    'warranty_request' => 'Esta solicitud ya no puede modificarse. Solo se permite actualizar estado y fábrica.'
                ]);
            }
        }

        /*
        |------------------------------------------------------------
        | 4. Si pasa a Rejected -> factory_id debe ser null
        |------------------------------------------------------------
        */
        if ($newStatus === WarrantyRequestStatus::Rejected) {

            if (!is_null($warrantyRequest->factory_id)) {
                throw ValidationException::withMessages([
                    'factory_id' => 'Una solicitud rechazada no puede tener fábrica asignada.'
                ]);
            }
        }

        /*
        |------------------------------------------------------------
        | 5. Si pasa a Approved -> factory_id obligatorio
        |------------------------------------------------------------
        */
        if ($newStatus === WarrantyRequestStatus::Approved) {

            if (is_null($warrantyRequest->factory_id)) {
                throw ValidationException::withMessages([
                    'factory_id' => 'Para aprobar una solicitud es obligatorio asignar una fábrica.'
                ]);
            }
        }
    }

    public function deleting(WarrantyRequest $warrantyRequest): void
    {
        if ($warrantyRequest->status !== WarrantyRequestStatus::Pending) {
            throw ValidationException::withMessages([
                'warranty_request' => 'Solo se pueden eliminar solicitudes en estado pendiente.'
            ]);
        }

        $warrantyRequest->attachments()->get()->each->delete();
    }
}