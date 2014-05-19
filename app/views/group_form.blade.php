@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')

    <div class="row-fluid">
        {{ Form::open(array('url' => 'grupo/guardar', 'method' => 'post', 'class' => 'form-horizontal group-form')) }}
            @if (!isset($group->id))
                <h1>{{Lang::get('content.new_group')}}</h1>
            @else
                <h1>{{Lang::get('content.edit_group')}}: {{ $group->group_name }}</h1>
            @endif

            <div class="control-group {{ $errors->has('group_name') ? 'error':'' }}">
                <label class="control-label required-field" for="group_name">{{ Lang::get('content.group_name') }}</label>
                <div class="controls">
                    {{ Form::text('group_name', $group->group_name, array('class' => 'required', 'placeholder'=> Lang::get('content.group_name_placeholder'))) }}
                    {{ $errors->first('group_name', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
                <label class="control-label required-field" for="status">{{ Lang::get('content.group_status') }}</label>
                <div class="controls">
                    {{ Form::select('status', $group_statuses, $group->status, array('class'=>'required')) }}
                    {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
            </div>

            {{ Form::hidden('id', $group->id) }}
            {{ Form::hidden('referer', $referer) }}

            <a href="{{ $referer }}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
            <button class="btn btn-medium btn-warning" type="submit">
                @if (!isset($group->id))
                    {{Lang::get('content.continue')}}
                @else
                    {{Lang::get('content.save')}}
                @endif
            </button>

        {{ Form::close() }}

    </div><!--/row-fluid-->

@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
//        jQuery('.group-form').validateBootstrap();

    });
</script>
@stop