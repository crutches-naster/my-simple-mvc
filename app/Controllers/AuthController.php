<?php

namespace App\Controllers;

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
}
