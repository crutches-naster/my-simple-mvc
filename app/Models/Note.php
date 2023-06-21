<?php


namespace App\Models;

use App\Helpers\Session;
use Core\Enums\SqlOrderByEnum;
use Core\Model;
use Core\Traits\Queryable;

class Note extends Model
{
    protected static string|null $tableName = 'notes';

    public int $author_id, $folder_id;
    public bool $pinned, $completed;
    public string $title, $content, $created_at, $updated_at;

}
