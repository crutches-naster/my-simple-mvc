<?php

use App\Controllers\AuthController;
use App\Models\Note;
use App\Models\User;
use Core\DB;
use Core\Enums\HttpMethodsEnum;
use Core\Router;

require_once dirname(__DIR__) . '/config/constants.php';
require_once BASE_DIR . "/vendor/autoload.php";

try {
    if (!session_id()) {
        session_start();
    }

    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR );
    $dotenv->load();

    Router::add(
        'register',
        [
            'controller' => AuthController::class,
            'action' => 'register',
            'method' => 'GET'
        ]
    );

    Router::add(
        'login',
        [
            'controller' => AuthController::class,
            'action' => 'login',
            'method' => 'GET'
        ]
    );


    if (!preg_match('/assets/i', $_SERVER['REQUEST_URI'])) {
        Router::dispatch($_SERVER['REQUEST_URI']);
    }

}
catch (PDOException $exception) {
    dd("PDOException", $exception->getLine() . ':' . $exception->getMessage());
}
catch (Exception $exception) {
    dd("Exception", $exception->getMessage());
}
