@extends('layouts.app')

@section('content')
    <form class="sirs-survey" method="POST" action="/attestations/{{$attestation->id}}">
        {{csrf_field()}}
        {{method_field('PUT')}}
        @yield('attestation')
    </form>
@endsection