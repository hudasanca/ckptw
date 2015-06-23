@extends('default')
@section('title')
{{$user->name}} ({{'@'.$user->username}})
@stop
@section('content')
<div class="header-user">
	<!-- <img src="" class="full {{$user->blur==1 ? 'blur':''}}"> -->
	<div class="center-cropped full {{$user->blur==1 ? 'blur':''}}" style="background-image:url('{{URL::asset('assets/images/'.$user->header)}}')"></div>
	<div class="header-user-background">
	</div>
	<div class="navbar-ckptw-profile">
		<div class="btn-group">
			<ul>
				<li><a class="btn btn-lg btn-black" href="{{route('{username}.status.index',[$user->username])}}">Status</a></li>
				<li><a class="btn btn-lg btn-black" href="{{route('{username}.followers',[$user->username])}}">Followers</a></li>
			</ul>
		</div>
	</div>
	<div class="avatar">
		<div>
			<a href="{{route('user.show',[$user->username])}}">
				<div class="img-circle center-cropped" style="background-image:url('{{URL::asset('assets/images/'.$user->photo)}}');width:100px;height:100px;margin:auto;margin-bottom:10px">
					
				</div>
			</a>
			<span style="font-size:11pt;color:white;margin-top:20px;">{{'@'.$user->username}}</span>
			<h2 style="margin:0px;"><a style="color:white" href="{{route('user.show',[$user->username])}}">{{$user->name}}</a></h2>
		</div>
		<div id="bio">
			<span>"{{$user->bio}}"</span>
		</div>
		<div class="btn-group" style="margin-top:20px">
			<a role="button" class="btn btn-lg btn-black" href="{{route('{username}.followers',[$user->username])}}">{{count($followers)}} followers</a>
		@if(Auth::check())
		@if($user->id!=Auth::id())
			@if(!$user->followed)
			<a role="button" class="btn btn-lg btn-black" href="{{route('follow',[$user->username])}}">Follow</a>
			@else
			<a role="button" class="btn btn-lg btn-black" href="{{route('unfollow',[$user->username])}}">Unfollow</a>
			@endif
		@endif
		@endif
		</div>
	</div>	
</div>
<script>
// var tinggi = $(window).height();
// var lebar = $(window).width();
// var left = (lebar-(lebar*25/100))/2+(lebar*25/100)-($('.avatar').width()/2);

// if ($('#leftbar-outer').css('display')=='block') {
// 	$('.avatar').animate({
// 		'top':tinggi/2-($('.avatar').height()/2),
// 		'opacity':'1'
// 	},1000);

// }
// else{
// 	left = ($(window).width()/2)-($('.avatar').width()/2);
// }
// $('.avatar').css({
// 	'left': left
// })

</script>
@stop