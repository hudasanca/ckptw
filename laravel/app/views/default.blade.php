@if(!Request::ajax())
<!DOCTYPE html>
<html>
<head>
	<!-- 

	   $$   $$  $$$$$  $$  $$  $$$$$  $$$$$$$$  $$         $$
	 $$$$$$$$$ $$      $$ $$   $$  $$    $$      $$   $   $$
	  $$  $$   $$      $$$$    $$$$$     $$      $$  $$$  $$
	$$$$$$$$$  $$      $$ $$   $$        $$       $$$$ $$$$
	 $$  $$     $$$$$  $$  $$  $$        $$        $$   $$

	   $$   $$  $$$$$  $$  $$  $$$$$  $$$$$$$$  $$         $$
	 $$$$$$$$$ $$      $$ $$   $$  $$    $$      $$   $   $$
	  $$  $$   $$      $$$$    $$$$$     $$      $$  $$$  $$
	$$$$$$$$$  $$      $$ $$   $$        $$       $$$$ $$$$
	 $$  $$     $$$$$  $$  $$  $$        $$        $$   $$

	   $$   $$  $$$$$  $$  $$  $$$$$  $$$$$$$$  $$         $$
	 $$$$$$$$$ $$      $$ $$   $$  $$    $$      $$   $   $$
	  $$  $$   $$      $$$$    $$$$$     $$      $$  $$$  $$
	$$$$$$$$$  $$      $$ $$   $$        $$       $$$$ $$$$
	 $$  $$     $$$$$  $$  $$  $$        $$        $$   $$

	   $$   $$  $$$$$  $$  $$  $$$$$  $$$$$$$$  $$         $$
	 $$$$$$$$$ $$      $$ $$   $$  $$    $$      $$   $   $$
	  $$  $$   $$      $$$$    $$$$$     $$      $$  $$$  $$
	$$$$$$$$$  $$      $$ $$   $$        $$       $$$$ $$$$
	 $$  $$     $$$$$  $$  $$  $$        $$        $$   $$

	 -->
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script>
	var root = '{{route("root")."/"}}';
	var username = '{{Auth::check() ? Auth::user()->username : ''}}';
	@if(Route::currentRouteName()=='discover.search')
	var path = '{{route(Route::currentRouteName(),Input::all())}}';
	@else
	var path = '{{Request::url()}}';
	@endif
	var route = '{{Route::currentRouteName()}}';
	</script>
	{{HTML::style('assets/css/bootstrap.min.css')}}
	{{HTML::style('assets/css/default.css')}}
	{{HTML::script('assets/js/jquery.min.js')}}
	{{HTML::script('assets/js/bootstrap.min.js')}}
	{{HTML::script('assets/js/default.js')}}
	{{HTML::script('assets/js/push.js')}}
	{{HTML::script('assets/js/pull.js')}}
	<link rel="shortcut icon" href="{{URL::asset('assets/images/ckptw_logo_shadow.png')}}" type="image/x-icon" />
</head>
<body style="background:rgb(200,200,200)">
	<div class="container-fluid">
		<div class="row">
			@if(!(Route::currentRouteName()=="root"&&!Auth::check()))
			<div class="visible-xs">
				@include('templates.navbar')
			</div>
			@else
			<style>
			@media (max-width: 768px){
				#content{
					padding-top: 0;
					margin-top: 0;
				}
			}
			</style>
			@endif
			<div id="leftbar-outer" class="col-md-3 col-sm-3 visible-lg visible-md visible-sm">
				@include('templates.leftbar')
			</div>
			<div id="content" class="col-md-9 col-sm-9 col-xs-12">
				@include('templates.notification')
				@endif
				@yield('content')
				@if(!Request::ajax())
				<div id="footer">@yield('footer')</div>
			</div>
		</div>
	</div>
	@endif
	@if(!Request::ajax())
	@include('templates.forms.status')
	<div class="popup box" id="delete-pop-up">
		<p>Are you sure you want to delete this?</p>
		<div class="btn-group" style="margin:auto">
			<a act="no" id="delete-confirmation-button" class="btn btn-lg btn-danger">No</a>
			<a act="yes" id="delete-confirmation-button" class="btn btn-lg btn-danger">Yes</a>
		</div>
	</div>
	<div id="tirai"></div>
	<script>
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})
	</script>
</body>
</html>
@endif