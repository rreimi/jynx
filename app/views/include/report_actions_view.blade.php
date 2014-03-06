<div class="text-center report-actions">
    <h2 class="text-center">{{ Lang::get('content.report_view_actions_title') }}</h2>

    <br/>

    <div>
        <a nohref class="btn btn-info btn-small btn-action" data-action="delete-comment">{{ Lang::get('content.report_view_delete_report') }}</a>
    </div>

    <br/>

    <div>
        <a nohref class="btn btn-info btn-small btn-action" data-action="suspend-publication">{{ Lang::get('content.report_view_suspend_publication') }}</a>
    </div>

    <br/>

    <div>
        <a nohref class="btn btn-info btn-small btn-action" data-action="suspend-publisher">{{ Lang::get('content.report_view_suspend_publisher') }}</a>
    </div>

    <br/>

    <div>
        <a nohref class="btn btn-info btn-small btn-action" data-action="suspend-user">{{ Lang::get('content.report_view_suspend_user') }}</a>
    </div>

    <br/>

    <div>
        <a nohref class="btn btn-info btn-small btn-action" data-action="skip">{{ Lang::get('content.report_view_skip') }}</a>
    </div>
</div>

{{ Form::hidden('report_id', $report->id) }}
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery('.report-actions .btn-action').on('click',function(){

            var action = jQuery(this).data('action');
            var url;

            switch (action){
                case "delete-comment":
                    url = '{{URL::to("denuncia/borrar-comentario/". $report->id) }}'
                    break;
                case "suspend-publication":
                    url = '{{URL::to("denuncia/suspender-publicacion/". $report->id) }}'
                    break;
                case "suspend-publisher":
                    url = '{{URL::to("denuncia/suspender-anunciante/". $report->id) }}'
                    break;
                case "suspend-user":
                    url = '{{URL::to("denuncia/suspender-usuario/". $report->id) }}'
                    break;
                case "skip":
                    url = '{{URL::to("denuncia/saltar/". $report->id) }}'
                    break;
            }

            jQuery.ajax({
                url: url,
                cache: false,
                success: function(html){
                    switch (action){
                        case "delete-comment":
                            window.location.replace("{{ URL::to('publicacion/detalle/'. $report->publication->id) }}");
                            break;
                        default:
                            window.parent.location.reload();
                            break;
                    }
                },
                error: function(html){
                    var resp = html.responseText.substring(1, html.responseText.length - 1);
                    switch (resp){
                        case 'report_actions_error_publication':
                            Mercatino.showFlashMessage({title:'', message: "{{ Lang::get('content.report_actions_error_publication') }}", type:'error'});
                            break;
                        case 'report_actions_error_publisher':
                            Mercatino.showFlashMessage({title:'', message: "{{ Lang::get('content.report_actions_error_publisher') }}", type:'error'});
                            break;
                        case 'report_actions_error_user':
                            Mercatino.showFlashMessage({title:'', message: "{{ Lang::get('content.report_actions_error_user') }}", type:'error'});
                            break;
                    }
                }
            });
        });

    });
</script>