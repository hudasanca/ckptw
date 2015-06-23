{{Form::open(['id'=>'status-form','class'=>'popup','url'=>route('{username}.status.store')])}}
{{$errors->first('status')}}
{{Form::textarea('status','',['placeholder'=>'status',Route::currentRouteName()=="{username}.status.edit"&&$status->user_id!=Auth::id() ? 'disabled' : 'enabled'])}}
{{Form::hidden('photo','',['id'=>'photo'])}}
<div id="instagram-result" style="margin-top:20px"></div>
<div class="form-group text-right">
	<a style="font-size:32pt" href="#" id="get-instagram"><img style="height:30px;width:30px" src="{{URL::asset('assets/images/Instagram_Icon.png')}}"></span></a>
	<a style="font-size:19pt" href="#" onclick="statusSubmit()"><span class="glyphicon glyphicon-send"></span></a>
	{{Form::submit('post',['id'=>'post','class'=>'btn btn-lg btn-primary','style'=>'display:none'])}}
</div>
{{Form::close()}}