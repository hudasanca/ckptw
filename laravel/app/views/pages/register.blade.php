@extends('default')
@section('title')
Sign up
@stop
@section('content') 
{{HTML::style('assets/css/full-form.css')}}
<div class="full-form">
	<div class="progress-bar">
		
	</div>
	<h1 id="spesial">Sign up</h1>
	<p id="messages">Please fill out the field</p>
	{{Form::open(['url'=>route('user.store'),'id'=>'register-form'])}}
	{{$errors->first('name')}}
	<ul id="form-ul" lists='6'>
		<li tes='2' id="1" req="yes">
		{{Form::label('name','What\'s your name?')}}
		{{Form::text('name',Input::old('name'),array('id'=>'name','autocomplete'=>'off','placeholder'=>'Nurul Huda	'))}}
		</li>
		<li id="2" req="yes">
		{{Form::label('username','What\'s your username?')}}
		{{$errors->first('username')}}
		{{Form::text('username',Input::old('hudasanca'),array('id'=>'username','autocomplete'=>'off','placeholder'=>'username'))}}
		</li>
		<li id="3" req="yes">
		{{Form::label('email','What\'s your email?')}}
		{{$errors->first('email')}}
		{{Form::email('email',Input::old('email'),array('id'=>'email','autocomplete'=>'off','placeholder'=>'example@domain.com'))}}
		</li>
		<li id="4" req="yes">
		{{Form::label('password','What\'s your password?')}}
		{{$errors->first('password')}}
		{{Form::password('password',['id'=>'password','placeholder'=>'password'])}}
		</li>
		<li id="5" req="yes">
		{{Form::label('password','Retype password?')}}
		{{Form::password('password_confirmation',['id'=>'password_confirmation','placeholder'=>'password confirmation'])}}		
		</li>
	</ul>
	{{Form::close()}}
	<div id="result" style="display:none">
		{{Form::open(['url'=>route('user.store'),'id'=>'register-form-result'])}}
			<h3>Is this okay?</h3>
			{{Form::label('name','Name')}}
			{{Form::text('name',Input::old('name'),array('id'=>'name','autocomplete'=>'off','placeholder'=>'Nurul Huda'))}}
			{{Form::label('username','Username')}}
			{{$errors->first('username')}}
			{{Form::text('username',Input::old('hudasanca'),array('id'=>'username','autocomplete'=>'off','placeholder'=>'username'))}}
			{{Form::label('email','Email')}}
			{{$errors->first('email')}}
			{{Form::email('email',Input::old('email'),array('id'=>'email','autocomplete'=>'off','placeholder'=>'example@domain.com'))}}
			<div style="display:none"> 
			{{$errors->first('password')}}
			{{Form::password('password',['id'=>'password','placeholder'=>'password'])}}
			{{Form::label('password','Retype password?')}}
			{{Form::password('password_confirmation',['id'=>'password_confirmation','placeholder'=>'password confirmation'])}}		
			</div>
		{{Form::close()}}
	</div>
	<a href="#" class="btn btn-black" id="registerbtn" style="display:none">Submit</a>
	<a href="#" class="btn btn-black" id="back" style="display:none">Back</a>
	<a href="#" class="btn btn-black" id="continue">Continue</a>
</div>
{{HTML::script('assets/js/full-form.js')}}
@stop