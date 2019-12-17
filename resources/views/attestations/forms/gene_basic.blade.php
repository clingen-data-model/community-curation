@extends('layouts.attestation_form')

@section('attestation')
    <gene-basic-form :attestation="{{$attestation}}"></gene-basic-form>
@endsection