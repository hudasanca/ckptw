<div class="navbar navbar-default navbar-fixed-top">
	<ul class="nav nav-pills text-center">
		<li style="width:100%"><a href="{{route('root')}}">#ckptw</a></li>
	</ul>
</div>
<div class="navbar navbar-default navbar-fixed-bottom">
	<ul class="nav nav-pills">
		<div>
		<li><a href="{{route('root')}}"><span class="glyphicon glyphicon-home"></span></a></li>
		@if(Auth::check())
		<li><a href="{{route('user.show',[Auth::user()->username])}}"><span class="glyphicon glyphicon-user"></span></a></li>
		<li><a class="{{count(Session::get('notifications'))>0 ? 'orange' : ''}}" href="{{route('notif.index')}}"><span class="glyphicon glyphicon-bell"></span></a></li>
		<li><a href="{{route('discover')}}"><span class="glyphicon glyphicon-fire"></span></a></li>
		<li><a href="{{route('setting')}}"><span class="glyphicon glyphicon-wrench"></span></a></li>
		<li><a href="{{route('signout')}}"><span class="glyphicon glyphicon-log-out"></span></a></li>
		@else
		<li class="right"><a href="{{route('signin')}}">Sign in</a></li>
		<li class="right"><a href="{{route('register')}}">Register</a></li>
		@endif
		</div>
	</ul>
</div>