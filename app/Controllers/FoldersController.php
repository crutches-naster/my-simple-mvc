<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Folder;
use App\Models\Note;
use App\Services\Folders\FolderService;
use App\Services\Notes\NoteService;
use App\Validators\Folders\CreateFolderValidator;
use Core\Controller;

class FoldersController extends Controller
{
    public function index()
    {
        $activeFolder = Folder::GENERAL_FOLDER_ID;

        $notes = NoteService::getByFolderId(Folder::GENERAL_FOLDER_ID);
        $folders = FolderService::getUserFolders( Session::id() );

        view('pages/dashboard', compact('notes', 'folders', 'activeFolder'));
    }

    public function show( int $id )
    {
        $activeFolder = $id;

        $notes = NoteService::getByFolderId( $id );
        $folders = FolderService::getUserFolders( Session::id() );

        view('pages/dashboard', compact('notes', 'folders', 'activeFolder'));
    }

    public function create()
    {
        view('folders/create');
    }

    public function edit(int $id)
    {
        $folder = Folder::find($id);
        view('folders/edit', compact('folder'));
    }

    public function store()
    {
        $fields = filter_input_array(INPUT_POST, $_POST);
        $validator = new CreateFolderValidator();

        if ( $validator->validate($fields) && $folderId = FolderService::createNewFolder($fields, Session::id()) ) {
            Session::notify('success', 'Folder was created');
            redirect("folders/{$folderId}");
        }

        Session::notify('danger', 'Oops smth went wrong');
        view('folders/create', $this->getErrors($fields, $validator));
    }

    public function update(int $id)
    {
        $fields = filter_input_array(INPUT_POST, $_POST);
        $validator = new CreateFolderValidator();

        $fields = array_merge($fields, [
            'id' => $id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ( $validator->validate($fields) && $folder = Folder::find($id)) {
            if ( $folder->update($fields) ) {
                Session::notify('success', 'Folder was updated');
                redirect("folders/{$id}");
            }
        }

        view('folders/edit', $this->getErrors( $fields, $validator) );
    }

    public function destroy(int $id)
    {
        Folder::destroy($id);

        Session::notify('success', 'Folder was deleted');

        redirect('dashboard');
    }

    public function before(string $action, array $params = []): bool
    {
        if (!Session::check()) {
            redirect('login');
        }

        if (in_array($action, ['update', 'destroy', 'edit']) && !empty($params['id']) &&  Folder::isGeneral($params['id'])) {
            Session::notify('danger', 'You can not remove or update General folder');
            redirectBack();
        }

        return parent::before($action);
    }
}
