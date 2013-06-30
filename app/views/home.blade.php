@extends('layout')

@section('sidebar')
<p>This is appended to the master sidebar.</p>
@parent
@stop

@section('content')
The current UNIX timestamp is {{ time() }}.
<p>This is my body content. {{$object->id}}</p>
@stop