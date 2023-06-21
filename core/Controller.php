<?php

namespace Core;

class Controller
{
    public function before(string $action) : bool
    {
        return true;
    }

    public function after(string $action) : void
    {}

    protected function getErrors(array $fields, Validator $validator, $errors = []): array
    {
        return [
            'fields' => $fields,
            'errors' => array_merge($validator->getErrors(), $errors)
        ];
    }
}
