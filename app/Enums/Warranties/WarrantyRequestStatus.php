<?php

namespace App\Enums\Warranties;

enum WarrantyRequestStatus: string
{
    case Pending = 'pending';
    case InReview = 'in_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
