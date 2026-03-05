<?php

namespace App\Models\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_id',
        'user_id',
        'factory_id',
        'factory_sequence',
        'shipping_city',
        'shipping_address',
        'damage_date',
        'purchase_date',
        'invoice_number',
        'internal_code',
        'model',
        'quantity',
        'status',
        'failure_description',
    ];

    protected $casts = [
        'status' => WarrantyRequestStatus::class,
        'damage_date' => 'date',
        'purchase_date' => 'date',
    ];

    public function customer(): BelongsTo // customer_id
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function creator(): BelongsTo // user_id(quien creó)
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warrantyFactory(): BelongsTo
    {
        return $this->belongsTo(WarrantyFactory::class, 'factory_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(WarrantyNote::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(WarrantyAttachment::class);
    }

    public function batchItems(): HasMany
    {
        return $this->hasMany(WarrantyBatchItem::class);
    }



    //scope

    public function scopeWithCustomerSequence(Builder $query): Builder
    {
        return $query->selectRaw("
        warranty_requests.*,
        ROW_NUMBER() OVER (
            PARTITION BY customer_id
            ORDER BY created_at ASC, id ASC
        ) as customer_sequence
    ");
    }
}
