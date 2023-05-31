<?php

use Config\Config;

function config(string $name, $default = null ): string|null
{
    return Config::get($name) ?? $default;
}
