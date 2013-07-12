@extends('layout_login')
@section('content')
    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span5">
            {{ Form::open(array('url' => 'login','class'=>'big-form')) }}
            <h3 class='header'>{{ Lang::get('content.login_header') }}</h3>
            <fieldset>
                <div class="control-group @if($errors->has('login_email')) error @endif">
                    {{ Form::email('login_email',null,array('placeholder' => Lang::get('content.login_email'),'class' => 'input-block-level')) }}
                </div>
                <div class="control-group @if($errors->has('login_password')) error @endif">
                    {{ Form::password('login_password',array('placeholder' => Lang::get('content.login_password'),'class' => 'input-block-level')) }}
                </div>
                <label class="checkbox">
                    {{ Form::checkbox('login_remember',true) }} {{ Lang::get('content.login_remember') }}
                </label>
                <div class="login-controls text-right">
                    {{ Form::submit(Lang::get('content.login_signin'),array('class' => 'btn btn-large btn-success')) }}
                </div>
                @if($errors->any() && ($errors->has('login_email') || $errors->has('login_password')))
                    <div class="alert alert-error">{{ Lang::get('content.login_error') }}</div>
                @endif
            </fieldset>
            {{ Form::close() }}
        </div>
        <div class="span5">
            {{ Form::open(array('url' => 'registro','class'=>'big-form')) }}
            <h3 class='header'>{{ Lang::get('content.register_header') }}</h3>
            <fieldset>
                <div class="control-group @if($errors->has('register_email')) error @endif">
                    {{ Form::email('register_email',null,array('placeholder' => Lang::get('content.register_email'),'class' => 'input-block-level')) }}
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group @if($errors->has('register_first_name')) error @endif">
                            {{ Form::text('register_first_name',null,array('placeholder' => Lang::get('content.register_first_name'),'class' => 'input-block-level')) }}
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group @if($errors->has('register_last_name')) error @endif">
                            {{ Form::text('register_last_name',null,array('placeholder' => Lang::get('content.register_last_name'),'class' => 'input-block-level')) }}
                        </div>
                    </div>
                </div>
                <div class="control-group @if($errors->has('register_password')) error @endif">
                    {{ Form::password('register_password',array('placeholder' => Lang::get('content.register_password'),'class' => 'input-block-level')) }}
                </div>
                <div class="control-group @if($errors->has('register_password_confirmation')) error @endif">
                    {{ Form::password('register_password_confirmation',array('placeholder' => Lang::get('content.register_password_confirmation'),'class' => 'input-block-level')) }}
                </div>
                <label class="checkbox">
                    {{ Form::checkbox('register_publisher',true) }} {{ Lang::get('content.register_publisher') }}
                </label>
                <div class="register-controls text-right">
                    {{ Form::submit(Lang::get('content.register_signup'),array('class' => 'btn btn-large btn-warning')) }}
                </div>
                @if($errors->any() && ($errors->has('register_email') || $errors->has('register_first_name') || $errors->has('register_last_name') ||
                        $errors->has('register_password') || $errors->has('register_password_confirmation')))
                    <div class="alert alert-error">{{ Lang::get('content.register_error') }}</div>
                @endif
            </fieldset>
            {{ Form::close() }}
        </div>
        <div class="span1"></div>
    </div>
@stop