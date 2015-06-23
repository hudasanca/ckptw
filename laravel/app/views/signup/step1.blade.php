@extends('default')
@section('title')
Dashboard
@stop
@section('content')
<div class="box">
	<h1>Step 1</h1>
	<h2>Profile</h2>
	@include('templates.forms.profile')

	<!-- Tombol ini memerlukan request POST jadi harus
	pake form -->
</div>
<form id="skip" method="post" action="{{route('registration.skip')}}" style="text-align:right">
	<input class="btn btn-lg btn-primary" type="submit" name="action" value="skip">
</form>
@stop