<div class="comment">
	<div class="row">
		<div class="col col-lg-1 col-md-1 col-sm-1 visible-lg visible-md visible-sm">
			<div style="padding-top:10px">
				<div class="img-circle center-cropped" style="background-image:url('{{URL::asset('assets/images/'.$comment->photo)}}');width:50px;height:50px"></div>
			</div>
		</div>
		<div id="comment-box" class="col col-lg-11 col-md-11 col-sm-11">
			<div class="box" style="padding:0">
				<div class="comment-header">
					<a href="{{route('user.show',[$comment->username])}}">{{$comment->name}}</a>
					<a href="{{route('user.show',[$comment->username])}}"><small>{{'@'.$comment->username}}</small></a>
				</div>
				<div class="comment-body">
					{{$comment->comment}}
				</div>
				<div class="comment-footer">
				<a act="{{$comment->loved ? 'unlove' : 'love'}}" id="love-comment" style="color:{{$comment->loved ? '#5947B7' : ''}}" href="{{route($comment->loved ? 'comment.unlove':'comment.love',[$status->username,$status->id,$comment->id])}}"><span class="count">{{count(json_decode($comment->loves))>0 ? count(json_decode($comment->loves)) : ''}}</span><span class="glyphicon glyphicon-heart"></span></a>
				@if(Auth::id()==$comment->user_id)
				<a  href="{{route('{username}.status.comment.edit',[$status->username,$status->id,$comment->id])}}"><span class="glyphicon glyphicon-edit"></span></a>
				<a href="{{route('{username}.status.comment.delete.confirm',[$status->username,$status->id,$comment->id])}}"><span class="glyphicon glyphicon-trash"></span></a>
				@endif
				</div>
			</div>
		</div>
	</div>
</div>