<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyFactory extends Model
{
    protected $fillable = [
        'code',
        'name',
        'current_sequence',
    ];

    public function warrantiesRequests(): HasMany
    {
        return $this->hasMany(WarrantyRequest::class, 'warranty_factory_id');
    }
}
