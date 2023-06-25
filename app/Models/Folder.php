<?php

namespace App\Models;

use App\Helpers\Session;
use Core\Enums\SqlOrderByEnum;
use Core\Model;

class Folder extends Model
{
    protected static string|null $tableName = 'folders';

    const GENERAL_FOLDER_ID = 1;
    const SHARED_FOLDER_ID = 0;

    public int $user_id;
    public string $title, $created_at, $updated_at;

    static public function isGeneral(int $id): bool
    {
        return $id === static::GENERAL_FOLDER_ID;
    }

    static public function getUserFolders(): array
    {
        $folders = static::select()
            ->where('user_id', '=', Session::id())
            ->orWhere('id', '=', static::GENERAL_FOLDER_ID)
            ->orderBy(['id' => SqlOrderByEnum::ASC])
            ->get();

        return $folders;
    }

    static public function getUserFoldersWithShared(): array
    {
        $folders = static::getUserFolders();

        if (static::sharedNotesForUser()) {
            array_unshift($folders, static::buildSharedFolder());
        }

        return $folders;
    }

    static public function sharedNotesForUser(): bool
    {
        return (bool) SharedNote::select()
            ->where('user_id', '=', Session::id())
            ->get();
    }

    static protected function buildSharedFolder(): static
    {
        $sharedFolder = new static();
        $sharedFolder->id = static::SHARED_FOLDER_ID;
        $sharedFolder->title = 'Shared';
        $sharedFolder->user_id = 0;

        return $sharedFolder;
    }

}
