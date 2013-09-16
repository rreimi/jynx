<div class="register-form-first">
    {{ Form::open(array('url' => 'registro','class'=>'big-form register-form', 'id' => 'register-form')) }}
        <h4 class='header'>{{ Lang::get('content.register_header') }}</h4>
        <fieldset>
            <div class="control-group {{ $errors->has('register_email')? 'error':'' }}">
                {{ Form::email('register_email',null,array('placeholder' => Lang::get('content.register_email'),'class' => 'input-block-level required')) }}
            </div>
            <div class="control-group {{ $errors->has('register_full_name')? 'error':'' }}">
                {{ Form::text('register_full_name',null,array('placeholder' => Lang::get('content.register_full_name'),'class' => 'input-block-level required')) }}
            </div>
            <div class="control-group {{ $errors->has('register_password')? 'error':'' }}">
                {{ Form::password('register_password',array('placeholder' => Lang::get('content.register_password'),'class' => 'input-block-level required')) }}
            </div>
            <div class="control-group {{ $errors->has('register_password_confirmation')? 'error':'' }}">
                {{ Form::password('register_password_confirmation',array('placeholder' => Lang::get('content.register_password_confirmation'),'class' => 'input-block-level required')) }}
            </div>
            <label class="checkbox terminos">
                {{ Form::checkbox('register_conditions',true,null,array('class'=>'required')) }} {{ Lang::get('content.register_conditions') }}
            </label>
        </fieldset>
    {{ Form::close() }}
</div>
<div class="register-form-conditions">
    Aquí va el contenido de los términos y condiciones de servicio.
    <br/>
    <br/>
    <a noref onclick="javascript:hideConditions();" class="manito">{{ Lang::get('content.register_hide_conditions_back') }}</a>
</div>

@section('scripts')
@parent

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#register-form').validateBootstrap({placement:'bottom'});
    });

    function showConditions(){
        jQuery('.register-form-first').hide();
        jQuery('.modal-header').hide();
        jQuery('.modal-footer').hide();
        jQuery('.register-form-conditions').show();
    }

    function hideConditions(){
        jQuery('.register-form-conditions').hide();
        jQuery('.register-form-first').show();
        jQuery('.modal-header').hide();
        jQuery('.modal-footer').show();
    }
</script>
@stop