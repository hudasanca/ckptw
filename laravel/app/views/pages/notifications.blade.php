@extends('default')
@section('title')
#ckptw
@stop
@section('content')
@foreach($notifications as $notif)
<div class="status box notif-single {{$notif->clicked==1 ? 'notif-clicked' : 'notif-unclicked'}}" style="overflow:hidden;padding:20px">
	<div class="center-cropped" style="float:left;margin-right:10px;width:100px;height:100px;background-image:url('{{URL::asset('assets/images/'.User::find($notif->user_sender)->photo)}}')"></div>
	<a style="width:85%" href="{{$notif->link}}">{{$notif->message}}</a>
</div>
@endforeach
@if(!Request::ajax())
<button id="load-more-status-button" class="btn btn-lg status box visible-lg visible-md visible-sm" style="padding:20px;">Load more notifications</button>
<div class="visible-xs">
{{$notifications->links()}}
</div>
<div id="down-arrow-animation" style="text-align:center;display:none">
	<span class="glyphicon glyphicon-chevron-down"></span>
</div>
<script>
	var tinggi = $(window).height();
	var lebar = $(window).width();
	var left = (lebar-(lebar*25/100))/2+(lebar*25/100)-($('#down-arrow-animation').width()/2);

	if ($('#leftbar-outer').css('display')=='block') {
		$('#down-arrow-animation').css({
			'left':left,
			'display':'block'
		})
		$('#down-arrow-animation').animate({
			'top':$(window).height()-$('#down-arrow-animation').height()/2,
			'opacity':0
		}, 1000, function(){
			$('#down-arrow-animation').hide()
		});
	}
</script>
@endif
@stop
@section('footer')
<div class="visible-xs">
{{Form::open(['method'=>'get','autocomplete'=>'off','style'=>'height:100%','url'=>route('discover.search')])}}
{{Form::text('q','',['style'=>'height:100%','class'=>'form-control','placeholder'=>'search people, status, etc'])}}
{{Form::close()}}
</div>
@stop