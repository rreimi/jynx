@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid">
        {{ Form::open(array('method' => 'post', 'class' => 'form-horizontal')) }}
        <h1>{{Lang::get('content.new_publication')}}</h1>

        <div class="control-group">
            <label class="control-label" for="title">{{ Lang::get('content.title') }}</label>
            <div class="controls">
                {{ Form::text('title', null, array('placeholder'=> Lang::get('content.title'))) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="short_description">{{ Lang::get('content.short_description') }}</label>
            <div class="controls">
                {{ Form::text('short_description', null, array('class' => 'input-xxlarge', 'placeholder'=> Lang::get('content.short_description'))) }}
            </div>
        </div>


        <div class="control-group">
            <label class="control-label" for="long_description">{{ Lang::get('content.long_description') }}</label>
            <div class="controls">
                {{ Form::textarea('long_description', null, array('class' => 'input-xxlarge', 'placeholder'=> Lang::get('content.long_description'))) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select('status', $pub_statuses) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="from_date">{{ Lang::get('content.from_date') }}</label>
            <div class="controls">
                {{ Form::text('from_date', null, array('class' => 'datepicker', 'placeholder' => Lang::get('content.date_format'))) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="to_date">{{ Lang::get('content.to_date') }}</label>
            <div class="controls">
                {{ Form::text('to_date', null, array('class' => 'datepicker', 'placeholder' => Lang::get('content.date_format'))) }}
            </div>
        </div>
        <a href="#" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
        <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.save')}}</button>
        {{ Form::close() }}
    </div><!--/row-fluid-->
@stop