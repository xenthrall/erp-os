<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'document_type',
        'document_number',
        'phone',
        'address',
        'is_active',
    ];

    public function warrantyRequests(): HasMany
    {
        return $this->hasMany(WarrantyRequest::class);
    }
}
