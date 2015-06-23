@extends('default')
@section('title')
Dashboard
@stop
@section('content')
	<p>Are you sure want to delete this status?</p>
	<p><a href="{{route('{username}.status.show',[$status->username,$status->id])}}">Cancel</a></p>
	{{Form::open(['method'=>'delete','url'=>route('{username}.status.destroy',[$status->username,$status->id])])}}
	{{Form::submit('delete')}}
	{{Form::close()}}
@stop