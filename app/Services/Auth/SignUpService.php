<?php

namespace App\Services\Auth;

use App\Services\Users\CreateUserService;
use App\Validators\Auth\SignUpValidator;

class SignUpService
{
    public static function invoke($fields, SignUpValidator $validator ) : bool
    {
        if($validator->validate($fields) && CreateUserService::invoke($fields))
        {
            return true;
        }

        return false;
    }
}
