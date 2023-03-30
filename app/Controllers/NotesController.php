<?php

namespace App\Controllers;

use App\Models\Note;
use Core\Controller;
use Core\Validator;

class NotesController extends Controller
{
    public function index()
    {
        $notes = Note::all();

        view("notes.index", [
            "heading" => 'My Notes',
            "module" => "notes",
            "notes" => $notes
        ]);        
    }

    public function show(int $id)
    {
        $note = Note::findOrFail($id);

        authorize($note->user_id === auth("id"));

        view("notes.show", [
            "heading" => 'Note',
            "module" => "notes",
            "note" => $note
        ]);
    }

    public function create()
    {
        view("notes.create", [
            "heading" => 'Create Note',
            "module" => "notes",
        ]);
    }

    public function store()
    {
        if (validate(Note::$rules["store"])) {
            $data = Validator::validated();

            Note::create(array_merge($data, ["user_id" => auth("id")]));
        }

        redirect(route("notes.index"));
    }

    public function edit(int $id)
    {
        $note = Note::findOrFail($id);

        authorize($note->user_id === auth("id"));

        view("notes.edit", [
            "heading" => 'Edit Note',
            "module" => "notes",
            "note" => $note
        ]);
    }

    public function update(int $id)
    {
        $note = Note::findOrFail($id);

        authorize($note->user_id === auth("id"));

        if (validate(Note::$rules["update"])) {
            $data = Validator::validated();
            
            $note->update($data);
        }

        redirect(route("notes.index"));
    }

    public function destroy(int $id)
    {
        $note = Note::findOrFail($id);

        authorize($note->user_id === auth("id"));

        $note->delete();

        redirect(route("notes.index"));
    }
}