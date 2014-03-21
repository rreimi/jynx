<div class="row-fluid search-sidebar-box hide">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'report_list_form')) }}
    <div class="span11 reporter-list-filters">
        <span class="nav-header">{{ Lang::get('content.backend_search_reports_title') }}</span>
        <div class="control-group">
            <div class="controls">
                {{Form::text('q', $state['q'], array('class' => 'input-large filter-field', 'placeholder' => Lang::get('content.user_search_placeholder')))}}
                <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
            </div>
        </div>

        <div id="search-options-box" class="more-search-options collapse in">
            <div class="control-group">
                <label class="control-label text-left" for="filter_status">{{ Lang::get('content.filter_publication_status') }}</label>
                <div class="controls">
                    {{ Form::select('filter_status', $rep_statuses, $state['filter_status'], array('class' => 'filter-field')) }}
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="filter_reporters">{{ Lang::get('content.filter_report_reporter') }}</label>
            <div class="controls">
                {{ Form::select('filter_reporters[]', $rep_reporters, $state['filter_reporters'], array('id' => 'filter_reporters', 'multiple' => '', 'class' => 'chosen-select input filter-field', 'data-placeholder' => Lang::get('content.filter_reporter_placeholder'))) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="filter_publications">{{ Lang::get('content.filter_report_publication') }}</label>
            <div class="controls">
                {{ Form::select('filter_publications[]', $rep_publications, $state['filter_publications'], array('id' => 'filter_publications', 'multiple' => '', 'class' => 'chosen-select input filter-field', 'data-placeholder' => Lang::get('content.filter_publication_placeholder'))) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="filter_publishers">{{ Lang::get('content.filter_report_publisher') }}</label>
            <div class="controls">
                {{ Form::select('filter_publishers[]', $rep_publishers, $state['filter_publishers'], array('id' => 'filter_publishers', 'multiple' => '', 'class' => 'chosen-select input filter-field', 'data-placeholder' => Lang::get('content.filter_publisher_placeholder'))) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="filter_start_date">{{ Lang::get('content.backend_report_date_created') }}</label>
            <div class="controls">
                {{ Form::text('date_start_date', $state['date_start_date'], array('id' => 'filter_start_date','class' => 'datepicker date-start-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
                {{ Form::text('final_status_start_date', $state['final_status_start_date'], array('class' => 'datepicker final-status-start-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="filter_end_date">{{ Lang::get('content.backend_report_date_resolved') }}</label>
            <div class="controls">
                {{ Form::text('date_end_date', $state['date_end_date'], array('id' => 'filter_end_date', 'class' => 'datepicker date-end-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
                {{ Form::text('final_status_end_date', $state['final_status_end_date'], array('class' => 'datepicker final-status-end-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
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
