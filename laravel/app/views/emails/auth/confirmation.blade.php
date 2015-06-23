<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		{{HTML::style('assets/css/bootstrap.min.css')}}
		{{HTML::script('assets/js/jquery.min.js')}}
	</head>
	<body>
		<div class="container">
			<h1>Just a little bit again!</h1>
			<div class="jumbotron">
				<div>
					<p>Hey, {{Auth::user()->name}}! Your account will happily get ready a second after you confirm that this is really you who just registered on <a href="http://ckptw.com">ckptw.com</a>.</p>
					<p>Complete this registration by clicking the link bellow. Have fun!</p>
					<br><a href="{{route('confirmation', array(Auth::user()->confirmation_token))}}">{{route('confirmation', array(Auth::user()->confirmation_token))}}</a>
					<br><br>
					<p><small>*nb: if you didn't think you just did any registration on ckptw.com, just ignore this message.</small></p>
				</div>
			</div>
		</div>
	</body>
</html>