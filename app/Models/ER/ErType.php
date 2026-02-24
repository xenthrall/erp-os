<?php

namespace App\Models\ER;

use Illuminate\Database\Eloquent\Model;
use App\Models\ER\ErReport;

class ErType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'severity',
        'is_active',
    ];

    public function reports()
    {
        return $this->hasMany(ErReport::class);
    }
}