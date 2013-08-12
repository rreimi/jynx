<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'pub_list_form')) }}
    <div class="span11 pub-list-filters">
        <span class="nav-header">{{ Lang::get('content.backend_search_publication_title') }}</span>
        <div class="control-group">
            <div class="controls">
                {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
                <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
            </div>

        </div>

        <div class="control-group">
            <div class="controls">
                <a class="cursor-pointer" data-toggle="collapse" data-target="#search-options-box">{{Lang::get('content.advanced_search')}}</a>
            </div>
        </div>

        <div id="search-options-box" class="more-search-options collapse in">
            <div class="control-group">
                <label class="control-label text-left" for="filter_status">{{ Lang::get('content.filter_publication_status') }}</label>
                <div class="controls">
                    {{ Form::select('filter_status', $pub_statuses, $state['filter_status'], array('class' => 'input filter-field')) }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label text-left" for="filter_categories">{{ Lang::get('content.filter_publication_category') }}</label>
                <div class="controls">
                    {{ Form::select('filter_categories[]', $pub_categories, $state['filter_categories'], array('multiple' => '', 'class' => 'chosen-select input filter-field', 'data-placeholder' => Lang::get('content.filter_category_placeholder'))) }}
                </div>
            </div>

            @if ($user->isAdmin())
            <div class="control-group">
                <label class="control-label" for="filter_publishers">{{ Lang::get('content.filter_publication_publisher') }}</label>
                <div class="controls">
                    {{ Form::select('filter_publishers[]', $pub_publishers, $state['filter_publishers'], array('multiple' => '', 'class' => 'chosen-select input filter-field', 'data-placeholder' => Lang::get('content.filter_publisher_placeholder'))) }}
                </div>
            </div>
            @endif

            <div class="control-group">
                <label class="control-label" for="start_date">{{ Lang::get('content.filter_publication_start_date') }}</label>
                <div class="controls">
                    {{ Form::text('from_start_date', $state['from_start_date'], array('class' => 'datepicker from-start-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
                    {{ Form::text('to_start_date', $state['to_start_date'], array('class' => 'datepicker to-start-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="end_date">{{ Lang::get('content.filter_publication_end_date') }}</label>
                <div class="controls">
                    {{ Form::text('from_end_date', $state['from_end_date'], array('class' => 'datepicker from-end-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
                    {{ Form::text('to_end_date', $state['to_end_date'], array('class' => 'datepicker to-end-date input-small filter-field', 'placeholder' => Lang::get('content.date_format'))) }}
                </div>
            </div>
            @if ($state['active_custom_filters'] > 0)
            <button class="btn btn-small reset-fields" type="button">{{Lang::get('content.reset_search')}} <i class="icon-remove"></i></button>
            @endif
        </div>
        <hr/>
    </div>
    {{ Form::close() }}
</div>