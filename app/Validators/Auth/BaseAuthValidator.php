<?php

namespace App\Validators\Auth;

use App\Models\User;
use Core\Validator;

class BaseAuthValidator extends Validator
{
    public function checkEmailExists(string $email): bool
    {
        $result = (bool) User::findBy('email', $email);

        if ( $result ) {
            $this->setError('email', 'This email already taken!');
        }

        return $result;
    }
}
