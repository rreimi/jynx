@extends('layout_backend')

@section('sidebar')
<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'report_list_form')) }}
    <div class="span11 user-list-filters">
        <span class="nav-header">{{ Lang::get('content.backend_search_user_title') }}</span>
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
                        <td><a href="{{ URL::to('denuncia/lista/usuario/'. $rep->user->id) }}">{{ $rep->user->full_name }}</a></td>
                        <td>{{ $rep->date }}</td>
                        <td>{{ $rep->final_status }}</td>
                        <td><a href="{{ URL::to('denuncia/lista/publicacion/'. $rep->publication->id) }}">{{ $rep->publication->title }}</a></td>
                        <td>{{ $rep->publication->publisher->seller_name }}</td>
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
    });
</script>
@stop