@extends('default')
@section('title')
#ckptw
@stop
@section('content')
<div role="tab-panel">
	<ul id="tabs" class="nav nav-tabs">
		<li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
		<li role="presentation"><a href="#password" aria-controls="password" role="tab" data-toggle="tab">Password</a></li>
		<!-- <li role="presentation"><a href="#security" aria-controls="security" role="tab" data-toggle="tab">Security</a></li> -->
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active box" id="profile">
			<h2>Profile</h2>
			@include('templates.forms.profile')
		</div>
		<div role="tabpanel" class="tab-pane fade box" id="password">
			<h2>Password</h2>
			{{Form::open(['method'=>'put','url'=>route('setting.update')])}}
			{{Form::hidden('_form','password')}}
			<div class="form-group">
			{{Form::label('old password')}}
			{{Form::password('old_password',['placeholder'=>'old password','required','class'=>'form-control'])}}
			</div>
			<div class="form-group">
				{{Form::label('new password')}}
				{{Form::password('password',['placeholder'=>'new password','required','class'=>'form-control'])}}
			</div>
			<div class="form-group">
				{{Form::label('password confirmation')}}
				{{Form::password('password_confirmation',['placeholder'=>'retype password','required','class'=>'form-control'])}}
			</div>
			{{Form::submit('Update',['class'=>'btn btn-lg btn-primary'])}}
			{{Form::close()}}
		</div>
		<!-- <div role="tabpanel" class="tab-pane fade" id="security">
			<h2>Security and Privacy</h2>
		</div> -->
	</div>
</div>
@stop