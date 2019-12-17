@extends('layouts.attestation_form')

@section('attestation')
    <variant-basic-form :attestation="{{$attestation}}"></variant-basic-form>
@endsection