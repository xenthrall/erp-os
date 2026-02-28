<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Enums\Warranties\WarrantyRequestStatus;

class WarrantyRequest extends Model
{
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

    public function factory(): BelongsTo
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
}
