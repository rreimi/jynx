@extends('layout_backend')

@section('content')
    <div class="row-fluid">

        <table class="table table-bordered table-condensed">
            <h2>{{ Lang::get('content.backend_reports_total') }}</h2>
            <thead>
            <tr>
                <th>{{ Lang::get('content.backend_report_id') }}</th>
                <th>{{ Lang::get('content.backend_report_user') }}</th>
                <th>{{ Lang::get('content.backend_report_publication') }}</th>
                <th>{{ Lang::get('content.backend_report_date') }}</th>
                <th>{{ Lang::get('content.backend_report_status') }}</th>
                <th>-</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($reports as $key => $rep)
                    <tr>
                        <td>{{ $rep->id }}</td>
                        <td>{{ $rep->user->full_name }}</td>
                        <td>{{ $rep->publication->title }}</td>
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