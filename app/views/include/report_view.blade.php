<p>
    <b>{{ Lang::get('content.report_view_user_name') }}:</b> {{ $report->user->full_name }}
</p>

<p>
    <b>{{ Lang::get('content.report_view_publication_name') }}:</b> {{ $report->publication->title }}
</p>

<p>
    <b>{{ Lang::get('content.report_view_publisher_name') }}:</b> {{ $report->publication->publisher->seller_name }}
</p>

<p>
    <b>{{ Lang::get('content.report_view_report_date') }}:</b> {{ $report->date }}
</p>

<p>
    <b>{{ Lang::get('content.report_view_report_comment') }}:</b> {{ $report->comment }}
</p>

<br/>

<div class="text-center report-actions">
    <div>
        <a nohref class="btn btn-warning btn-small btn-report" data-id="valid-report">{{ Lang::get('content.valid_report') }}</a>
        <a nohref class="btn btn-warning btn-small btn-report" data-id="invalid-report">{{ Lang::get('content.invalid_report') }}</a>
    </div>
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
                    if (action == 'valid-report'){
                        loadActions();
                    } else if (action == 'invalid-report'){
                        window.parent.location.reload();
                    }
                }, error: function(html){
                    var resp = html.responseText.substring(1, html.responseText.length - 1);
                    if (resp){
                        Mercatino.showFlashMessage({title:'', message: "{{ Lang::get('content.report_message_not_exist') }}", type:'error'});
                    } else {
                        Mercatino.showFlashMessage({title:'', message: "{{ Lang::get('content.report_message_invalid_data') }}", type:'error'});
                    }
                }
            });
        });

        function loadActions(){
            jQuery.ajax({
                url: '{{URL::to("denuncia/acciones/". $report->id) }}',
                cache: false,
                success: function(html){
                    jQuery('#viewReport').children('.modal-body').html(html);
                    jQuery('#viewReport').modal('show');
                }
            });
        }

    });
</script>