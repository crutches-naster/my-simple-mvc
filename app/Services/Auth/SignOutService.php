<?php

namespace App\Services\Auth;

use App\Helpers\Session;

class SignOutService
{
    public static function invoke() : bool
    {
        Session::destroy();
        return true;
    }
}
