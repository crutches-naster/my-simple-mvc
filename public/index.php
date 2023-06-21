<?php

use App\Controllers\AuthController;
use App\Controllers\FoldersController;
use App\Controllers\NotesController;
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

    /**
     * AUTH SECTION
     * ToDo move routes to routes/web.php
     */

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

    Router::add(
        'auth/signup',
        [
            'controller' => AuthController::class,
            'action' => 'signup',
            'method' => 'POST'
        ]
    );

    Router::add(
        'auth/signin',
        [
            'controller' => AuthController::class,
            'action' => 'signin',
            'method' => 'POST'
        ]
    );

    Router::add(
        'auth/signout',
        [
            'controller' => AuthController::class,
            'action' => 'signout',
            'method' => 'POST'
        ]
    );

    /**
     * DASHBOARD SECTION
     */

    Router::add(
        'dashboard',
        [
            'controller' => FoldersController::class,
            'action' => 'index',
            'method' => 'GET'
        ]
    );

    /**
     *  FOLDERS SECTION
     */
    Router::add(
        'folders/{id:\d+}',
        [
            'controller' => FoldersController::class,
            'action' => 'show',
            'method' => 'GET'
        ]
    );

    Router::add(
        'folders/create',
        [
            'controller' => FoldersController::class,
            'action' => 'create',
            'method' => 'GET'
        ]
    );

    Router::add(
        'folders/store',
        [
            'controller' => FoldersController::class,
            'action' => 'store',
            'method' => 'POST'
        ]
    );

    Router::add(
        'folders/{id:\d+}/edit',
        [
            'controller' => FoldersController::class,
            'action' => 'edit',
            'method' => 'GET'
        ]
    );

    Router::add(
        'folders/{id:\d+}/update',
        [
            'controller' => FoldersController::class,
            'action' => 'update',
            'method' => 'POST'
        ]
    );

    Router::add(
        'folders/{id:\d+}/destroy',
        [
            'controller' => FoldersController::class,
            'action' => 'destroy',
            'method' => 'POST'
        ]
    );

    /*******************************************************************************************************************
     * NOTES SECTION
     ******************************************************************************************************************/


    Router::add(
        'notes/{id:\d+}',
        [
            'controller' => NotesController::class,
            'action' => 'show',
            'method' => 'GET'
        ]
    );

    Router::add(
        'notes/create',
        [
            'controller' => NotesController::class,
            'action' => 'create',
            'method' => 'GET'
        ]
    );

    Router::add(
        'notes/store',
        [
            'controller' => NotesController::class,
            'action' => 'store',
            'method' => 'POST'
        ]
    );

    Router::add(
        'notes/{id:\d+}/edit',
        [
            'controller' => NotesController::class,
            'action' => 'edit',
            'method' => 'GET'
        ]
    );

    Router::add(
        'notes/{id:\d+}/update',
        [
            'controller' => NotesController::class,
            'action' => 'update',
            'method' => 'GET'
        ]
    );

    Router::add(
        'notes/{id:\d+}/destroy',
        [
            'controller' => NotesController::class,
            'action' => 'destroy',
            'method' => 'POST'
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
