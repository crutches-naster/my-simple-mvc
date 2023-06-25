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
    public bool $pinned, $completed, $shared = false;
    public string $author = 'nobody', $title, $content, $created_at, $updated_at;

    static public function byFolder(int $folderId)
    {
        return static::selectWithSharedField()
            ->join('shared_notes sn', 'notes.id', 'sn.note_id')
            ->where('author_id', '=', Session::id())
            ->andWhere('folder_id', '=', $folderId)
            ->groupBy(['notes.id'])
            ->orderBy([
                'notes.pinned' => SqlOrderByEnum::DESC,
                'notes.completed' => SqlOrderByEnum::ASC,
                'notes.id' => SqlOrderByEnum::DESC
            ])
            ->get();
    }

    static public function sharedNotes()
    {
        return Note::select(['notes.*', 'us.email as author'])
            ->join('shared_notes sn', 'sn.note_id', 'notes.id')
            ->join('users us', 'notes.author_id', 'us.id')
            ->where('sn.user_id', '=', Session::id())
            ->orderBy(['notes.id' => SqlOrderByEnum::DESC])
            ->get();
    }

    static protected function selectWithSharedField(): Model
    {
        return Note::select([
            'notes.*',
            'IF(sn.note_id IS NULL, 0, 1) as shared'
        ]);
    }

}
