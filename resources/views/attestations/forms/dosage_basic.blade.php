@extends('layouts.attestation_form')

@section('attestation')
    <dosage-basic-form :attestation="{{$attestation}}"></dosage-basic-form>
@endsection