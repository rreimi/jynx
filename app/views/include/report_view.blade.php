<h2>{{ Lang::get('content.report_title') }}</h2>
<p>
    <b>{{ Lang::get('content.backend_report_id') }}:</b> {{ $report->id }} <br/>
    <b>{{ Lang::get('content.backend_report_comment') }}:</b><br/> {{ $report->comment }}
</p>

<h2>{{ Lang::get('content.backend_report_user') }}</h2>
<p>
    <b>{{ Lang::get('content.backend_report_id') }}:</b> {{ $report->user->id }}<br/>
    <b>{{ Lang::get('content.backend_report_user') }}:</b> {{ $report->user->full_name }}
</p>

<h2>{{ Lang::get('content.backend_report_publication') }}</h2>
<p>
    <b>{{ Lang::get('content.backend_report_id') }}:</b> {{ $report->publication->id }}<br/>
    <b>{{ Lang::get('content.title') }}:</b> {{ $report->publication->title }}
</p>

<br/>

<div class="text-center report-actions">
    <a nohref class="btn btn-success btn-small btn-report" data-id="valid-report">Denuncia valida</a>
    <a nohref class="btn btn-warning btn-small btn-report" data-id="invalid-report">Denuncia invalida</a>
    <a href="{{ URL::to('publicacion/editar/'. $report->publication->id) }}" class="btn btn-info btn-small" target="_blank">Suspender publicacion</a>
    <a href="{{ URL::to('usuario/editar/'. $report->user->id) }}" class="btn btn-danger btn-small" target="_blank">Suspender usuario</a>
</div>

{{ Form::hidden('report_id', $report->id) }}
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery('.report-actions .btn-report').on('click',function(){

            var action = jQuery(this).data('id');

            jQuery.ajax({
                url:  "{{ URL::to('denuncia/procesar/') }}",
                type: 'POST',
                data: { id: '{{ $report->id }}' , action: action },
                cache: false,
                success: function(html){
                    window.parent.location.reload();
                }, error: function(html){
                    var resp = html.responseText.substring(1, html.responseText.length - 1);
                    alert(resp);
                    if (resp){
                        Mercatino.showFlashMessage({title:'', message: "{{ Lang::get('content.report_message_not_exist') }}", type:'error'});
                    } else {
                        Mercatino.showFlashMessage({title:'', message: "{{ Lang::get('content.report_message_invalid_data') }}", type:'error'});
                    }
                }
            });
        });

    });
</script>