<?php

namespace App\Http\Controllers;

use App\CurationGroup;
use Illuminate\Http\Request;
use App\Services\Search\VolunteerSearchService;

class CurationGroupController extends Controller
{
    protected $volunteerSearch;

    public function __construct(VolunteerSearchService $volunteerSearch)
    {
        $this->volunteerSearch = $volunteerSearch;
    }

    public function index()
    {
        return view('curation-groups.index');
    }

    public function show($id)
    {
        return view('curation-groups.show', compact('id'));
    }
}
