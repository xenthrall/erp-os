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
        'has_commission_penalty',
        'commission_penalty_percentage',
        'is_active',
    ];

    public function errorReports()
    {
        return $this->hasMany(ErReport::class);
    }
}