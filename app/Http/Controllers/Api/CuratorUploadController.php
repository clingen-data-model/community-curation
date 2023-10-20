<?php

namespace App\Http\Controllers\Api;

use App\Actions\DocumentCreate;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUploadRequest;
use App\Http\Requests\CuratorUploadIndexRequest;
use App\Http\Resources\UploadResource;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CuratorUploadController extends Controller
{
    public function __construct(private DocumentCreate $documentCreate)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CuratorUploadIndexRequest $request)
    {
        $validated = $request->validated();
        $query = Upload::query();

        if ($request->has('where')) {
            foreach ($validated['where'] as $key => $val) {
                $query->where($key, $val);
            }
        }

        if ($request->has('sort')) {
            $field = $validated->sort['field'] ?? 'name';
            $dir = $validated->sort['dir'] ?? 'asc';
            $query->orderBy($field, $dir);
        }

        if ($request->has('with')) {
            $query->with($validated['with']);
        }

        if ($request->has('with_deleted')) {
            $query->withTrashed();
        }

        $uploads = $query->get();

        return UploadResource::collection($uploads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUploadRequest $request)
    {
        if (! $request->user()->can('create', Upload::class)) {
            return response()->json(['error' => 'You do not have permission to create a document.'], 403);
        }

        if ($request->user()->id !== (int) $request->user_id
            && ! $request->user()->can('createForOthers', Upload::class)
        ) {
            return response()->json(['error' => 'You do not have permission to create a document for another user.'], 403);
        }

        $data = $request->only(['user_id', 'upload_category_id', 'name', 'notes']);
        $data['filepath'] = $request->file->store('public/curator_uploads');
        $data['originalFileName'] = $request->file->getClientOriginalName();

        $upload = $this->documentCreate->handle(...$data);
        $upload->load('category');

        return new UploadResource($upload);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $upload = Upload::findOrFail($id);
        $upload->load('category');

        if ($request->user()->id !== $upload->user_id && ! $request->user()->can('list uploads')) {
            return response()->noContent(403);
        }

        return new UploadResource($upload);
    }

    public function getFile(Request $request, $id)
    {
        $upload = Upload::findOrFail($id);
        $upload->load('category');

        if (! $request->user()->can('view', $upload)) {
            return response()->noContent(403);
        }

        if (! file_exists(storage_path('app/'.$upload->file_path))) {
            return response()->noContent(404);
        }

        return Storage::download($upload->file_path);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $upload = Upload::find($id);
        if (! $request->user()->can('update', $upload)) {
            return response()->noContent(403);
        }

        $upload->update($request->except('user_id'));
        $upload->load('category');

        return new UploadResource($upload);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): Response
    {
        $upload = Upload::find($id);
        if (! $request->user()->can('delete', $upload)) {
            return response()->noContent(403);
        }

        $upload->delete();

        return response()->noContent();
    }
}
