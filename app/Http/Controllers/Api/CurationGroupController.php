<?php

namespace App\Http\Controllers\Api;

use App\CurationGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\CurationGroupResource;
use App\Services\Search\CurationGroupSearchService;

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
        $page = $query->paginate();

        return CurationGroupResource::collection($page);
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
