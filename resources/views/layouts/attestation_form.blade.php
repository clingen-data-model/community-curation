@extends('layouts.app')

@section('content')
    <form class="sirs-survey" method="POST" action="/attestations/{{$attestation->id}}">
        @csrf
        @method('PUT')
        @yield('attestation')
    </form>
@endsection