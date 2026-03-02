<?php

namespace App\Models\Warranties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class WarrantyAttachment extends Model
{
    protected $fillable = [
        'warranty_request_id',
        'path',
    ];

    protected $appends = [
        'size',
    ];

    protected static function booted(): void
    {
        static::deleting(function (WarrantyAttachment $attachment): void {
            if ($attachment->path) {
                Storage::disk('public')->delete($attachment->path);
            }
        });
    }

    public function getSizeAttribute(): ?int
    {
        if (! $this->path) {
            return null;
        }

        return Storage::disk('public')->size($this->path);
    }

    public function warrantyRequest(): BelongsTo
    {
        return $this->belongsTo(WarrantyRequest::class);
    }
}
