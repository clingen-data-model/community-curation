@extends('layouts.app')

@section('content')
    <training-session-detail :id="{{ $trainingSession->id }}"></training-session-detail>
@endsection