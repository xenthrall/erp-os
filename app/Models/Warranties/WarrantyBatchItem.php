<?php

namespace App\Models\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use Exception;
use Illuminate\Database\Eloquent\Model;

class WarrantyBatchItem extends Model
{
    protected $fillable = [
        'warranty_request_id',
        'warranty_batch_id',
        'quantity_assigned'
    ];

    protected static function booted()
    {
        static::saving(function (WarrantyBatchItem $item) {
            // Cargamos la solicitud de garantía relacionada
            $request = $item->request;

            if (!$request) {
                throw new Exception('La solicitud de garantía no existe.');
            }

            // 1. Validar que la solicitud esté APROBADA
            if ($request->status !== WarrantyRequestStatus::Approved) {
                throw new Exception('Solo se pueden agregar solicitudes con estado Aprobado.');
            }

            // 2. Validar que la solicitud se vincule SOLO UNA VEZ por lote
            // (Verificamos solo si es un registro nuevo, no si se está editando la cantidad)
            if (!$item->exists) {
                $alreadyInBatch = static::where('warranty_batch_id', $item->warranty_batch_id)
                    ->where('warranty_request_id', $item->warranty_request_id)
                    ->exists();

                if ($alreadyInBatch) {
                    throw new Exception('Esta solicitud ya se encuentra vinculada a este lote.');
                }
            }

            // 3. Validar que la cantidad no supere la disponible
            // Sumamos lo que ya se asignó en otros lotes (excluyendo este item si se está actualizando)
            $assignedInOtherBatches = static::where('warranty_request_id', $item->warranty_request_id)
                ->when($item->exists, fn ($query) => $query->where('id', '!=', $item->id))
                ->sum('quantity_assigned');

            $availableQuantity = $request->quantity - $assignedInOtherBatches;

            if ($item->quantity_assigned > $availableQuantity) {
                throw new Exception("La cantidad ingresada ({$item->quantity_assigned}) supera lo disponible. Solo quedan {$availableQuantity} unidades por asignar a lotes.");
            }
        });
    }

    public function request()
    {
        return $this->belongsTo(WarrantyRequest::class, 'warranty_request_id');
    }
    public function batch()
    {
        return $this->belongsTo(WarrantyBatch::class, 'warranty_batch_id');
    }
}
