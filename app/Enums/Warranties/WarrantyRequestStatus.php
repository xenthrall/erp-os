<?php

namespace App\Enums\Warranties;

enum WarrantyRequestStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}