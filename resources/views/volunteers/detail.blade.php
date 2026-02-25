@extends('layouts.app')

@section('content')
    <div data-component="volunteer-detail"
         data-id="{{$volunteerId}}"
         data-initial-volunteer="{{$volunteerJson}}"></div>
@endSection
