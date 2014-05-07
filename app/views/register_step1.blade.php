<div class="register-form-first" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    {{ Form::open(array('url' => 'registro','class'=>'big-form register-form', 'id' => 'register-form')) }}
        <h4 class='header'>{{ Lang::get('content.register_header') }}</h4>
        <fieldset>
            <div class="control-group {{ $errors->has('register_email')? 'error':'' }}">
                {{ Form::email('register_email',null,array('placeholder' => Lang::get('content.register_email'),'class' => 'input-block-level required email')) }}
            </div>
            <div class="control-group {{ $errors->has('register_full_name')? 'error':'' }}">
                {{ Form::text('register_full_name',null,array('placeholder' => Lang::get('content.register_full_name'),'class' => 'input-block-level required')) }}
            </div>
            <div class="control-group {{ $errors->has('register_password')? 'error':'' }}">
                {{ Form::password('register_password',array('id' => 'register_password', 'placeholder' => Lang::get('content.register_password'),'class' => 'input-block-level required')) }}
            </div>
            <div class="control-group {{ $errors->has('register_password_confirmation')? 'error':'' }}">
                {{ Form::password('register_password_confirmation',array('placeholder' => Lang::get('content.register_password_confirmation'),'class' => 'input-block-level required')) }}
            </div>
            <div class="checkbox terminos">
                {{ Form::checkbox('register_conditions',true,null,array('class'=>'required', 'data-placement' => 'right')) }} {{ Lang::get('content.register_conditions') }}
            </div>
        </fieldset>
    {{ Form::close() }}
</div>
<div class="register-form-conditions">
    <h2>TÉRMINOS Y CONDICIONES LEGALES</h2>
    @include('include.terms_and_conditions')
    <a noref onclick="javascript:hideConditions();" class="manito">{{ Lang::get('content.register_hide_conditions_back') }}</a>
    <br/>
    <br/>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    function showConditions(){
        jQuery('.register-form-first').hide();
        jQuery('.modal-header').hide();
        jQuery('.modal-footer').hide();
        jQuery('.register-form-conditions').show();
    }

    function hideConditions(){
        jQuery('.register-form-conditions').hide();
        jQuery('.register-form-first').show();
        jQuery('.modal-header').show();
        jQuery('.modal-footer').show();
    }
</script>
@stop