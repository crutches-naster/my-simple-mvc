<?php

use Config\Config;
use Core\View;

function config(string $name, $default = null ): string|null
{
    return Config::get($name) ?? $default;
}

/**
 * @throws Exception
 */
function view(string $view, array $args = []) : void
{
    View::render($view, $args);
}

function url(string $path = '') : string
{
    return SITE_URL . '/' . $path;
}
