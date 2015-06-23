@extends('default')
@section('title')
{{$user->name}} | Followers
@stop
@section('content')
@if(!Request::ajax())
<img src="{{URL::asset('assets/images/'.$user->header)}}" style="z-index:-2" class="full {{$user->blur==1 ? 'blur':''}}">
<div class="header-user-background" style="z-index:-1"></div>
<div id="followers-box" class="box row" style="background-color: transparent">
@endif
	@foreach($followers as $follower)
		@include('templates.userSummary',['user'=>$follower])
	@endforeach
@if(!Request::ajax())
</div>
<div>
<button id="load-more-status-button" class="btn btn-lg status box visible-lg visible-md visible-sm" style="padding:20px;">Load more users</button>
</div>
<div class="visible-xs">
{{$paginator->links()}}
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
@stop
@section('footer')
<div class="visible-xs">
{{Form::open(['method'=>'get','autocomplete'=>'off','style'=>'height:100%','url'=>route('discover.search')])}}
{{Form::text('q','',['style'=>'height:100%','class'=>'form-control','placeholder'=>'search people, status, etc'])}}
{{Form::close()}}
</div>
@stop