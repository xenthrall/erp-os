<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'city',
        'is_active',
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}