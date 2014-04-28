@extends('layout_backend')

@section('sidebar')
    @include('include.publication_table_sidebar')
    @parent
@stop

@include('include.publication_table')