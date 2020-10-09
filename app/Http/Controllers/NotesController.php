<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Http\Requests\NoteDestroyRequest;
use Illuminate\Support\Facades\Gate;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Gate::authorize('create', 'App\Note');

        $lead_id = $request['lead_id'];
        $project_id = $request['project_id'];

        return view('notes.create', compact('lead_id', 'project_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteStoreRequest $request)
    {
        Gate::authorize('create', 'App\Note');

        $post = $request->validated();

        Note::createNote($post);

        $request->session()->flash('success', __('Note successfully created.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        Gate::authorize('update', $note);

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(NoteUpdateRequest $request, Note $note)
    {
        Gate::authorize('update', $note);

        $post = $request->validated();

        $note->updateNote($post);

        $request->session()->flash('success', __('Note successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(NoteDestroyRequest $request, Note $note)
    {
        Gate::authorize('delete', $note);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $note->delete();

        return Redirect::to(URL::previous())->with('success', __('Note successfully deleted.'));
    }
}
