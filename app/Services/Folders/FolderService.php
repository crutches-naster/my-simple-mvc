<?php

namespace App\Services\Folders;

use App\Helpers\Session;
use App\Models\Folder;
use Core\Enums\SqlOrderByEnum;

class FolderService
{
    public static function createNewFolder($fields, $userId) : int
    {
        return Folder::create(array_merge($fields, ['user_id' => $userId]));
    }

    public static function getUserFolders($userId, $generalFolderId = Folder::GENERAL_FOLDER_ID ): array
    {
        return Folder::getUserFoldersWithShared();
    }
}
