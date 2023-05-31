<?php


use Core\DB;

require_once dirname(__DIR__) . '/config/constants.php';
require_once BASE_DIR . "/vendor/autoload.php";

//ToDo add router functionality


try {
    if (!session_id()) {
        session_start();
    }

    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
    $dotenv->load();

    $connection = DB::connect();

    dd($connection);

}
catch (PDOException $exception) {
    dd("PDOException", $exception->getLine() . ':' . $exception->getMessage());
}
catch (Exception $exception) {
    dd("Exception", $exception->getMessage());
}
