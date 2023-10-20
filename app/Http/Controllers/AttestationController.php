<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attestation;
use App\Contracts\AttestationFormResolver;
use App\Http\Requests\UpdateAttestationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Attestation $attestation)
    {
        if (! $request->user()->can('view', $attestation)) {
            abort(403);
        }

        return $attestation;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Attestation $attestation): View
    {
        if (! $request->user()->can('view', $attestation)) {
            abort(403);
        }

        $attestation->load('user', 'aptitude');
        $resolver = $this->attestationFormResolver;

        return view('attestations.attestation', compact('attestation', 'resolver'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Attestation  $attestation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttestationRequest $request, $id)
    {
        $attestation = Attestation::findOrFail($id);
        if (! $request->user()->can('update', $attestation)) {
            return abort(403);
        }

        $attestation->update([
            'signed_at' => Carbon::now(),
            'data' => $request->except('_method', '_token'),
        ]);

        $request->session()->flash('success', 'Attestation for '.$attestation->aptitude->name.' completed.');

        return redirect('/volunteers/'.$attestation->user_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attestation $attestation)
    {
    }
}
