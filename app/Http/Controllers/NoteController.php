<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class NoteController extends Controller
{

    public function index()
    {
        $notes= Note::all();
        return response()->json([
            'success' => true,
            'messege' => 'All Notes ',
            'data' => $notes,
        ]);
    }

    public function store(Request $request)
    {
        $input= $request->all();
        $validator = Validator::make($input ,[
            'title' => 'required',
            'body' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'fails' => false,
                'messege' => 'Sorry, not stored,
                 Please, validate your data ',
                'data' => $validator->errors(),
            ]);
        }
        $user = Auth::user();
        $input['user_name'] = $user->name;
        $note = Note::create($input,[
            'user_id' => auth()->user()->id,
            'title' => $input['title'],
            'body' => $input['body'],
        ]);
        $note ->save();

        return response()->json([
            'success' => true,
            'messege' => 'Note added successfully ',
            'data' => $note,
        ]);
    }

    public function show($id)
    {
        $note =Note:: find($id);
        if (is_null($note)) {
            return response()->json([
                'fails' => false,
                'messege' => 'Sorry, not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'messege' => 'Note readed successfully',
            'data' => $note,
        ]);
    }

    public function update(Request $request, Note $note)
    {
        $input= $request->all();
        $validator = Validator::make($input ,[
            'title' => 'required',
            'body' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'fails' => false,
                'messege' => 'Sorry, not stored,
                 Please, validate your data ',
                'data' => $validator->errors(),
            ]);
        }
        $note -> title = $input['title'];
        $note -> body = $input['body'];
        $user = Auth::user();
        $input['user_name'] = $user->name;
        $note ->save();

        return response()->json([
            'success' => true,
            'messege' => 'Note updated successfully',
            'data' => $note,
        ]);
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json([
            'success' => true,
            'messege' => 'Note deleted successfully',
            'data' => $note,
        ]);
    }
}
