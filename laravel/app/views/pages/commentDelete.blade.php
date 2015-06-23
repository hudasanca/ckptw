@extends('default')
@section('title')
Dashboard
@stop
@section('content')
<p>Are you sure want to delete this status?</p>
<p><a href="{{route('{username}.status.show',[$status->username,$status->id])}}">Cancel</a></p>
{{Form::open(['method'=>'delete','url'=>route('{username}.status.comment.destroy',[$status->username,$comment->status_id,$comment->id])])}}
{{Form::submit('Delete')}}
{{Form::close()}}
@stop