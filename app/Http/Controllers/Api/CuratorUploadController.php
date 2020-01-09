<?php

namespace App\Http\Controllers\Api;

use App\Upload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UploadResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateUploadRequest;
use App\Http\Requests\CuratorUploadIndexRequest;

class CuratorUploadController extends Controller
{
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUploadRequest $request)
    {
        if (!Auth::user()->can('create', Upload::class)) {
            return response(null, 403);
        }

        if (Auth::user()->id !== $request->user_id && !Auth::user()->can('createForOthers', Upload::class)) {
            return response(null, 403);
        }

        $path = $request->file->store('public/curator_uploads');

        $originalFileName = $request->file->getClientOriginalName();
        
        $upload = Upload::create([
            'user_id' => $request->user_id,
            'name' => $request->name ?? $originalFileName,
            'file_path' => $path,
            'upload_category_id' => $request->upload_category_id,
            'notes' => $request->notes
        ]);
        $upload->load('category');
        
        return new UploadResource($upload);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $upload = Upload::findOrFail($id);
        $upload->load('category');
        
        if (Auth::user()->id !== $upload->user_id && !Auth::user()->can('list uploads')) {
            return response('', 403);
        }

        return new UploadResource($upload);
    }

    public function getFile($id)
    {
        $upload = Upload::findOrFail($id);
        $upload->load('category');
        
        if (!Auth::user()->can('view', $upload)) {
            return response('', 403);
        }

        if (!file_exists(storage_path('app/'.$upload->file_path))) {
            return response(null, 404);
        }

        return Storage::download($upload->file_path);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $upload = Upload::find($id);
        if (!Auth::user()->can('update', $upload)) {
            return response(null, 403);
        }

        $upload->update($request->except('user_id'));
        $upload->load('category');

        return new UploadResource($upload);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $upload = Upload::find($id);
        if (!Auth::user()->can('delete', $upload)) {
            return response(null, 403);
        }

        $upload->delete();

        return response(null, 204);
    }
}
