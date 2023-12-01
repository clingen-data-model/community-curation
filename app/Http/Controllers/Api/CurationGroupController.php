<?php

namespace App\Http\Controllers\Api;

use App\CurationGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurationGroupResource;
use App\Services\Search\CurationGroupSearchService;
use Illuminate\Http\Request;

class CurationGroupController extends Controller
{
    protected $searchService;

    public function __construct(CurationGroupSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $groups = CurationGroup::orderBy('name')->paginate();
        $query = $this->searchService->buildQuery($request->all());
        if ($request->page) {
            $groups = $query->paginate();
        }

        $groups = $query->get();

        return CurationGroupResource::collection($groups);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new CurationGroupResource(CurationGroup::find($id));
    }
}
