<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Attestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contracts\AttestationFormResolver;
use App\Http\Requests\UpdateAttestationRequest;

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
    public function update(UpdateAttestationRequest $request, $id)
    {
        $attestation = Attestation::findOrFail($id);
        if (!Auth::user()->can('update', $attestation)) {
            return abort(403);
        }

        $attestation->update([
            'signed_at' => Carbon::now(),
            'data' => $request->except('_method', '_token')]);

        session()->flash('success', 'Attestation for '.$attestation->aptitude->name.' completed.');

        return redirect('/volunteers/'.$attestation->user_id);
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
