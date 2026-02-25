@extends(backpack_view('blank'))

@section('before_styles')
    @vite(['resources/js/app.js'])
    <style>
        body {
          font-size: 14px !important;
        }
    </style>
@endsection

@section('content')
    <div id="app">
        <div data-component="admin-dashboard"></div>
    </div>
@endsection
