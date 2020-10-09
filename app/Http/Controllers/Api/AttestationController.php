<?php

namespace App\Http\Controllers\Api;

use App\Attestation;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttestationResource;
use App\User;
use Illuminate\Http\Request;

class AttestationController extends Controller
{
    public function index(Request $request)
    {
        return AttestationResource::collection(Attestation::all());
    }

    public function volunteer(Request $request, $id)
    {
        $volunteer = User::findOrFail($id);

        return $volunteer->attestations;
    }
}
