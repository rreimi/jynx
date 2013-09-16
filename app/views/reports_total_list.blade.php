@extends('layout_backend')

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

        {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'rep_list_form')) }}
            <div class="control-group">
                <div class="controls">
                    {{Form::text('q', $state['q'], array('class' => 'input-large filter-field', 'placeholder' => Lang::get('content.reports_search_placeholder')))}}
                    {{ Form::select('filter_status', $rep_statuses, $state['filter_status'], array('class' => 'input-medium filter-field')) }}
                    <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
                </div>
            </div>
            {{ Form::hidden('filtering_type', $filteringType) }}
            {{ Form::hidden('filtering_id', $filteringId) }}
        {{ Form::close() }}

        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th>{{ Lang::get('content.backend_report_id') }}</th>
                <th>{{ Lang::get('content.backend_report_user') }}</th>
                <th>{{ Lang::get('content.backend_report_publication') }}</th>
                <th>{{ Lang::get('content.backend_report_comment') }}</th>
                <th>{{ Lang::get('content.backend_report_date') }}</th>
                <th>{{ Lang::get('content.backend_report_status') }}</th>
                <th>-</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($reports as $key => $rep)
                    <tr>
                        <td>{{ $rep->id }}</td>
                        <td><a href="{{ URL::to('denuncia/lista/usuario/'. $rep->user->id) }}">{{ $rep->user->full_name }}</a></td>
                        <td><a href="{{ URL::to('denuncia/lista/publicacion/'. $rep->publication->id) }}">{{ $rep->publication->title }}</a></td>
                        <td>{{ $rep->comment }}</td>
                        <td>{{ $rep->date }}</td>
                        <td>{{ Lang::get('content.backend_report_status_'. $rep->status) }}</td>
                        <td>
                            <a rel="tooltip" title="{{Lang::get('content.view')}}" class="btn report-modal" type="button"
                                        data-target="#viewReport" data-remote="{{URL::to('denuncia/detalle-info/'. $rep->id) }}">
                                <i class="icon-search"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $reports->links() }}

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

    });
</script>
@stop