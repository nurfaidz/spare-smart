<?php

namespace App\Filament\Pages;

use App\Enums\Roles\Role;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Database\Eloquent\Builder;

class Login extends BaseLogin
{
    /**
     * {@inheritDoc}
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => $data['email'],
            'password' => $data['password'],
            fn (Builder $query) => $query->whereRelation('roles', 'name', [Role::Superadmin->value, Role::Admin->value]),
        ];
    }
}
