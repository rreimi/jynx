@extends('layout_backend')

@section('sidebar')
@include('include.publication_report_table_sidebar')
@parent
@stop

@section('content')

    <div class="row-fluid">

        <h1>
            @if ($filteringType == 'usuario')
                {{ Lang::get('content.backend_report_by_user', array('u' => $reports[0]->user->full_name)) }}
            @elseif ($filteringType == 'publicacion')
                {{ Lang::get('content.backend_report_by_publication', array('p' => $reports[0]->publication->title)) }}
            @else
                {{ Lang::get('content.reports') }}
            @endif
        </h1>

        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th><a href="{{UrlHelper::fullUrltoogleSort('full_name')}}">{{ Lang::get('content.backend_report_user') }} <i class="{{UrlHelper::getSortIcon('user.full_name')}}"></i></th>
                <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('date')}}">{{ Lang::get('content.backend_report_date_created') }} <i class="{{UrlHelper::getSortIcon('date')}}"></i></th>
                <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('date')}}">{{ Lang::get('content.backend_report_date_resolved') }} <i class="{{UrlHelper::getSortIcon('date')}}"></i></th>
                <th><a href="{{UrlHelper::fullUrltoogleSort('publication.title')}}">{{ Lang::get('content.backend_report_publication') }} <i class="{{UrlHelper::getSortIcon('publication.title')}}"></i></th>
                <th><a href="{{UrlHelper::fullUrltoogleSort('publisher')}}">{{ Lang::get('content.backend_report_publisher') }} <i class="{{UrlHelper::getSortIcon('publisher')}}"></i></th>
                <th><a href="{{UrlHelper::fullUrltoogleSort('comment')}}">{{ Lang::get('content.backend_report_comments_in_publication') }} <i class="{{UrlHelper::getSortIcon('comment')}}"></i></th>
                <th><a href="{{UrlHelper::fullUrltoogleSort('status')}}">{{ Lang::get('content.backend_report_status') }} <i class="{{UrlHelper::getSortIcon('status')}}"></i></th>
                <th>-</th>
            </tr>
            </thead>
            <tbody>
                @if (count($reports) > 0)
                @foreach ($reports as $key => $rep)
                    <tr>
                        <td>{{ $rep->user['full_name'] }}</td>
                        <td>{{ date(Lang::get('content.date_format_php'),strtotime($rep->date)) }}</td>
                        <td>@if (!empty($rep->final_status))
                                {{ date(Lang::get('content.date_format_php'),strtotime($rep->final_status)) }}
                            @endif
                        </td>
                        <td>{{ $rep->publication['title'] }}</td>
                        <td>{{ $rep->publication['publisher']['seller_name'] }}</td>
                        <td>{{ $rep->reports_in_publication }}</td>
                        <td>{{ Lang::get('content.status_report_'. $rep->status) }}</td>
                        <td>
                            <a rel="tooltip" title="{{Lang::get('content.view')}}" class="btn report-modal" type="button"
                                        data-target="#viewReport" data-remote="{{URL::to('denuncia/detalle-info/'. $rep->id) }}">
                                <i class="icon-search"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="7"><div class="text-center">{{Lang::get('content.no_elements_to_list')}}</div></td>
                    </tr>
                @endif
            </tbody>
        </table>
        {{ $reports->appends(Input::only('sort','order'))->links() }}

        <div id="viewReport" class="modal hide fade" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>{{ Lang::get('content.backend_report_total_view_title') }}</h3>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">{{ Lang::get('content.close') }}</button>
            </div>
        </div>
    </div>


@stop

@section('scripts')
@parent
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
<script type="text/javascript">
    Mercatino.reportForm = {
        show: function(title, content, url){
            jQuery('#modal-report').modal('show');

        },
        hide: function(){
            jQuery('#modal-report').modal('hide')
        }
    };

    jQuery(document).ready(function(){

        jQuery('.chosen-select').chosen({
            width: "100%"
        });

        jQuery('.search-sidebar-box').fadeIn();

        jQuery('.report-modal').on('click',function(){
            var remote=jQuery(this).data('remote');
            var target=jQuery(this).data('target');

            jQuery.ajax({
                url: remote,
                cache: false,
                success: function(html){
                    jQuery(target).children('.modal-body').html(html);
                    jQuery(target).modal('show');
                }
            });
        });

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('#report_list_form').submit();
        });

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