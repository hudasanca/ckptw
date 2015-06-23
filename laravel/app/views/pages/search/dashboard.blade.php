@extends('default')
@section('title')
Search Result | {{Input::get('q')}}
@stop
@section('content')
<div class="content-inner">
	@if(!Request::ajax())
		<div class="visible-xs">
			{{Form::open(['method'=>'get','autocomplete'=>'off','style'=>'height:100%','url'=>route('discover.search')])}}
			{{Form::text('q','',['style'=>'height:100%','class'=>'form-control','placeholder'=>'search people, status, etc'])}}
			{{Form::close()}}
		</div>
	<div class="box">
		<h2>Search Result</h2>
	 	<h3>Users</h3>
	 	<div class="row">
		@foreach($users as $user)
			@include('templates.userSummary',['user'=>$user])
		@endforeach
	 	</div>
	</div>
	<div class="box" style="background:rgba(255,255,255,0.3);border:1px solid rgba(0,0,0,0.1)">
		<h3>Statuses</h3>
	@endif
		@foreach($statuses as $key)
			@include('templates.status',['status'=>$key])
		@endforeach
		@if(!Request::ajax())
		<button id="load-more-status-button" class="btn btn-lg status box visible-lg visible-md visible-sm" style="padding:20px;">Load more status</button>
		<div class="visible-xs">
		{{$statuses->links()}}
		</div>
		<div id="down-arrow-animation" style="text-align:center;display:none">
			<span class="glyphicon glyphicon-chevron-down"></span>
		</div>
		<script>
			var tinggi = $(window).height();
			var lebar = $(window).width();
			var left = (lebar-(lebar*25/100))/2+(lebar*25/100)-($('#down-arrow-animation').width()/2);

			if ($('#leftbar-outer').css('display')=='block') {
				$('#down-arrow-animation').css({
					'left':left,
					'display':'block'
				})
				$('#down-arrow-animation').animate({
					'top':$(window).height()-$('#down-arrow-animation').height()/2,
					'opacity':0
				}, 1000, function(){
					$('#down-arrow-animation').hide()
				});
			}
		</script>
		@endif
	</div>
</div>
@stop