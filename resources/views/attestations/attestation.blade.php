@extends('layouts.attestation_form')

@section('content')

    @if ($attestation->signed_at)
        <b-card class="lead">
            <p>This attestation has already been completed.</p>
            @if(Auth::user()->id == $attestation->user_id)
                <a href="/">Go back home</a>
            @else
                <a href="/volunteers/{{$attestation->user_id}}">Back to volunteer</a>
            @endif
        </b-card>
    @else
        @include($resolver->resolve($attestation), compact('attestation'))
    @endif

@endsection