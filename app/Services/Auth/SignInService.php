<?php

namespace App\Services\Auth;

use App\Helpers\Session;
use App\Models\User;
use App\Validators\Auth\SignInValidator;

class SignInService
{
    public static function invoke($fields, SignInValidator $validator)
    {
        if ($validator->validate($fields)) {

            $user = User::findBy('email', $fields['email']);

            if ( $validator->verifyPassword( $fields['password'], $user->password)) {
                Session::setUserData($user->id, [  'logged_in' => true, 'email' => $user->email] );
                return true;
            }
        }

        return false;
    }
}
