@extends('layouts.attestation_form')

@section('attestation')
    <somatic-basic-form :attestation="{{$attestation}}"></somatic-basic-form>
@endsection