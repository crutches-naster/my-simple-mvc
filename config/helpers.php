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

function redirectBack(string $path = ''): void
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

function showInputError(string $key, array $errors = []): string
{
    return !empty($errors[$key])
        ? sprintf('<div class="mb-3 alert alert-danger" role="alert">%s</div>', $errors[$key])
        : '';
}

function notify() : void
{
    if (!empty($_SESSION['notify'])) {
        $template = '<div class="alert alert-%s" role="alert">%s</div>';
        echo sprintf($template, $_SESSION['notify']['type'], $_SESSION['notify']['message']);
        \App\Helpers\Session::flushNotify();
    }
}

function urlBack(): string
{
    return $_SERVER['HTTP_REFERER'] ?? url();
}
