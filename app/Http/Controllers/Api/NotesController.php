<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteCreateRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Http\Resources\NotesResource;
use App\Note;
use App\Services\Search\NotesSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

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
     * @return \Illuminate\Http\Response
     */
    public function store(NoteCreateRequest $request)
    {
        $data = $request->all();
        $data['created_by_id'] = $request->user()->id;
        $note = Note::create($data);
        $note->load('creator');

        return new NotesResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $notes
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        return new NotesResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Note  $notes
     * @return \Illuminate\Http\Response
     */
    public function update(NoteUpdateRequest $request, Note $note)
    {
        $note->update($request->all());
        $note->load('creator');

        return new NotesResource($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $notes
     */
    public function destroy(Request $request, Note $note): Response
    {
        if (! $request->user()->can('delete notes')) {
            throw new UnauthorizedException('User does not have permission to delete notes.');
        }
        $note->delete();

        return response('successfully deleted');
    }
}
