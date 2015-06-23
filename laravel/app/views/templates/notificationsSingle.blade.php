<div class="box messages" style="overflow:hidden;">
	<div class="center-cropped" style="float:left;margin-right:10px;width:100px;height:100px;background-image:url('{{URL::asset('assets/images/'.User::find($notif->user_sender)->photo)}}')"></div>
	<a style="width:85%" href="{{$notif->link}}">{{$notif->message}}</a>
</div>