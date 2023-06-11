<?php


use App\Models\Note;
use App\Models\User;
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

    $notes = Note::select()->whereNotNull('folder_id');
    d($notes->get());

    $notes = Note::select()->where('author_id', '=', 1)->orWhereNull('folder_id');
    d($notes->get());

    d(Note::create([
        'author_id' => rand(1,10),
        'folder_id' => rand(0, 15),
        'content' => 'created test'
    ]));

}
catch (PDOException $exception) {
    dd("PDOException", $exception->getLine() . ':' . $exception->getMessage());
}
catch (Exception $exception) {
    dd("Exception", $exception->getMessage());
}
