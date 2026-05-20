<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID_TO_ESCROW = 'paid_to_escrow';
    case RELEASED_TO_OWNER = 'released_to_owner';
    case CANCELLED = 'cancelled';
}
