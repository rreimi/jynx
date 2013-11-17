@extends('layout_login')
@section('content')
<div class="step">
    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span10">
            <div class="big-form register-success">
                <h3>{{ Lang::get('content.publisher_success_title') }}</h3>
                <p class="text">{{ Lang::get('content.publisher_success_message') }}</p>
                <a class="btn btn-large btn-warning" href="{{ URL::to('/') }}">{{ Lang::get('content.publisher_success_accept') }}</a>
            </div>
        </div>
    </div>
</div>
@stop

