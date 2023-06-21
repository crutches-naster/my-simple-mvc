<?php

namespace App\Services\Notes;

use App\Helpers\Session;
use App\Models\Note;
use Core\Enums\SqlOrderByEnum;
use Core\Validator;

class NoteService
{
    static public function create(Validator $validator, array $fields = []): bool
    {
        if (!$validator->validate($fields)) {
            return false;
        }

        $sharedUsers = $fields['users'] ?? [];
        unset($fields['users']);

        $fields = static::prepareFields($fields);
        $noteId = Note::create($fields);

        return $noteId;
    }

    public static function getByFolderId(int $folderId): bool|array
    {
        return Note::select()
            ->where('author_id', '=', Session::id())
            ->andWhere('folder_id', '=', $folderId)
            ->orderBy('id', SqlOrderByEnum::DESC)
            ->get();
    }

    static protected function prepareFields(array $fields): array
    {
        $fields['author_id'] = Session::id();
        $fields['pinned'] = $fields['pinned'] ?? 0;
        $fields['completed'] = $fields['completed'] ?? 0;

        return $fields;
    }
}
