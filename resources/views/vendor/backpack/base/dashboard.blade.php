@extends('backpack::layout')

@section('before_styles')
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
			{{ trans('backpack::base.dashboard') }}
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
			<li class="active">{{ trans('backpack::base.dashboard') }}</li>
		</ol>
    </section>
@endsection


@section('content')
    <div id="app">
	<div class="box">
		<div class="box-header with-border">
			<div class="box-title w-100">Demographic Metrics</div>
			<div class="box-body">
				<global-metrics ref="glabalmetrics"></global-metrics>
			</div>
		</div>
	</div>
	<div class="box">
		<div class="box-header with-border">
			<div class="box-title w-100">
				<button class="btn btn-sm btn-light border float-right" @click="$refs.activitymetrics.getMetrics()">
					<b-icon icon="arrow-clockwise"></b-icon>
				</button>
				Activity Metrics
			</div>
			<div class="box-body">
				<activity-metrics ref="activitymetrics"></activity-metrics>
			</div>
		</div>
	</div>
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
