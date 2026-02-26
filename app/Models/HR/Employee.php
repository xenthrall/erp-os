<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

//importar report
use App\Models\ER\ErReport;

class Employee extends Model
{
    protected $fillable = [
        'department_id',
        'first_name',
        'last_name',
        'document_type',
        'document_number',
        'phone',
        'birth_date',
        'hire_date',
        'position',
        'is_active',
    ];

    /**
     * Relación Inversa Polimórfica: Un empleado tiene unas credenciales de usuario
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function errorReports()
    {
        return $this->hasMany(ErReport::class);
    }

    // Atributo para obtener el nombre completo del empleado
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim("{$this->first_name} {$this->last_name}"),
        );
    }

    protected function fullNameWithDocument(): Attribute
    {
        return Attribute::make(
            get: fn() => trim(
                "{$this->first_name} {$this->last_name} - {$this->document_type} {$this->document_number}"
            ),
        );
    }
}
