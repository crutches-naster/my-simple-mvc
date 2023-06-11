<?php


namespace App\Models;

use Core\Model;

class Note extends Model
{
    protected static string|null $tableName = 'notes';

    //ToDo types declaration
    public $author_id;
    public $folder_id;
    public $content;
    public $pinned;
    public $completed;
    public $created_at;
    public $updated_at;
}
