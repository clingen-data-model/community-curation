@extends('layouts.app')

@section('content')
    <b-card title="Curation Activities">
        <curation-activity-list :initial-activities="{{$curationActivities}}"></curation-activity-list>
    </b-card>
@endsection