<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Attestation;
use App\ExpertPanel;
use App\CurationActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\AssignVolunteerToAssignable;
use App\Http\Resources\AttestationResource;
use App\Http\Requests\AttestationUpdateRequest;
use App\Http\Requests\ActivityAttestationCreateRequest;

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
