<?php

namespace App\Services\Users;

use App\Models\User;

class CreateUserService
{
    public static function invoke($fields)
    {
        unset( $fields['password_confirm'] );

        $fields['password'] = password_hash( $fields['password'], PASSWORD_BCRYPT );

        return User::create($fields);
    }
}
