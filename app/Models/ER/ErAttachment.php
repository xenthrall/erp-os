<?php

namespace App\Models\ER;

use Illuminate\Database\Eloquent\Model;
use App\Models\ER\ErReport;


class ErAttachment extends Model
{
    protected $fillable = [
        'er_report_id',
        'path',
    ];

    public function report()
    {
        return $this->belongsTo(ErReport::class, 'er_report_id');
    }
}