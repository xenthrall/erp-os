<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyBatch extends Model
{
    protected $fillable = [
        'customer_id',
        'status',
        'out_sequence',
        'servientrega_guide',
        'sent_at',
        'closed_at',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WarrantyBatchItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
