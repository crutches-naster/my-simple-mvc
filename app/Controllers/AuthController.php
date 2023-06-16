<?php

namespace App\Controllers;

use App\Services\Auth\SignInService;
use App\Services\Auth\SignOutService;
use App\Services\Auth\SignUpService;
use App\Services\Users\CreateUserService;
use App\Validators\Auth\SignInValidator;
use App\Validators\Auth\SignUpValidator;
use Core\Controller;

class AuthController extends Controller
{
    public function register()
    {
        view('auth/registration');
    }

    public function login()
    {
        view('auth/login');
    }

    public function signup()
    {
        $fields = filter_input_array(INPUT_POST, $_POST);
        $validator = new SignUpValidator();

        if( SignUpService::invoke($fields, $validator) )
        {
            redirect('login');
        }

        dd($validator->getErrors());
    }

    public function signin()
    {
        $fields = filter_input_array(INPUT_POST, $_POST);
        $validator = new SignInValidator();

        if ( SignInService::invoke($fields, $validator) ) {
            redirect('dashboard');
        }

        //ToDo return login view with errors display
        dd($validator->getErrors());
    }

    public function signout()
    {
        if( SignOutService::invoke() )
        {
            redirect('login');
        }
    }
}
