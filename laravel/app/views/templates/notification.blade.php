@if(Auth::check())
<div id="notification-body" class="notification-body visible-lg visible-md visible-sm">
	<h4>Apps</h4>
	<hr>
	<ul style="overflow:hidden">
		<li style="float:left"><a data-toggle="tooltip" data-placement="bottom" title="Home" href="{{route('root')}}"><span class="glyphicon glyphicon-home"></span></a></li>
		@if(Auth::user()->step > 3)
		<li style="float:left"><a data-toggle="tooltip" data-placement="bottom" title="New status" id="btn" id-impact="status-form" href="{{route('root')}}" role="button"><span class='glyphicon glyphicon-pencil'></span></a></li>
		@endif
		<li style="float:left"><a data-toggle="tooltip" data-placement="bottom" title="Discover" href="{{route('discover')}}" role="button"><span class='glyphicon glyphicon-fire'></span></a></li>
		<li style="float:left"><a data-toggle="tooltip" data-placement="bottom" title="Setting" href="{{route('setting')}}" ><span class="glyphicon glyphicon-wrench"></span></a></li>
		<li style="float:left"><a data-toggle="tooltip" data-placement="bottom" title="Sign out" href="{{route('signout')}}"><span class="glyphicon glyphicon-log-out"></span></a></li>
		<li style="float:right;height:58px">
		{{Form::open(['method'=>'get','autocomplete'=>'off','style'=>'height:100%','url'=>route('discover.search')])}}
		{{Form::text('q','',['style'=>'height:100%','class'=>'form-control','placeholder'=>'search people, status, etc'])}}
		{{Form::close()}}
		</li>
	</ul>
	<h4>Notifications</h4>
	<hr id="hr-0">
	<div id="messages" style="overflow-y:auto;height: 300px;display:none;padding:20px">
		@foreach(Session::get('notifications') as $notif)
			@include('templates.notificationsSingle')
		@endforeach
		<div class="box messages" style="overflow:hidden;">
			<a href="{{route('notif.index')}}">See all notifications</a>
		</div>
	</div>
</div>
<div id="notification-bar" class='notification-bar visible-lg visible-md visible-sm'>
	<span id="arrow" class="glyphicon glyphicon-chevron-down"> </span>
	@foreach(Session::get('notifications') as $notif)
	@include('templates.notificationsIcon',['notif'=>$notif])
	@endforeach
	<div style="float:right">
	<span class='glyphicon glyphicon-user'></span> {{Auth::user()->name}} ({{'@'.Auth::user()->username}})
	</div>
</div>
@endif