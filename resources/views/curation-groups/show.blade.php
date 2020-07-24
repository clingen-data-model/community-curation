@extends('layouts.app')

@section('content')
    <curation-group-detail :initial-group="{{$curationGroup}}">
    </curation-group-detail>
@endsection