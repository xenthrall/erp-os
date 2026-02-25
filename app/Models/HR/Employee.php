<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

//importar report
use App\Models\ER\ErReport;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'full_name',
        'document_number',
        'position',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function errorReports()
    {
        return $this->hasMany(ErReport::class);
    }

}