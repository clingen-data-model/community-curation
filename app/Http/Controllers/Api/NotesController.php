<?php

namespace App\Http\Controllers\Api;

use App\Note;
use App\Notes;
use Illuminate\Http\Request;
use App\Http\Requests\NoteRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NotesResource;
use App\Services\Search\NotesSearchService;

class NotesController extends Controller
{
    protected $notesSearcService;

    public function __construct(NotesSearchService $notesSearcService)
    {
        $this->notesSearcService = $notesSearcService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return NotesResource::collection($this->notesSearcService->search($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $data = $request->all();
        $data['created_by_id'] = Auth::user()->id;
        $note = Note::create($data);

        return new NotesResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function show(Notes $note)
    {
        return new NotesResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notes $notes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notes $notes)
    {
        //
    }
}
