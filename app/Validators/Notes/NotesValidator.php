<?php

namespace App\Validators\Notes;

use App\Helpers\Session;
use App\Models\Folder;
use Core\Validator;


class NotesValidator extends Validator
{
    protected array $rules = [
        'title' => '/.{3,255}$/i',
        'content' => '/.+$/i',
    ];

    protected array $errors = [
        'title' => 'Title should be more then 3 symbols but less then 255',
        'content' => 'Content should be more then 1 symbol'
    ];

    const REQUEST_RULES = [
        'folder_id' => FILTER_VALIDATE_INT,
        'title' => array(
            'filter' => 'is_string',
            'flags' => FILTER_CALLBACK
        ),
        'content' => array(
            'filter' => 'is_string',
            'flags' => FILTER_CALLBACK
        ),
        'users' => array(
            'filter' => FILTER_VALIDATE_INT,
            'flags' => FILTER_REQUIRE_ARRAY,
        ),
        'pinned' => FILTER_VALIDATE_BOOL,
        'completed' => FILTER_VALIDATE_BOOL
    ];

    public function validate(array $fields = []): bool
    {
        $result = [
            parent::validate($fields),
            $this->validateFolderId($fields['folder_id'])
        ];

        return !in_array(false, $result);
    }

    public function validateFolderId(int $folderId): bool
    {
        $result = (bool)Folder::select()
            ->where('id', '=', $folderId)
            //->whereIn('author_id', [Session::id(), 0])
            ->get();

        if (!$result) {
            $this->setError(
                'folder_id',
                'Folder does not exists or does not related to the current user'
            );
        }

        return $result;
    }
}
