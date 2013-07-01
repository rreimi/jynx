@extends('layout_home')

@section('sidebar')
@parent
<p>This is appended to the master sidebar.</p>
@stop

@section('content')
The current UNIX timestamp is {{ time() }}.
<p>This is my body content. </p>
@stop