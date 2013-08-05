@section('sidebar')
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
    <button class="btn reset-fields" type="button">{{Lang::get('content.reset_search')}}<i class="icon-remove"></i></button>
    <hr/>
</div>
{{ Form::close() }}
@parent
@endsection

@section('content')
<div class="row-fluid">
    @if ($user->isPublisher())
    <h1>{{Lang::get('content.my_publications')}} <a href="{{URL::to('publicacion/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_publication')}}</a></h1>
    @else
    <h1>{{Lang::choice('content.publication',2)}}</h1>
    @endif

    <table class="pub-table @if ($user->isAdmin()) admin @endif table table-bordered table-condensed">
        <thead>
        <tr>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('title')}}">{{Lang::get('content.title')}} <i class="{{UrlHelper::getSortIcon('title')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('created_at')}}">{{Lang::get('content.created_at')}} <i class="{{UrlHelper::getSortIcon('created_at')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('from_date')}}">{{Lang::get('content.from_date')}} <i class="{{UrlHelper::getSortIcon('from_date')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('to_date')}}">{{Lang::get('content.to_date')}} <i class="{{UrlHelper::getSortIcon('to_date')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.status')}} <i class="{{UrlHelper::getSortIcon('status')}}"></i></a></th>
            @if ($user->isAdmin())
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('seller_name')}}">{{Lang::get('content.seller_name')}} <i class="{{UrlHelper::getSortIcon('seller_name')}}"></i></a></th>
            @endif
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('visits_number')}}">{{Lang::get('content.visits_number')}} <i class="{{UrlHelper::getSortIcon('visits_number')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('rating_avg')}}">{{Lang::get('content.rating_avg')}} <i class="{{UrlHelper::getSortIcon('rating_avg')}}"></i></a></th>
            <!--                        <th>{{Lang::get('content.category_name')}}</th>-->
            <th class="options"></th>
        </tr>
        </thead>
        <tbody>
        @if (count($publications) > 0)
        @foreach ($publications as $key => $pub)
        <tr>
            <td class="row-title"><b>{{ e($pub->title) }}</b>
                <span class="title-with-categories">{{ $pub->categories }})</span>
            </td>
            <td class="row-created">{{ date(Lang::get('content.date_format_php'),strtotime($pub->created_at)) }}</td>
            <td class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($pub->from_date)) }}</td>
            <td class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($pub->to_date)) }}</td>
            <td class="row-status">{{ Lang::get('content.status_publication_' . $pub->status) }}</td>
            @if ($user->isAdmin())
            <td class="row-seller_name">{{ $pub->seller_name }}</td>
            @endif
            <td class="row-visits_number">{{ $pub->visits_number }}</td>
            <td class="row-rating_avg">{{ $pub->rating_avg }}</td>
            <!--                        <td>{{ $pub->categories_name }}</td>-->
            <td class="row-options">
                <a rel="tooltip" target="_blank"  title="{{Lang::get('content.view')}}" class="btn btn-mini" href="{{URL::to('publicacion/detalle/' . $pub->id)}}">
                    <i class="icon-search"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn btn-mini" href="{{URL::to('publicacion/editar/' . $pub->id)}}">
                    <i class="icon-pencil"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn btn-mini" href="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_publication_delete_title') }}', '{{ Lang::get('content.modal_publication_delete_content') }}', '{{URL::to('publicacion/eliminar/' . $pub->id)}}')">
                    <i class="icon-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="8"><div class="text-center">{{Lang::get('content.no_elements_to_list')}}</div></td>
        </tr>
        @endif
        </tbody>
    </table>
    {{ $publications->appends(Input::except('page'))->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('select.filter-field').bind('change', function(){
            //jQuery('#pub_list_form').submit();
        });

        jQuery('.chosen-select').chosen({
            width: "100%"
        });

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('.sidebar-search-form').submit();
        });

        /* Filter fields */

            /* All date filters */
            jQuery('.datepicker').datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true
            });

            /* Set dynamic date range */
            jQuery('.datepicker.from-start-date').bind("change", function(){
                jQuery('.datepicker.to-start-date').datepicker( "option", "minDate", jQuery(this).val());
            });
            jQuery('.datepicker.to-start-date').bind("change", function(){
                jQuery('.datepicker.from-start-date').datepicker( "option", "maxDate", jQuery(this).val());
            });

            /* Set dynamic date range */
            jQuery('.datepicker.from-end-date').bind("change", function(){
                jQuery('.datepicker.to-end-date').datepicker( "option", "minDate", jQuery(this).val());
            });
            jQuery('.datepicker.to-end-date').bind("change", function(){
                jQuery('.datepicker.from-end-date').datepicker( "option", "maxDate", jQuery(this).val());
            });

        });
</script>
@stop