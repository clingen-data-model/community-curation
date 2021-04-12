@extends('errors.minimal')

@section('title', __('The '.{{config('app.name')}}.' is down for the moment.'))

@section('message')

<p>We're aware of the problem...</p>

<p>The hosting platform for the {{config('app.name')}} is experiencing problems that will prevent the site from working, so we've taken the site down for the moment.</p>

<p><small>This page will refresh every 30 seconds to check for status changes.</small></p>

<script>
    setTimeout(function () 
        console.log('refresh!')
        window.location = window.location
    }, 30000);
</script>
@endsection
