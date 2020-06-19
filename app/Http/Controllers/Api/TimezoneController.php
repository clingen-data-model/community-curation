<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimezoneController extends Controller
{
    public function index()
    {
        return ['data' => timezone_identifiers_list()];
    }
}
