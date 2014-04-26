@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    {{ Form::open(array('url' => 'usuario/guardar', 'method' => 'post', 'class' => 'form-horizontal user-form')) }}
        @if (!isset($user->id))
        <h1>{{Lang::get('content.new_user_admin')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_user')}}: {{ $user->full_name }}</h1>
        @endif

        <div class="control-group {{ $errors->has('name') ? 'error':'' }}">
            <label class="control-label required-field" for="name">{{ Lang::get('content.user_name') }}</label>
            <div class="controls">
                {{ Form::text('full_name', $user->full_name, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.user_name'))) }}
                {{ $errors->first('full_name', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
            <label class="control-label required-field" for="email">{{ Lang::get('content.user_email') }}</label>
            <div class="controls">
                {{ Form::text('email', $user->email, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.user_email'))) }}
                {{ $errors->first('email', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('role') ? 'error':'' }}">
            <label class="control-label required-field" for="role">{{ Lang::get('content.user_role') }}</label>
            <div class="controls">
                {{ Form::select('role', $user_roles, $user->role, array('class'=>'required role')) }}
                {{ $errors->first('role', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        @if ($groupsQty > 1 || $user->role == User::ROLE_SUBADMIN || $user->id == null)
            <div class="control-group group-section {{ $errors->has('group') ? 'error':'' }}">
                <label class="control-label required-field" for="role">{{ Lang::get('content.user_group') }}</label>
                <div class="controls">
                    {{ Form::select('group', $groups, $user->group_id, array('class'=>'required group-field')) }}
                    {{ $errors->first('group', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
        @endif

        <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
            <label class="control-label required-field" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select('status', $user_statuses, $user->status, array('class'=>'required')) }}
                {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        @if($user->id)
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-password" type="button" data-toggle="collapse" data-target=".collapse-password">{{Lang::get('content.restore_password')}}</button>
                </div>
            </div>
            <div class="collapse collapse-password out">
                <div class="control-group {{ $errors->has('password') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.password') }}</label>
                    <div class="controls">
                        {{ Form::password('password', null, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.password'))) }}
                        {{ $errors->first('password', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password_confirmation') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.password_confirmation') }}</label>
                    <div class="controls">
                        {{ Form::password('password_confirmation', null, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.password_confirmation'))) }}
                        {{ $errors->first('password_confirmation', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="control-group">
            <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
        </div>

        {{ Form::hidden('id', $user->id) }}
        {{ Form::hidden('referer', $referer) }}

        <div class="control-group">
            <div class="controls">
                <a href="{{ $referer }}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
                <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.save')}}</button>
            </div>
        </div>


    {{ Form::close() }}

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.user-form').validateBootstrap();

        jQuery('.btn-password').click(function() {
            if (!jQuery('.collapse-password').hasClass('in')){
                jQuery("input:password").removeAttr('disabled');
                jQuery('.btn-password').button('toggle');
                jQuery("input:password").addClass('required');
            } else {
                jQuery('.btn-password').button('toggle');
                jQuery("input:password").val('');
                jQuery("input:password").attr('disabled', 'disabled');
                jQuery("input:password").removeClass('required');
            }
        });

        var passwordError = {{ $errors->has('password') || $errors->has('password_confirmation') ? 'true' : 'false' }};
        if (passwordError){
            jQuery("input:password").val('');
            jQuery('.btn-password').click();
        } else {
            jQuery("input:password").val('');
            jQuery("input:password").attr('disabled', 'disabled');
        }

        jQuery('.role').bind("change", function(){
            if (jQuery('select.role').val() == '{{ User::ROLE_SUBADMIN }}'){
                jQuery('.group-section').show();
            } else {
                jQuery('.group-section').hide();
                jQuery('.group-field').val('');
            }
        });

        jQuery('.role').trigger('change');
    });

</script>
@stop