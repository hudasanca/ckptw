@extends('default')
@section('title')
Discover
@stop
@section('content')
<div class="content-inner">
	<h2>Discover</h2>
	<div class="box">
		<h3>Trendings</h3>
		<ul>
		@foreach($trendings as $trending)
			<li><a class="trending" href="{{route('discover.search',['q'=>$trending->hashtag])}}">#{{$trending->hashtag}}</a></li>
		@endforeach
		</ul>
	</div>
	@if(Auth::check())
		@if(count($recentPeople)>0)
		<div class="box">
			<h3>People recently contacted</h3>
			<div class="row">
			@foreach($recentPeople as $key)
				@include('templates.userSummary',['user'=>$key])
			@endforeach
			</div>
		</div>
		@endif
	@endif
	<div class="box">
		<h3>Popular User</h3>
		<div class="row">
			@foreach($popularUser as $key)
				@include('templates.userSummary',['user'=>$key])
			@endforeach
		</div>
	</div>
	<div class="text-right">
		<a href="" role="button" class="btn btn-lg btn-default">More popular users</a>
	</div>
</div>
@stop