<?php

namespace App\Http\Controllers;

use App\ExpertPanel;
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
        $groups = ExpertPanel::all();
        return $groups;
    }
}
