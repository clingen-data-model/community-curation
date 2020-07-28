@extends('layouts.app')

@section('content')
    <faq-list :faqs="{{$faqs}}"></faq-list>
@endsection