@extends('layout_backend')

@section('content')
    <div class="row-fluid">

        {{ Form::open(array('url' => 'dashboard/approve','class'=>'','id'=>'approve_form')) }}

            <table class="table table-bordered table-condensed">
                <h2>{{ Lang::get('content.backend_users_section_title') }}</h2>
                <thead>
                <tr>
                    <th>{{ Lang::get('content.backend_full_name') }}</th>
                    <th>{{ Lang::get('content.backend_email') }}</th>
                    <th>{{ Lang::get('content.backend_id') }}</th>
                    <th>{{ Lang::get('content.backend_seller') }}</th>
                    <th>{{ Lang::get('content.backend_phone') }}</th>
                    <th>{{ Lang::get('content.backend_date') }}</th>
                    <th></th>
                    <th>{{ Form::checkbox('all', 0, 0, array('class' => 'chk-all', 'enabled' => 'false')) }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->publisher->letter_rif_ci }}-{{ $user->publisher->rif_ci }}</td>
                        <td>{{ $user->publisher->seller_name }}</td>
                        <td>{{ $user->publisher->phone1 }} @if (!empty($user->publisher->phone2)) / {{ $user->publisher->phone2 }} @endif</td>
                        <td>{{ date(Lang::get('content.date_format_php'),strtotime($user->publisher->created_at)) }}</td>
                        <td>
                            <a rel="tooltip" title="{{Lang::get('content.view_publisher')}}" class="btn" type="button"
                               target="_blank" href="{{URL::to('anunciante/editar/'. $user->publisher->id) }}">
                                <i class="icon-search"></i>
                            </a>
                        </td>
                        <td>{{ Form::checkbox('approve_users[]', $user->id, in_array($user->id,Input::old('approve_users',array())), array('class' => 'chk-pending-approval')) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-right">
                <input type="hidden" name="approve" id="approve" value=""/>
                <button class="btn btn-warning not_approve" type="button">{{ Lang::get('content.backend_user_not_approve') }}</button>
                <button class="btn btn-success approve" type="button">{{ Lang::get('content.backend_user_approve') }}</button>
            </div>

        {{ Form::close() }}

        <table class="table table-bordered table-condensed">
            <h2>{{ Lang::get('content.backend_reports_section_title') }}</h2>
            <thead>
            <tr>
                <th>{{ Lang::get('content.backend_report_user') }}</th>
                <th>{{ Lang::get('content.backend_report_publication') }}</th>
                <th>{{ Lang::get('content.backend_report_publisher') }}</th>
                <th>{{ Lang::get('content.backend_report_date') }}</th>
                <th>{{ Lang::get('content.backend_report_status') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach ($reports as $key => $rep)
                    <tr>
                        <td>{{ $rep->user->full_name }}</td>
                        <td>{{ $rep->publication->title }}</td>
                        <td>{{ $rep->publication->publisher->seller_name }}</td>
                        <td>{{ date(Lang::get('content.date_format_php'),strtotime($rep->date)) }}</td>
                        <td>{{ Lang::get('content.status_report_'. $rep->status) }}</td>
                        <td>
                            <a rel="tooltip" title="{{Lang::get('content.view_publication')}}" class="btn" type="button"
                               target="_blank" href="{{URL::to('/publicacion/detalle/'. $rep->publication->id) }}">
                                <i class="icon-search"></i>
                            </a>
                            <a rel="tooltip" title="{{Lang::get('content.check_report')}}" class="btn report-modal" type="button"
                                        data-target="#viewReport" data-remote="{{URL::to('denuncia/detalle/'. $rep->id) }}">
                                <i class="icon-pencil"></i>
                            </a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="viewReport" class="modal hide fade" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>{{ Lang::get('content.backend_report_view_title') }}</h3>
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

        jQuery('.not_approve').on('click',function(){
            jQuery('#approve').val(false);
            jQuery('#approve_form').submit();
        });

        jQuery('.approve').on('click',function(){
            jQuery('#approve').val(true);
            jQuery('#approve_form').submit();
        });

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

        jQuery('.chk-all').change(function(){
            var checked = jQuery(this).is(':checked');
            jQuery(".chk-pending-approval").prop('checked', checked);
        });

    });
</script>
@stop