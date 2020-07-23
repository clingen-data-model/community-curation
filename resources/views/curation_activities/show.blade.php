@extends('layouts.app')

@section('content')
    <curation-activity-detail :initial-group="{{$curationActivity}}"></curation-activity-detail>
@endsection