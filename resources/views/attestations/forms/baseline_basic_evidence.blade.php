@extends('layouts.attestation_form')

@section('attestation')
    <baseline-genetic-form :attestation="{{$attestation}}"></baseline-genetic-form>
@endsection