@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    {{ Form::open(array('url' => 'usuario/guardar', 'method' => 'post', 'class' => 'form-horizontal')) }}
        @if (!isset($user->id))
        <h1>{{Lang::get('content.new_user_admin')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_user')}}: {{ $user->full_name }}</h1>
        @endif

        <div class="control-group {{ $errors->has('name') ? 'error':'' }}">
            <label class="control-label" for="name">{{ Lang::get('content.user_name') }}</label>
            <div class="controls">
                {{ Form::text('full_name', $user->full_name, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.user_name'))) }}
                {{ $errors->first('full_name', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
            <label class="control-label" for="email">{{ Lang::get('content.user_email') }}</label>
            <div class="controls">
                {{ Form::text('email', $user->email, array('class' => 'input-xlarge', 'placeholder'=> Lang::get('content.user_email'))) }}
                {{ $errors->first('email', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('role') ? 'error':'' }}">
            <label class="control-label" for="role">{{ Lang::get('content.user_role') }}</label>
            <div class="controls">
                {{ Form::select('role', $user_roles, $user->role) }}
                {{ $errors->first('role', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
            <label class="control-label" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select('status', $user_statuses, $user->status) }}
                {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
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