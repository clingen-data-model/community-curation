@extends('layouts.attestation_form')

@section('attestation')
    <baseline-basic-form :attestation="{{$attestation}}"></baseline-basic-form>
@endsection