@extends('layouts.app')

@section('content')
    <volunteer-detail :id="{{$volunteerId}}"></volunteer-detail>
@endSection