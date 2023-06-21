<?php

namespace App\Validators\Folders;

use Core\Validator;

class CreateFolderValidator extends Validator
{
    protected array $rules = [
        'title' => '/.{3,255}$/i',
    ];

    protected array $errors = [
        'title' => 'Title should be more then 3 symbols but less then 255'
    ];
}
