@extends('layout_login')
@section('content')
    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span5">
            {{ Form::open(array('url' => 'login','class'=>'big-form')) }}
            <h3 class='header'>Please sign in</h3>
            <fieldset>
                {{ Form::email('login.email',null,array('placeholder'=>'Email address','class'=>'input-block-level')) }}
                {{ Form::password('login.password',array('placeholder'=>'Password','class'=>'input-block-level')) }}
                <label class="checkbox">
                    {{ Form::checkbox('login.remember','Remember me') }} Remember me
                </label>
                <button class="btn btn-large btn-warning pull-right" type="submit">Sign in</button>
            </fieldset>
            {{ Form::close() }}
        </div>
        <div class="span5">
            {{ Form::open(array('url' => 'register','class'=>'big-form')) }}
            <h3 class='header'>Please sign up</h3>
            <fieldset>
                <div class="control-group @if($errors->has('register.email')) error @endif">
                    {{ Form::email('register.email',null,array('placeholder'=>'Email address','class'=>'input-block-level')) }}
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        {{ Form::text('register.first_name',null,array('placeholder'=>'First name','class'=>'input-block-level')) }}
                    </div>
                    <div class="span6">
                        {{ Form::text('register.last_name',null,array('placeholder'=>'Last name','class'=>'input-block-level')) }}
                    </div>
                </div>
                {{ Form::password('register.password',array('placeholder'=>'Password','class'=>'input-block-level')) }}
                {{ Form::password('register.password_confirmation',array('placeholder'=>'Repeat Password','class'=>'input-block-level')) }}

                <button class="btn btn-large btn-warning pull-right" type="submit">Sign up</button>
            </fieldset>
            {{ Form::close() }}
        </div>
        <div class="span1"></div>
    </div>
@stop