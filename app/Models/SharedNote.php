<?php

namespace App\Models;

namespace App\Models;

use App\Helpers\Session;
use Core\DB;
use Core\Model;

class SharedNote extends Model
{
    protected static string|null $tableName = 'shared_notes';

    public int $note_id, $user_id;
    public string $created_at, $updated_at;

    public function remove() : bool
    {
        $query = "DELETE FROM " . static::$tableName . " WHERE note_id=:note_id and user_id=:user_id";
        $query = Db::connect()->prepare($query);
        $query->bindParam('note_id', $this->note_id );
        $query->bindParam('user_id', $this->user_id );

        return $query->execute();
    }

    public static function RemoveShare()
    {

    }

}
