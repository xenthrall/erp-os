<?php

namespace App\Enums\Warranties;

enum CustomerType: string
{
    case Regular = 'regular';
    case Distributor = 'distributor';

    public function minWarrantyBatch(): int
    {
        return match($this) {
            self::Regular => 5,
            self::Distributor => 10,
        };
    }

    public function label(): ?string
    {
        return match ($this) {
            self::Regular => 'Cliente Regular',
            self::Distributor => 'Cliente Distribuidor',
        };
    }

    public function color(): string|array|null
    {
        return match ($this) {
            self::Regular => 'gray',    
            self::Distributor => 'warning', 
        };
    }
}