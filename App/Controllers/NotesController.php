<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Note;
use Core\Validator;

class NotesController extends Controller
{
    public function index()
    {
        $notes = Note::all();

        view("notes.index", [
            "heading" => 'My Notes',
            "notes" => $notes
        ]);        
    }

    public function show($id)
    {
        $note = Note::findOrFail($id);

        authorize($note["user_id"] === auth("id"));

        view("notes.show", [
            "heading" => 'Note',
            "note" => $note
        ]);
    }

    public function create()
    {
        view("notes.create", ["heading" => 'Create Note']);
    }

    public function store()
    {
        if (validate(Note::$rules["store"])) {
            $data = Validator::validated();

            Note::create(array_merge($data, ["user_id" => auth("id")]));
        }

        redirect(route("notes.index"));
    }

    public function edit($id)
    {
        $note = Note::findOrFail($id);

        authorize($note["user_id"] === auth("id"));

        view("notes.edit", [
            "heading" => 'Edit Note',
            "note" => $note
        ]);
    }
    
    public function update($id)
    {
        $note = Note::findOrFail($id);

        authorize($note["user_id"] === auth("id"));

        if (validate(Note::$rules["update"])) {
            $data = Validator::validated();
            
            Note::update($note["id"], $data);
        }

        redirect(route("notes.index"));
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);

        authorize($note["user_id"] === auth("id"));

        Note::delete($note["id"]);

        redirect(route("notes.index"));
    }
}