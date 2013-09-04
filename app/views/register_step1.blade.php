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

@section('scripts')
@parent

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#register-form').validateBootstrap({placement:'bottom'});
    });
</script>
@stop