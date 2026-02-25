@extends('layouts.app')

@section('content')
    <div data-component="training-session-detail"
         data-id="{{ $trainingSession->id }}"></div>
@endsection
