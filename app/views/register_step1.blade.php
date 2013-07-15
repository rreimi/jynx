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