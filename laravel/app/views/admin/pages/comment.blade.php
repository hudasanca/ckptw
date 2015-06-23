@extends('admin')
@section('title')
Admin::dashboard
@stop()
@section('content')
<div>
	<h3>Comments</h3>
	<p>Jumlah: {{$comments->getTotal()}}</p>
	<div class="box">
		<div class="hor">
			@include('admin.templates.comments', ['comments'=>$comments])
		</div>
	</div>
	<div class="text-center">
		{{$comments->links()}}
	</div>
</div>
@stop()