<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    /**
     * Relación Inversa Polimórfica: Un cliente tiene unas credenciales de usuario
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
    
    public function warrantyRequests(): HasMany
    {
        return $this->hasMany(WarrantyRequest::class);
    }

    public function warrantyBatches(): HasMany
    {
        return $this->hasMany(WarrantyBatch::class);
    }
}
