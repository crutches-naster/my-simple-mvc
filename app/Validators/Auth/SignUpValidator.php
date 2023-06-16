<?php

namespace App\Validators\Auth;

class SignUpValidator extends BaseAuthValidator
{
    protected array $rules = [
        'email' => '/^[a-zA-Z0-9.!#$%&\'*+\/\?^_`{|}~-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i',
        'password' => '/[a-zA-Z0-9.!#$%&\'*+\/\?^_`{|}~-]{8,}/',
    ];

    protected array $errors = [
        'email' => 'Email should be a valid email',
        'password' => 'Incorrect password',
    ];

    public function passwordConfirmation( string $pass, string $passConfirm ): bool
    {
        if ($pass !== $passConfirm) {
            $this->setError('password_confirmation', 'Passwords mismatch');
            return false;
        }

        return true;
    }

    public function validate(array $fields = []): bool
    {
        $result = [
            parent::validate($fields),
            $this->passwordConfirmation($fields['password'], $fields['password_confirm']),
            !$this->checkEmailExists($fields['email'])
        ];

        return !in_array(false, $result);
    }
}
