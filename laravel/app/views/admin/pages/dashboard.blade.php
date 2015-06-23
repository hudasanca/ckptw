@extends('admin')
@section('title')
Admin::dashboard
@stop()
@section('content')
<h3>All</h3>
<div class="box">
	<div class="row">
		<div class="col-sm-4">
			<h4>Users</h4>
			<div class="box white">
				all: {{User::count()}}<br>
				confirmed {{User::where('confirmed',1)->count()}}
			</div>
		</div>
		<div class="col-sm-4">
			<h4>Statuses</h4>
			<div class="box white">
				{{Status::count()}}
			</div>
		</div>
		<div class="col-sm-4">
			<h4>Comments</h4>
			<div class="box white">
				{{Comment::count()}}
			</div>
		</div>
	</div>
</div>
<div>
	<h3>Users</h3>
	<div class="box">
		<div class="hor">
			@include('admin.templates.users', ['users'=>$users])
		</div>
	</div>
	<div class="text-right">
		<a href="{{route('admin.master.whatyoudontrealize.user.index')}}" class="btn btn-md btn-default">View detail</a>
	</div>
</div>
<div>
	<h3>Statuses</h3>
	<div class="box">
		<div class="hor">
			@include('admin.templates.statuses', ['statuses'=>$statuses])
		</div>
	</div>
	<div class="text-right">
		<a href="{{route('admin.master.whatyoudontrealize.status.all')}}" class="btn btn-md btn-default">View detail</a>
	</div>
</div>
<div>
	<h3>Comments</h3>
	<div class="box">
		<div class="hor">
			@include('admin.templates.comments', ['comments'=>$comments])
		</div>
	</div>
	<div class="text-right">
		<a href="{{route('admin.master.whatyoudontrealize.comment.all')}}" class="btn btn-md btn-default">View detail</a>
	</div>
</div>
@stop()