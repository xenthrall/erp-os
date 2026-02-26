<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyNote extends Model
{
    protected $fillable = [
        'user_id',
        'warranty_request_id',
        'note'
    ];

    public function warrantyRequest(): BelongsTo
    {
        return $this->belongsTo(WarrantyRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
