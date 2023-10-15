<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('faq.index', ['faqs' => Faq::orderBy('lft')->get()]);
    }
}
