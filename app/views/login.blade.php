@extends('layout_home_no_sidebar')

@section('content')

<h1 class="title login-title">{{ Lang::get('content.site_welcome') }}</h1>

{{ Form::open(array('url' => 'login','class'=>'big-form login-form', 'id' => 'login-full-form')) }}
<h4 class='header'>{{ Lang::get('content.login_header') }}</h4>
<fieldset>
    <div class="span6">
        <div class="control-group {{ $errors->has('title') ? 'error':'' }}">
            <div class="controls">
                {{ Form::email('login_email',null,
                array(
                'placeholder' => Lang::get('content.login_email'),
                'class' => 'input-block-level required email'
                )
                ) }}
                {{ $errors->first('login_email', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('login_password')? 'error':'' }}">
            <div class="controls">
                {{ Form::password('login_password', array('placeholder' => Lang::get('content.login_password'), 'class' => 'input-block-level required' )) }}
                {{ $errors->first('login_password', '<div class="field-error alert alert-error">:message</div>') }}
                {{ $errors->first('login_process', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <label class="checkbox">
            {{ Form::checkbox('login_remember',true) }} {{ Lang::get('content.login_remember') }}
        </label>
        <div class="login-controls text-right">
                {{ Form::submit(Lang::get('content.login_signin'),array('class' => 'btn btn-primary')) }}
        </div>
    </div>
    <div class="span6">
        <a class="register-banner" nohref onclick="Mercatino.registerForm.show();"><img src="img/banner_registro.jpg"/></a>
    </div>

</fieldset>
{{ Form::close() }}
@stop

@section('scripts')
@parent
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('#login-full-form').validateBootstrap({placement:'top'});
        });
    </script>
@stop