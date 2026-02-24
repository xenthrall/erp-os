<?php

namespace App\Models\ER;

use Illuminate\Database\Eloquent\Model;
use App\Models\ER\ErType;
use App\Models\ER\ErNote;
use App\Models\ER\ErAttachment;
use App\Models\User;
use App\Models\HR\Employee;

class ErReport extends Model
{
    protected $fillable = [
        'er_type_id',
        'reporter_id',
        'employee_id',
        'status',
        'event_date',
        'description',
        'discount_amount',
    ];

    public function type()
    {
        return $this->belongsTo(ErType::class, 'er_type_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function notes()
    {
        return $this->hasMany(ErNote::class);
    }

    public function attachments()
    {
        return $this->hasMany(ErAttachment::class);
    }
}