<?php

namespace App\Models\ER;

use Illuminate\Database\Eloquent\Model;
use App\Models\ER\ErReport;
use App\Models\User;

class ErNote extends Model
{
    protected $fillable = [
        'er_report_id',
        'user_id',
        'note',
    ];

    public function report()
    {
        return $this->belongsTo(ErReport::class, 'er_report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}