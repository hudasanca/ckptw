@extends('admin')
@section('title')
Admin::{{$user->username}}
@stop()
@section('content')
{{$user->name}}
@stop()