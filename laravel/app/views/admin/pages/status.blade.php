@extends('admin')
@section('title')
Admin::dashboard
@stop()
@section('content')
<div>
	<h3>Statuses</h3>
	<p>Jumlah: {{$statuses->getTotal()}}</p>
	<div class="box">
		<div class="hor">
			@include('admin.templates.statuses', ['statuses'=>$statuses])
		</div>
	</div>
	<div class="text-center">
		{{$statuses->links()}}
	</div>
</div>
@stop()