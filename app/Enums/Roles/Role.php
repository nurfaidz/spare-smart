<?php

namespace App\Enums\Roles;

enum Role: string
{
    case Superadmin = 'superadmin';
    case Admin = 'admin';
}
