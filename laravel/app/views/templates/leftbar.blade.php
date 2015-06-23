<div class="leftbar">
	<div class="leftbar-brand">
		<a href="{{route('root')}}"><img width="100px" height="100px" src="{{URL::asset('assets/images/ckptw_logo_shadow.png')}}"></a>
		<h1>#ckptw</h1>
	</div>
	<div class="rel-link">
		<ul>
			@if(Auth::check())
			<li>
				<a href="{{route('user.show',[Auth::user()->username])}}">
					<div class="center-cropped img-circle" href="{{route('user.show',[Auth::user()->username])}}" style="background-image: url('{{URL::asset('assets/images/'.Auth::user()->photo)}}');width:120px;height:120px;margin:auto"></div>
				</a>
			</li>
			<li><h3><a style="color:white" href="{{route('user.show',[Auth::user()->username])}}">{{Auth::user()->name}}</a></h3></li>
			<li><a href="{{route('root')}}" role="button" class="btn btn-black btn-lg"><span class="glyphicon glyphicon-home"></span> Home</a></li>
			@if(Auth::user()->step>3)
			<li><a id="btn" id-impact="status-form" href="{{route('root')}}" role="button" class="btn btn-black btn-lg"><span class='glyphicon glyphicon-pencil'></span> New Status</a></li>
			@endif
			<li><a href="{{route('signout')}}" role="button" class="btn btn-black btn-lg"><span class="glyphicon glyphicon-log-out"></span> Sign out</a></li>
			@else
			<li><a href="{{route('register')}}" role="button" class="btn btn-black btn-lg"><span class='glyphicon glyphicon-user'></span> Sign up</a></li>
			<li><a href="{{route('signin')}}" role="button" class="btn btn-black btn-lg"><span class='glyphicon glyphicon-log-in'></span> Sign in</a></li>
			@endif
		</ul>
	</div>
</div>