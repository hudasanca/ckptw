@extends('default')
@section('title')
Dashboard
@stop
@section('content')
<div class="box">
	<h1>Step 2</h1>
	<h2>Follow others</h2>
	<div class="row">
		@foreach($popularUser as $key)
			@include('templates.userSummary',['user'=>$key])
		@endforeach
	</div>
</div>
<form id="skip" method="post" action="{{route('registration.skip')}}" style="text-align:right">
	<input class="btn btn-lg btn-primary" type="submit" name="action" value="next">
</form>
@stop