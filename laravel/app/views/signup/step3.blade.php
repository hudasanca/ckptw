@extends('default')
@section('title')
Dashboard
@stop
@section('content')
<div class="box">
	<h1>Finished</h1>
	<p class="terang">Selamat! Akun anda sudah siap! :D</p>
</div>
<form id="skip" method="post" action="{{route('registration.skip')}}" style="text-align:right">
	<input class="btn btn-lg btn-primary" type="submit" name="action" value="Oke!">
</form>
@stop