<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    public string $email, $password, $created_at;

    protected static string|null $tableName = 'users';
}
