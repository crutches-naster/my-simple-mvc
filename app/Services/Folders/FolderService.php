<?php

namespace App\Services\Folders;

use App\Helpers\Session;
use App\Models\Folder;

class FolderService
{
    public static function createNewFolder($fields, $userId) : int
    {
        return Folder::create(array_merge($fields, ['user_id' => $userId]));
    }

    public static function getUserFolders($userId, $generalFolderId = Folder::GENERAL_FOLDER_ID ): array
    {
        return Folder::select()
            ->where('user_id', '=', $userId )
            ->orWhere('id', '=', $generalFolderId )
            ->orderBy('id')
            ->get();
    }
}
