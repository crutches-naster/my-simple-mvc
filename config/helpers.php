<?php

use Config\Config;
use Core\View;
use JetBrains\PhpStorm\NoReturn;

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

#[NoReturn]
function redirect(string $path = ''): void
{
    header('Location: ' . url($path) );
    exit;
}

function currentLink(string $path): bool
{
    return trim($_SERVER['REQUEST_URI'], '/') === $path;
}
