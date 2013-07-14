@extends('layout_login')
@section('content')
    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span6">
            <div class="hero-unit hello">
                <h3>Bienvenido a Mercatino</h3>
                <p>
                    bla bla bla bla bla bla bla bla bla bla
                    bla bla bla bla bla bla bla bla bla bla
                    bla bla bla bla bla bla bla bla bla bla
                    bla bla bla bla bla bla bla bla bla bla
                </p>
            </div>
        </div>
        <div class="span4">
            {{ Form::open(array('url' => 'login','class'=>'big-form')) }}
            <fieldset>
                <div class="control-group @if($errors->has('login_email')) error @endif">
                    {{ Form::email('login_email',null,array('placeholder' => Lang::get('content.login_email'),'class' => 'input-block-level')) }}
                </div>

                <div class="row-fluid">
                    <div class="span9">
                        <div class="control-group @if($errors->has('login_password')) error @endif">
                            {{ Form::password('login_password',array('placeholder' => Lang::get('content.login_password'),'class' => 'input-block-level')) }}
                        </div>
                    </div>
                    <div class="span3">
                        <div class="login-controls text-right">
                            {{ Form::submit(Lang::get('content.login_signin'),array('class' => 'btn btn-success')) }}
                        </div>
                    </div>
                </div>
                <label class="checkbox">
                    {{ Form::checkbox('login_remember',true) }} {{ Lang::get('content.login_remember') }}
                </label>
                @if($errors->any() && ($errors->has('login_email') || $errors->has('login_password')))
                    <div class="alert alert-error">{{ Lang::get('content.login_error') }}</div>
                @endif
            </fieldset>
            {{ Form::close() }}
            {{ Form::open(array('url' => 'registro','class'=>'big-form')) }}
            <h4 class='header'>{{ Lang::get('content.register_header') }}</h4>
            <fieldset>
                <div class="control-group @if($errors->has('register_email')) error @endif">
                    {{ Form::email('register_email',null,array('placeholder' => Lang::get('content.register_email'),'class' => 'input-block-level')) }}
                </div>
                <div class="control-group @if($errors->has('register_first_name')) error @endif">
                    {{ Form::text('register_full_name',null,array('placeholder' => Lang::get('content.register_full_name'),'class' => 'input-block-level')) }}
                </div>
                <div class="control-group @if($errors->has('register_password')) error @endif">
                    {{ Form::password('register_password',array('placeholder' => Lang::get('content.register_password'),'class' => 'input-block-level')) }}
                </div>
                <div class="control-group @if($errors->has('register_password_confirmation')) error @endif">
                    {{ Form::password('register_password_confirmation',array('placeholder' => Lang::get('content.register_password_confirmation'),'class' => 'input-block-level')) }}
                </div>
                <div class="register-controls text-right">
                    {{ Form::submit(Lang::get('content.register_signup'),array('class' => 'btn btn-warning')) }}
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