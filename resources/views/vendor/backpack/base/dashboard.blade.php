@extends('backpack::layout')

@section('before_styles')
    <script src="{{mix('/js/manifest.js')}}"></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <style>
        body {
          font-size: 14px !important;
        }
    </style>
@endsection

@section('header')
    <section class="content-header">
		<h1>
			{{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
			<li class="active">{{ trans('backpack::base.dashboard') }}</li>
		</ol>
    </section>
@endsection


@section('content')
    <div id="app">
	{{-- <div class="box">
		<div class="box-header with-border">
			<div class="box-title">Activity Metrics</div>
			<div class="box-body">
				<activity-metrics></activity-metrics>
			</div>
		</div>
	</div> --}}
    <row>
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<div class="box-title">Logged in users</div>
				</div>
				<div class="box-body">
					<logged-in-users-list></logged-in-users-list>
				</div>
			</div>
		</div>
     </row>
  </div>
@endsection
