@extends('layout_login')
@section('content')
<div class="step">
    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span10">
            <h3 id="myModalLabel">{{ Lang::get('content.publisher_success_title') }}</h3>
            <div class="row-fluid">
                <p>{{ Lang::get('content.publisher_success_message') }}</p>
                <a class="btn btn-large btn-warning publisher-info" href="{{ URL::to('/') }}">{{ Lang::get('content.publisher_success_accept') }}</a>
            </div>
        </div>
        <div class="span1"></div>
    </div>
</div>
@stop

