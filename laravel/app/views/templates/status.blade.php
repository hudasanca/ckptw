<div id="status" class="status box hideme" status-username='{{$status->username}}' status-id="{{$status->id}}">
	<div class="status-header">
		<div class="row">
			<div class="col col-lg-2 col-md-2 col-sm-2 visible-lg visible-md visible-sm">
				<a href="{{route('user.show',[$status->username])}}">
					<div class="img-circle img-status center-cropped" style="background-image:url('{{URL::asset('assets/images/'.$status->photo)}}')">
					</div>
				</a>
			</div>
			<div class="col col-lg-10 col-md-10 col-sm-10">
				<div class="col col-lg-6 col-md-6 col-sm-6" style="padding-left:0">
					<div><a href="{{route('user.show',[$status->username])}}">{{$status->name}}</a></div>
					<div><a href="{{route('user.show',[$status->username])}}"><small>{{'@'.$status->username}}</small></a></div>
				</div>
				<div class="col col-lg-6 col-md-6 col-sm-6" style="text-align:right">
					<span class="glyphicon glyphicon-time"></span> {{$status->date}}
				</div>
			</div>
		</div>
	</div>
	<div class="status-body">
		{{$status->status}}
	</div>
	@if($status->photo_status!=null)
	<div class="status-photo">
		<img src="{{$status->photo_status}}" style="width:100%">
	</div>
	@endif
	<div class="status-footer">
		<div>
			<a act="{{$status->loved ? 'unlove' : 'love'}}" id="love-status" style="color:{{$status->loved ? '#5947B7' : ''}}" href="{{route($status->loved ? 'unlove':'love',[$status->username,$status->id])}}"><span class="count">{{count(json_decode($status->loves))>0 ? count(json_decode($status->loves)) : ''}}</span> <span class="glyphicon glyphicon-heart"></span></a>
			<a href="{{route('{username}.status.show',[$status->username,$status->id])}}"><span class="count">{{count($status->comments)>0 ? count($status->comments) : ''}}</span> <span class='glyphicon glyphicon-comment'></span></a>
			@if(Auth::id()==$status->user_id)
				<a href="{{route('{username}.status.edit',[$status->username,$status->id])}}"><span class='glyphicon glyphicon-edit'></span></a>
				<a id="btn" id-impact="delete-pop-up" href="{{route('{username}.status.delete.confirm',[$status->username,$status->id])}}"><span class='glyphicon glyphicon-trash'></span></a>
			@endif
			<a href="{{route('{username}.status.show',[$status->username,$status->id])}}">View details</a>
		</div>
	</div>
</div>
@if(Route::currentRouteName()=='{username}.status.show')
	<div class="comment">
		Comments:
	</div>
	@foreach($comments as $comment)
		@include('templates.comment')
	@endforeach
	@include('templates.forms.comment')
@endif