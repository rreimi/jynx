<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'jobListForm')) }}
    <div class="span11 pub-list-filters">
        <span class="nav-header">{{ Lang::get('content.search_job_title') }}</span>
        <div class="control-group">
            <div class="controls">
                {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
                <button class="btn btn-warning" type="submit">{{Lang::get('content.search')}}</button>
            </div>

        </div>

        <div id="search-options-box" class="more-search-options collapse in">
            <div class="control-group">
                <label class="control-label text-left" for="filter_state">{{ Lang::get('content.location') }}: </label>
                <div class="controls">
                    {{ Form::select('filter_state', $states, $state['filter_state'], array('id' => 'filter-state' ,'class' => 'input filter-field')) }}
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label text-left" for="filter_areas">{{ Lang::get('content.filter_areas') }}</label>
            <div class="controls">
                {{ Form::select('filter_areas[]', $areas, $state['filter_areas'], ['id' => 'filter-areas', 'multiple' => '', 'class' => 'chosen-select input filter-field', 'data-placeholder' => Lang::get('content.filter_select_areas')]) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="filter_job_date">{{ Lang::get('content.filter_job_date') }}</label>
            <div class="controls">
                {{ Form::text('from_job_date', $state['from_job_date'], ['id' => 'from-job-date','class' => 'datepicker from-job-date input-small filter-field', 'placeholder' => Lang::get('content.date_format')]) }}
                {{ Form::text('to_job_date', $state['to_job_date'], ['id' => 'to-job-date','class' => 'datepicker to-job-date input-small filter-field', 'placeholder' => Lang::get('content.date_format')]) }}
            </div>
        </div>

        @if ($state['active_filters'] > 0)
        <div class="control-group">
            <div class="controls">
                <button class="btn btn-small reset-fields" type="button">{{Lang::get('content.reset_search')}} <i class="icon-remove"></i></button>
            </div>
        </div>
        @endif
    </div>
    {{ Form::close() }}
</div>

@section('scripts')
@parent
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.chosen-select').chosen({
            width: "100%"
        });

        jQuery('.datepicker').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true
        });

        var fromDate=jQuery('.datepicker.from-job-date');
        var toDate=jQuery('.datepicker.to-job-date');

        fromDate.on("change", function(){
            toDate.datepicker('option','minDate',jQuery(this).val());
        });
        toDate.bind("change", function(){
            fromDate.datepicker('option','maxDate',jQuery(this).val());
        });

        jQuery('.reset-fields').on('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('#jobListForm').submit();
        });
    });
</script>
@stop