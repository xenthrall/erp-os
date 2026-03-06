<?php

namespace App\Enums\Warranties;

enum WarrantyBatchStatus: string
{
    case Draft = 'draft';
    case Processing = 'processing';
    case Shipped = 'shipped';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Borrador',
            self::Processing => 'En preparación en bodega',
            self::Shipped => 'Enviado a fábrica',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Processing => 'warning',
            self::Shipped => 'success',
        };
    }

    public function canEdit(): bool
    {
        return $this === self::Draft;
    }
}