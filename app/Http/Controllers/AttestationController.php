<?php

namespace App\Http\Controllers;

use App\Attestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contracts\AttestationFormResolver;

class AttestationController extends Controller
{
    protected $attestationFormResolver;

    public function __construct(AttestationFormResolver $attestationFormResolver)
    {
        $this->attestationFormResolver = $attestationFormResolver;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Attestation  $attestation
     * @return \Illuminate\Http\Response
     */
    public function show(Attestation $attestation)
    {
        if (!Auth::user()->can('view', $attestation)) {
            abort(403);
        }
        return $attestation;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attestation  $attestation
     * @return \Illuminate\Http\Response
     */
    public function edit(Attestation $attestation)
    {
        if (!Auth::user()->can('view', $attestation)) {
            abort(403);
        }
        $attestation->load('user', 'aptitude');

        return view($this->attestationFormResolver->resolve($attestation), compact('attestation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attestation  $attestation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attestation = Attestation::findOrFail($id);
        if (!Auth::user()->can('update', $attestation)) {
            return abort(403);
        }

        $attestation->update(['signed_at' => $request->date, 'data' => $request->all()]);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attestation  $attestation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attestation $attestation)
    {
        //
    }
}
