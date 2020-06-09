<?php

namespace App\Http\Controllers;

use App\TrainingSession;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        return view('training_sessions.list');
    }

    public function show($id)
    {
        $trainingSession = TrainingSession::findOrFail($id);
        $trainingSession->load('topic');
        return view('training_sessions.detail', compact('trainingSession'));
    }
}
