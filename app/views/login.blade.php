@extends('layout_login')
@section('content')
    <div class="initial">
        <div class="row-fluid">
            <div class="span1"></div>
            <div class="span6">
                <div class="hero-unit hello">
                    <h3>{{ Lang::get('content.site_welcome') }}</h3>
                    <p>
                        {{ Lang::get('content.site_description') }}
                    </p>
                </div>
            </div>
            <div class="span4">
                {{ Form::open(array('url' => 'login','class'=>'big-form login-form')) }}
                <h4 class='header'>{{ Lang::get('content.login_header') }}</h4>
                <fieldset>
                    <div class="control-group @if($errors->has('login_email')) error @endif">
                        {{ Form::email('login_email',null,
                            array(
                                'placeholder' => Lang::get('content.login_email'),
                                'class' => 'input-block-level required email'
                            )
                        ) }}
                    </div>

                    <div class="row-fluid">
                        <div class="span9">
                            <div class="control-group @if($errors->has('login_password')) error @endif">
                                {{ Form::password('login_password',
                                    array(
                                        'placeholder' => Lang::get('content.login_password'),
                                        'class' => 'input-block-level required'
                                    )
                                ) }}
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
                </fieldset>
                {{ Form::close() }}
                @include('register_step1')
            </div>
            <div class="span1"></div>
        </div>
    </div>
@stop

@section('scripts')
@parent

    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('.login-form').validateBootstrap({placement:'left'});
        });
    </script>
@stop