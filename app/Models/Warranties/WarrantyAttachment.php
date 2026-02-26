<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;

class WarrantyAttachment extends Model
{
    protected $fillable = [
        'warranty_request_id',
        'file_path',
    ];

    public function warrantyRequest()
    {
        return $this->belongsTo(WarrantyRequest::class);
    }
}
