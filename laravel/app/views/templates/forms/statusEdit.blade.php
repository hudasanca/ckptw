{{Form::open(['url'=>route('{username}.status.update',[$status->username,$status->id]),'method'=>'put'])}}
{{$status->user_id!=Auth::id() ? 'This\'s not your status!' : ''}}
{{$errors->first('status')}}
{{Form::textarea('status',Route::currentRouteName()=="{username}.status.edit" ? $status->originalStatus : Input::old('status'),['placeholder'=>'status',Route::currentRouteName()=="{username}.status.edit"&&$status->user_id!=Auth::id() ? 'disabled' : 'enabled'])}}
{{Form::submit('Update',[$status->user_id!=Auth::id() ? 'disabled' : 'enabled','class'=>'btn btn-lg btn-success'])}}
{{Form::close()}}