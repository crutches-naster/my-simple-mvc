<?php

namespace App\Services\Notes;

use App\Helpers\Session;
use App\Models\Folder;
use App\Models\Note;
use App\Models\SharedNote;
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

        if( !empty($sharedUsers) && $sharedUsers[0] )
        {
            foreach ($sharedUsers as $sharedUserId)
            {
                SharedNote::create([ 'user_id' => $sharedUserId, 'note_id' => $noteId ]);
            }
        }

        return $noteId;
    }

    static public function update(Validator $validator, Note $note, array $fields = [])
    {
        if (!$validator->validate($fields)) {
            return false;
        }

        $sharedUsers = $fields['users'] ?? [];
        unset($fields['users']);

        $fields = static::prepareFields($fields);

        if ( $note->update($fields) ) {

            if (!empty($sharedUsers)) {

                $removingShares = self::getSharedNotesToRemove( $note->id, $sharedUsers );

                if(!empty($removingShares))
                {
                    foreach ($removingShares as $removingShare)
                    {
                        $removingShare->remove();
                    }
                }

                $existingUsers = SharedNote::select(['user_id'])
                    ->where('note_id', '=', $note->id)
                    ->pluck('user_id');

                $usersToShare = array_diff($sharedUsers, $existingUsers);

                if(!empty($usersToShare))
                {
                    foreach ($usersToShare as $userId) {
                        SharedNote::create(['note_id' => $note->id, 'user_id' => $userId]);
                    }
                }
            }
        }

        return $note->id;
    }


    public static function getByFolderId( int $folderId ): bool|array
    {
        return $folderId == Folder::SHARED_FOLDER_ID
            ? Note::sharedNotes()
            : Note::byFolder($folderId);
    }

    private static function getSharedNotesToRemove(int $noteId, mixed $sharedUsers)
    {
        return SharedNote::select()
            ->where('note_id', '=', $noteId)
            ->whereNotIn('user_id', $sharedUsers)
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
