@extends('default')
@section('title')
{{$status->name}} | {{$status->originalStatus}}
@stop
@section('content')
@include('templates.status')
@stop