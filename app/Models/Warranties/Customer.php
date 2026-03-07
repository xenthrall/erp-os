<?php

namespace App\Models\Warranties;

use App\Enums\Warranties\CustomerType;
use App\Enums\Warranties\WarrantyRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'document_type',
        'document_number',
        'phone',
        'address',
        'customer_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'customer_type' => CustomerType::class, 
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


    //scope

    public function scopeWithWarrantyStatusCounts(Builder $query): Builder
    {
        return $query->withCount([
            'warrantyRequests',

            'warrantyRequests as warranties_pending_count' => function ($query) {
                $query->where('status', WarrantyRequestStatus::Pending);
            },

            'warrantyRequests as warranties_review_count' => function ($query) {
                $query->where('status', WarrantyRequestStatus::InReview);
            },

            'warrantyRequests as warranties_approved_count' => function ($query) {
                $query->where('status', WarrantyRequestStatus::Approved);
            },

            'warrantyRequests as warranties_rejected_count' => function ($query) {
                $query->where('status', WarrantyRequestStatus::Rejected);
            },
        ]);
    }
}
