@extends('layouts.app')

@section('content')
    <volunteer-detail :id="{{$volunteerId}}" :initial-volunteer="{{$volunteerJson}}"></volunteer-detail>
@endSection