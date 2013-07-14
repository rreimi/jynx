@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')

    <div class="row-fluid">
        {{ Form::open(array('url' => 'publicacion/guardar', 'method' => 'post', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) }}
        @if (!isset($publication->id))
        <h1>{{Lang::get('content.new_publication')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_publication')}}: {{ $publication->title }}</h1>
        @endif
        <div class="control-group">
            <label class="control-label" for="title">{{ Lang::get('content.title') }}</label>
            <div class="controls">
                {{ Form::text('title', $publication->title, array('placeholder'=> Lang::get('content.title'))) }}
                {{ $errors->first('title', '<p class="error">:message</p>') }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="short_description">{{ Lang::get('content.short_description') }}</label>
            <div class="controls">
                {{ Form::text('short_description', $publication->short_description, array('class' => 'input-xxlarge', 'placeholder'=> Lang::get('content.short_description'))) }}
                {{ $errors->first('short_description', '<p class="error">:message</p>') }}
            </div>
        </div>


        <div class="control-group">
            <label class="control-label" for="long_description">{{ Lang::get('content.long_description') }}</label>
            <div class="controls">
                {{ Form::textarea('long_description', $publication->long_description, array('class' => 'input-xxlarge', 'placeholder'=> Lang::get('content.long_description'))) }}
                {{ $errors->first('long_description', '<p class="error">:message</p>') }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select('status', $pub_statuses, $publication->status) }}
                {{ $errors->first('status', '<p class="error">:message</p>') }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="from_date">{{ Lang::get('content.from_date') }}</label>
            <div class="controls">
                {{ Form::text('from_date', date("d-m-Y",strtotime($publication->from_date)), array('class' => 'datepicker from-date', 'placeholder' => Lang::get('content.date_format'))) }}
                {{ $errors->first('from_date', '<p class="error">:message</p>') }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="to_date">{{ Lang::get('content.to_date') }}</label>
            <div class="controls">
                {{ Form::text('to_date', date("d-m-Y",strtotime($publication->to_date)), array('class' => 'datepicker to-date', 'placeholder' => Lang::get('content.date_format'))) }}
                {{ $errors->first('to_date', '<p class="error">:message</p>') }}
            </div>
        </div>

        <h2>{{Lang::get('content.publication_categories')}}</h2>

        @foreach ($categories as $cat)
            <dt>
            <label class="checkbox">
                {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $publication_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
            </label>
            </dt>
            <dd>
                @foreach ($cat->subcategories as $subcat)
                <label class="checkbox">
                    {{ Form::checkbox('categories[]', $subcat->id, in_array($subcat->id, (array) $publication_categories), array('class' => 'chk-sub-cat', 'data-parent-id' => $cat->id)) }} {{ $subcat->name }}
                </label>
                @endforeach
            </dd>

        @endforeach

        {{ Form::hidden('id', $publication->id) }}
        {{ Form::hidden('publisher_id', $publication->publisher_id) }}

        <a href="#" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
        <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.save')}}</button>
        {{ Form::close() }}
    </div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
<script type="text/javascript">
    jQuery(document).ready(function(){

        /* All date filters */
        jQuery('.datepicker').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true
        });

        /* Set dynamic date range */
        jQuery('.datepicker.from-date').bind("change", function(){
            jQuery('.datepicker.to-date').datepicker( "option", "minDate", jQuery(this).val());
        });
        jQuery('.datepicker.to-date').bind("change", function(){
            jQuery('.datepicker.from-date').datepicker( "option", "maxDate", jQuery(this).val());
        });

        /* When subcat got checked parents also */
        jQuery('.chk-sub-cat').bind('click', function() {
            jQuery('input.chk-cat[value=' + jQuery(this).attr('data-parent-id') + ']').attr('checked', true);
        })
    });
</script>
@stop
