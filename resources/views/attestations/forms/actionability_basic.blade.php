@extends('layouts.attestation_form')

@section('attestation')
    <actionability-basic-form :attestation="{{$attestation}}"></actionability-basic-form>
@endsection