@extends('default')
@section('title')
Dashboard
@stop
@section('content')
<div class="box status step">
@if($user->confirmed==0)
	@if(!Input::has('req'))
	<p class="terang">Akun anda akan siap setelah mengkonfirmasi email :D</p>
	<p>Tidak menerima email? Coba periksa folder spam anda.</p>
	<p>Masih tidak menerima email? Klik <a href="{{route(Route::currentRouteName(),['req'=>'why-i-dont-get-an-email'])}}">di sini</a>.</p>
	@else
	{{Form::open(['url'=>route('resend.email')])}}
	{{Form::label('Tulis email anda')}}
	{{Form::email('email','',['class'=>'form-control','placeholder'=>'email'])}}
	<br>
	{{Form::submit('Kirim ulang email konfirmasi',['class'=>'btn btn-lg btn-primary'])}}
	{{Form::close()}}
	@endif
@else
	<p class="terang">Selamat, {{$user->name}}! Akun anda telah dikonfirmasi. Profil anda akan siap setelah ini.<br>:D</p>
	<!-- Tombol ini memerlukan request POST jadi harus
	pake form -->
	<form style="text-align:right" id="skip" method="post" action="{{route('registration.skip')}}">
		<input class="btn btn-lg btn-primary" type="submit" name="action" value="Lanjutkan">
	</form>
@endif
</div>
@stop