<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case OWNER = 'owner';
    case TENANT = 'tenant';
}
