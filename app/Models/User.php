<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\ER\ErReport;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Models\HR\Employee;
use App\Models\Warranties\Customer;
use App\Models\Warranties\WarrantyRequest;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles;
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Polimórfica
        'userable_id',
        'userable_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Relación Polimórfica: Obtiene el perfil del usuario (Employee o Customer)
     */
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    public function warrantyRequests()
    {
        return $this->hasMany(WarrantyRequest::class, 'user_id');
    }

    public function reportedErReports()
    {
        return $this->hasMany(ErReport::class, 'reporter_id');
    }


    //Acceso a panel erp
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'erp') {
            return $this->userable instanceof Employee;
        }

        return false;
    }

    /**
     * Devuelve la URL del panel correspondiente según el tipo de usuario.
     */
    public function getDashboardUrl(): string
    {
        return match (true) {
            $this->userable instanceof Employee => url('/interno'),
            $this->userable instanceof Customer => url('/cliente/dashboard'),
            default => route('error.cuenta'),
        };
    }
}
