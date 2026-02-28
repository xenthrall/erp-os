<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;

class WarrantyBatchItem extends Model
{
    protected $fillable = [
        'warranty_request_id',
        'warranty_batch_id',
        'quantity_assigned'
    ];

    public function request()
    {
        return $this->belongsTo(WarrantyRequest::class);
    }
    public function batch()
    {
        return $this->belongsTo(WarrantyBatch::class);
    }
}
