<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="{{URL::asset('assets/images/ckptw_logo_shadow.png')}}" type="image/x-icon" />
	{{HTML::style('assets/css/bootstrap.min.css')}}
	{{HTML::style('assets/css/admin.css')}}
	<title>@yield('title')</title>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{route('admin.master.whatyoudontrealize.index')}}">Admin</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{route('admin.master.whatyoudontrealize.index')}}">Dashboard</a></li>
					<li><a href="#">Announcements</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Users <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
								<li><a href="#">All</a></li>
								<li><a href="#">Popular</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Statuses <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
								<li><a href="#">All</a></li>
								<li><a href="#">Popular</a></li>
						</ul>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	<div class="container" style="padding-top:50px">
		<h1>Admin Page</h1>
		@yield('content')
	</div>
	{{HTML::script('assets/js/jquery.min.js')}}
	{{HTML::script('assets/js/bootstrap.min.js')}}
</body>
</html>