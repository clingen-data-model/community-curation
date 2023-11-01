@extends(backpack_view('blank'))

@section('before_styles')
    @vite('resources/js/app.js')
    <style>
        body {
          font-size: 14px !important;
        }
    </style>
@endsection

@section('content')
    <div id="app">
	<div class="box">
		<div class="box-header with-border">
			<div class="box-title w-100 mb-2">Demographic Metrics</div>
			<div class="box-body">
				<global-metrics ref="glabalmetrics"></global-metrics>
			</div>
		</div>
	</div>
	<hr>
	<div class="box">
		<div class="box-header with-border">
			<div class="box-title w-100 mb-2">
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
	<hr>
    <row>
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border mb-2">
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
