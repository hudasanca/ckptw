@extends('default')
@section('title')
#ckptw
@stop
@section('content')
<div class="root">
<!-- <div class="home pad-section" id="home">
	<div class="text-vcenter col-sm-12">
		<h1>#ckptw</h1>
		<h3>The place to share what's happened in life with</h3>
	</div>
</div> -->

<div id="home" class="pad-section" style="background-image: url('{{URL::asset('assets/images/default_user_header.png')}}');">
	<div class="text-vcenter">
		<h1>#ckptw</h1>
		<h3>A place to share what's happened in life with</h3>
	</div>
</div>

<!-- Third section services -->
<div id="services" class="pad-section">
	<div>
		<h2 class="text-center">Our services</h2><hr>
		<div class="row text-center">
			<div class="col-sm-3 col-xs-6">
				<i class="glyphicon glyphicon-pencil"> </i>
	        <h4>Write a status</h4>
	        <p>Tell the world what's just happened in life</p>
			</div>
			<div class="col-sm-3 col-xs-6">
	        <i class="glyphicon glyphicon-heart"> </i>
	        <h4>Love</h4>
	        <p>Give a heart to stories you really love</p>
	      </div>
	      <div class="col-sm-3 col-xs-6">
	        <i class="glyphicon glyphicon-comment"> </i>
	        <h4>Response to others</h4>
	        <p>Respone to people you follow to get interacted with</p>
	      </div>
	      <div class="col-sm-3 col-xs-6">
	        <i class="glyphicon glyphicon-fire"> </i>
	        <h4>Discover</h4>
	        <p>Discover for what's up on the world and find popular people</p>
	      </div>
		</div>
	</div>
</div>

<!-- Second sectin about -->
<div id="about" class="pad-section" style="width:100%;color:white;background-image: url('{{URL::asset('assets/images/5mm.jpg')}}'); ">
	<div style="background-color:rgba(80,80,80,0.5);padding-top:130px;padding-bottom:130px">
		<div class="text-center">
			<h2>Ckptw brings you to your private world with people you love</h2>
			<p class="lead">Greet, send them messages, tell your stories, and create your histories</p>
		</div>
		<div class="text-center" style="margin:auto">
	        <a class="btn btn-lg btn-black" href="{{route('signin')}} ">Sign in</a>
	    	<a class="btn btn-lg btn-black" href="{{route('register')}} ">Sign up</a>
		</div>
	</div>
</div>

<!-- fifth section -->
<div id="services" class="pad-section">
  <div >
    <div class="row">
      <div class="col-sm-12 text-center">
        <h4>Copyright &copy; sancasoft 2015</h4>
      </div>
    </div>
  </div>
</div>
<!-- /fifth section -->

</div>
{{HTML::style('assets/css/home.css')}}
<script>
	$(document).ready(function(){
		backgroundAddjustment();
	});
	$(window).resize(function(){
		backgroundAddjustment();
	});
	function backgroundAddjustment(){
		if ($('#leftbar-outer').css('display')=='block') {
			$('#home').css({
				'height':$(window).height(),
				'background-position':($(window).width()/4-30)+'px center',
				'background-size':'88%'
			});
			$('#information').css({
				'background-position':($(window).width()/4)+'px center',
				'background-size':'85%'
			});
			$('#about').css({
				'background-position':($(window).width()/4)+'px center',
				'background-size':'85%'
			});
		}
		else{
			$('#home').css({
				'height':'',
				'background-position':'',
				'background-size':''
			});
			$('#information').css({
				'background-position':'',
				'background-size':''
			});
			$('#about').css({
				'background-position':'',
				'background-size':''
			});
		}
	}
</script>
@stop