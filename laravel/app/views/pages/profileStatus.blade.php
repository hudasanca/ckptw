@extends('default')
@section('title')
Status of {{$user->name}}
@stop
@section('content')
@if(!Request::ajax())
<img src="{{URL::asset('assets/images/'.$user->header)}}" style="z-index:-2" class="full {{$user->blur==1 ? 'blur':''}}">
<div class="header-user-background" style="z-index:-1"></div>
	@if(Auth::user()->confirmed==1)
		{{Form::open(['id'=>'status-form-mobile','class'=>'status visible-xs','url'=>route('{username}.status.store')])}}
		{{$errors->first('status')}}
		{{Form::textarea('status','',['placeholder'=>'status',Route::currentRouteName()=="{username}.status.edit"&&$status->user_id!=Auth::id() ? 'disabled' : 'enabled'])}}
		{{Form::hidden('photo','',['id'=>'photo'])}}
		{{Form::submit('post',['id'=>'post','class'=>'btn btn-lg btn-black'])}}
		{{Form::close()}}
	@endif
<div id="status"></div>
@endif
@foreach($statuses as $status)
@include('templates.status')
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
@stop
@section('footer')
<div class="bottom-profile-bar">
	<ul class="nav navbar-nav">
		<div class="navbar-header">
			<a href="{{route('user.show',[$user->username])}}" class="navbar-brand">
				<img class="img-circle" width="20px" height="20px" src="{{URL::asset('assets/images/'.$user->photo)}}">
			</a>
		</div>
		<li>
			<a href="{{route('user.show',[$user->username])}}">{{$user->name}} ({{'@'.$user->username}})</a>
		</li>
	</ul>
</div>
@stop
@section('footer')
<div class="visible-xs">
{{Form::open(['method'=>'get','autocomplete'=>'off','style'=>'height:100%','url'=>route('discover.search')])}}
{{Form::text('q','',['style'=>'height:100%','class'=>'form-control','placeholder'=>'search people, status, etc'])}}
{{Form::close()}}
</div>
@stop