{{ Form::open(array('url' => 'login','class'=>'big-form login-form', 'id' => 'login-form')) }}
<h4 class='header'>{{ Lang::get('content.login_header') }}</h4>
<fieldset>
    <div class="control-group {{ $errors->has('login_email')? 'error':'' }}">
        {{ Form::email('login_email',null,
            array(
                'placeholder' => Lang::get('content.login_email'),
                'class' => 'input-block-level required email'
            )
        ) }}
    </div>

    <div class="row-fluid">
        <div class="span9">
            <div class="control-group {{ $errors->has('login_password')? 'error':'' }}">
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

@section('scripts')
@parent
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('#login-form').validateBootstrap({placement:'bottom'});
        });
    </script>
@stop