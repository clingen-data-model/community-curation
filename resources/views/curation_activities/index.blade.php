@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header"><h4>Curation Activities</h4></div>
        <div class="card-body">
            <div data-component="curation-activity-list"
                 data-initial-activities="{{$curationActivities}}"></div>
        </div>
    </div>
@endsection
