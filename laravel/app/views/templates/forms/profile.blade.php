{{Form::open(['method'=>'put','url'=>route('setting.update'),'files'=>true])}}
{{Form::hidden('_form','profile')}}
@if(Route::currentRouteName()=='root')
{{Form::hidden('_from','registration')}}
@else
{{Form::label('name')}}
@endif
{{Form::text('name',$user->name,['placeholder'=>'name','class'=>'form-control','required','style'=>Route::currentRouteName()=='root' ? 'display:none' : 'display:block'])}}
@if(Route::currentRouteName()!='root')
{{Form::label('username')}}
@endif
{{$errors->first('username')}}
{{Form::text('username',$user->username,['placeholder'=>'username','class'=>'form-control','required','style'=>Route::currentRouteName()=='root' ? 'display:none' : 'display:block'])}}
{{Form::label('bio')}}
{{$errors->first('bio')}}
{{Form::textarea('bio',$user->bio,['class'=>'form-control','placeholder'=>'bio'])}}
<div style="min-width:50%;margin:auto;padding:40px;border:1px solid rgba(80,80,80,0.3);margin-top:20px">
	<label>Avatar</label>
	<div class="center-cropped img-circle" style="background-image: url('{{URL::asset('assets/images/'.Auth::user()->photo)}}');width:240px;height:240px;margin:auto"></div>
	<br><br>
	{{$errors->first('photo')}}
	{{Form::file('photo')}}
</div>
<div style="min-width:50%;margin:auto;margin-top:20px">
	<label>Header</label>
	<div class="center-cropped" style="background-image: url('{{URL::asset('assets/images/'.Auth::user()->header)}}');width:100%;height:340px;margin:auto"></div>
	<br><br>
	<div class="form-group"> 
	{{Form::label('blur')}}
	{{Form::radio('blur',0,$user->blur==0 ? true : false)}}No
	{{Form::radio('blur',1,$user->blur==0 ? false : true)}}Yes
	</div>
	{{$errors->first('header')}}
	{{Form::file('header')}}
	<br><br>
</div>
{{Form::submit('Update',['class'=>'btn btn-lg btn-primary'])}}
{{Form::close()}}