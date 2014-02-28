<div class="text-center report-actions">
    <h2 class="text-center">{{ Lang::get('content.report_view_actions_title') }}</h2>

    <br/>

    <div>
        <a href="{{ URL::to('publicacion/detalle/'. $report->publication->id) }}" class="btn btn-info btn-small btn-action" data-id="valid-report">{{ Lang::get('content.report_view_delete_report') }}</a>
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
        <button class="btn-info btn-small btn-action" data-dismiss="modal">{{ Lang::get('content.report_view_skip') }}Nuevo</button>
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
                case "suspend-publication":
                    url = '{{URL::to("publicacion/suspender/". $report->publication->id) }}'
                    break;
                case "suspend-publisher":
                    url = '{{URL::to("anunciante/suspender/". $report->publication->publisher->user_id) }}'
                    break;
                case "suspend-user":
                    url = '{{URL::to("usuario/suspender/". $report->user_id) }}'
                    break;
            }

            jQuery.ajax({
                url: url,
                cache: false,
                success: function(html){
                    window.parent.location.reload();
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