<div class="col-lg-4 col-md-4 col-sm-4">
	<div class="user-summary">
		<div class="user-summary-header center-cropped" style="background-image: url('{{URL::asset('assets/images/'.$user->header)}}');">
			<a href="{{route('user.show',[$user->username])}}">
				<div class="center-cropped img-circle" href="{{route('user.show',[$user->username])}}" style="background-image: url('{{URL::asset('assets/images/'.$user->photo)}}');width:80px;height:80px;margin:auto"></div>
			</a>
			<div style="margin-top:10px"><a style="color:white" href="{{route('user.show',[$user->username])}}">{{$user->name}}</a></div>
			<div>{{'@'.$user->username}}</div>
		</div>
		<div class="user-summary-body">
			<div style="height:80px">
				{{$user->bio}}
			</div>
			<div>
				@if(Auth::id()!=$user->id)
					@if(!$user->followed)
					<a role="button" class="btn btn-lg btn-primary" href="{{route('follow',[$user->username,'redirect'=>convert_uuencode(Request::url())])}}">Follow</a>
					@else
					<a role="button" class="btn btn-lg btn-danger" href="{{route('unfollow',[$user->username,'redirect'=>convert_uuencode(Request::url())])}}">Unfollow</a>
					@endif
				@else
					<a role="button" class="btn btn-lg btn-success" href="{{route('user.show',[$user->username])}}">It's you!</a>
				@endif
			</div>
		</div>
	</div>
</div>