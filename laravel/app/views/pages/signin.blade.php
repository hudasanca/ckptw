@extends('default')
@section('title')
Sign in
@stop
@section('content')
{{HTML::style('assets/css/full-form.css')}}
{{HTML::script('assets/js/full-form.js')}}
<h1 id="spesial">Sign in</h1>
<div class="full-form">
	{{Form::open(['url'=>route('auth')])}}
	{{Form::text('username',Input::old('username'),['autocomplete'=>'off','placeholder'=>'username','required'])}}
	{{Form::password('password',['placeholder'=>'password','required'])}}
	<div class="form-group text-right">
	{{Form::checkbox('remember','remember', false,['id'=>'remember','style'=>'display:none'])}}
	<label id="remember-check" class="normal" for="remember"><span id="span-checked"></span> Remember me</label>	
	<br>
	{{Form::submit('Sign in')}}
	</div>
	{{Form::close()}}
</div>
<script>
	var checkbox = document.getElementById("remember");
	if (checkbox.checked) {
		$('#span-checked').attr('class', 'glyphicon glyphicon-ok	');
	}
	else{
		$('#span-checked').attr('class', 'glyphicon glyphicon-unchecked');			
	}

	$('#remember-check').click(function(){
		ok();
	});

	function ok(){
		if (checkbox.checked) {
			$('#span-checked').attr('class', 'glyphicon glyphicon-unchecked');
		}
		else{
			$('#span-checked').attr('class', 'glyphicon glyphicon-ok');			
		}
	}
</script>
@stop