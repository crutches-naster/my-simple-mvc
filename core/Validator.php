<?php

namespace Core;

class Validator
{
    protected array $rules = [], $errors = [];

    public function validate(array $fields = []): bool
    {
        foreach ($fields as $key => $value) {
            if (!empty($this->rules[$key]) && preg_match($this->rules[$key], $value)) {
                unset($this->errors[$key]);
            }
        }

        return empty($this->errors);
    }

    public function hasErrors() : bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setError(string $key, string $message)
    {
        $this->errors[$key] = $message;
    }
}
