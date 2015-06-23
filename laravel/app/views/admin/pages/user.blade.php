@extends('admin')
@section('title')
Admin::dashboard
@stop()
@section('content')
<div>
	<h3>Statuses</h3>
	<p>Jumlah: {{$users->getTotal()}}</p>
	<div class="box">
		<div class="hor">
			@include('admin.templates.users', ['users'=>$users])
		</div>
	</div>
	<div class="text-center">
		{{$users->links()}}
	</div>
</div>
@stop()