@extends('layouts.app')

@section('content')
    <dosage-basic-form :attestation="{{$attestation}}"></dosage-basic-form>
@endsection