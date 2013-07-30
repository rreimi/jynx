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
    <a nohref class="btn btn-success btn-small" data-id="valid-report">Denuncia valida</a>
    <a nohref class="btn btn-warning btn-small" data-id="invalid-report">Denuncia invalida</a>
    <a nohref class="btn btn-info btn-small" data-id="suspend-publication">Suspender publicacion</a>
    <a nohref class="btn btn-danger btn-small" data-id="suspend-user">Suspender usuario</a>
</div>

{{ Form::hidden('report_id', $report->id) }}
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery('.report-actions .btn').on('click',function(){
            var action = jQuery(this).data('id');

            jQuery.ajax({
                url:  "{{ URL::to('denuncia/procesar/') }}",
                type: 'POST',
                data: { id: '{{ $report->id }}' , action: action },
                cache: false,
                success: function(html){
                    /*jQuery(target).children('.modal-body').html(html);
                    jQuery(target).modal('show');*/
                }
            });
        });

    });
</script>