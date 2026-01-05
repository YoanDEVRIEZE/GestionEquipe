<?php

namespace App\Enum;

enum RolesEnum: string
{
    case ROLE_ADMIN = 'ROLE_USER';
    case ROLE_MANAGER = 'ROLE_MANAGER';
    case ROLE_USER = 'ROLE_ADMIN';

    public function label(): string
    {
        return match ($this) {
            self::ROLE_USER => 'Utilisateur',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_ADMIN => 'Administrateur',
        };
    }
}
