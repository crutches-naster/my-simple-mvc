<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use App\Services\Folders\FolderService;
use App\Services\Notes\NoteService;
use App\Validators\Notes\NotesValidator;
use Core\Controller;

class NotesController extends Controller
{
    public function show(int $id)
    {
        view('notes/show', ['note' => Note::find($id)]);
    }

    public function create()
    {
        $users = User::select()->where('id', '!=', Session::id())->get();

        view('notes/create', ['folders' => FolderService::getUserFolders(Session::id()), 'users' => $users]);
    }

    public function store()
    {
        $fields = filter_input_array(INPUT_POST, NotesValidator::REQUEST_RULES, false);
        $validator = new NotesValidator();

        if (NoteService::create($validator, $fields)) {
            Session::notify('success', 'Note was created!');
            redirect("folders/{$fields['folder_id']}");
        }

        view('notes/create', $this->getErrors($fields, $validator));

    }

    public function edit(int $id)
    {
        //ToDo implement
    }

    public function update(int $id)
    {
        //ToDo implement
    }

    public function destroy(int $id)
    {
        Note::destroy($id);
        Session::notify('success', 'Note was deleted');

        //ToDo just reload page
        redirect('dashboard');
    }
}
