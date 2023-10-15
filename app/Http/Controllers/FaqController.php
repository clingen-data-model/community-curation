<?php

namespace App\Http\Controllers;

use App\Faq;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('faq.index', ['faqs' => Faq::orderBy('lft')->get()]);
    }
}
