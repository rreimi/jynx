@extends('layout_backend')

@section('sidebar')
    @include('include.job_table_sidebar')
    @parent
@endsection

@include('include.job_table')