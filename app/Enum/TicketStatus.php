<?php

namespace App\Enum;

enum TicketStatus: string
{
    case REPORTED = 'reported';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
}
