<?php

namespace App\Http\Controllers;

use App\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingController extends Controller
{
    public function index(Request $request): View
    {
        return view('training_sessions.list');
    }

    public function show($id): View
    {
        $trainingSession = TrainingSession::findOrFail($id);
        $trainingSession->load('topic');

        return view('training_sessions.detail', compact('trainingSession'));
    }
}
