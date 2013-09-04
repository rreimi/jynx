Estimado Administrador:<br/>
<br/>
Hay una nueva solicitud:<br/>
<br/>
{{ Lang::get('content.user_name') }}: {{ $advertiserData->full_name }}<br/>
{{ Lang::get('content.user_email') }}: {{ $advertiserData->email }}<br/>
{{ Lang::get('content.publisher_type') }}:
@if ($advertiserData->publisher_type == 'Person')
    {{ Lang::get('content.publisher_type_person') }}
@elseif (($advertiserData->publisher_type == 'Business')
    {{ Lang::get('content.publisher_type_business') }}
@endif
<br/>
{{ Lang::get('content.seller_name') }}: {{ $advertiserData->seller_name }}<br/>
{{ Lang::get('content.select_id_type') }}: {{ $advertiserData->letter_rif_ci }}<br/>
{{ Lang::get('content.publisher_id') }}: {{ $advertiserData->rif_ci }}<br/>
{{ Lang::get('content.state') }}: {{ $advertiserData->state_id }}<br/>
{{ Lang::get('content.phone1') }}: {{ $advertiserData->phone1 }}<br/>
{{ Lang::get('content.phone2') }}: {{ $advertiserData->phone2 }}<br/>
{{ Lang::get('content.publisher_media') }}: {{ $advertiserData->media }}<br/>
<br/>
Otorgue los permisos al nuevo anunciante <a href="{{ URL::to('dashboard') }}">aquí.</a><br/>
<br/>
ESTE MENSAJE FUE ENVIADO AUTOMATICAMENTE Y NO RECIBE RESPUESTAS.<br/>
Para cualquier información adicional, comentario o sugerencia: http://www.tumercato.com/contactenos/  o envíe un mensaje a: info@tumercato.com