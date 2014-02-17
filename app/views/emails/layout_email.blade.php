<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    {{ HTML::image('img/logo.png')}}
</div>
<br/>
<br/>
<div class="container main-container well-small" id="body">
    <div class="row-fluid">
        <div class="span9">
            @if ($contentEmail == 'new_user_welcome')
                @include('emails.new_user_welcome')
            @elseif ($contentEmail == 'admin_notification_new_adviser')
                @include('emails.admin_notification_new_adviser')
            @elseif ($contentEmail == 'approved_user_notification')
                @include('emails.approved_user_notification')
            @elseif ($contentEmail == 'disapproved_user_notification')
                @include('emails.disapproved_user_notification')
            @elseif ($contentEmail == 'admin_notification_new_report')
                @include('emails.admin_notification_new_report')
            @elseif ($contentEmail == 'user_notification_publication_next_expire')
                @include('emails.user_notification_publication_next_expire')
            @elseif ($contentEmail == 'general_notification')
                @include('emails.general_notification')
            @elseif ($contentEmail == 'contactUs')
                @include('emails.contactUs')
            @elseif ($contentEmail == "restore_user_password")
                @include('emails.restore_user_password')
            @elseif ($contentEmail == "user_suspended")
                @include('emails.user_suspended')
            @endif

            Atentamente,<br/>
            El equipo de TuMercato.com<br/>
            <br/>
            ESTE MENSAJE FUE ENVIADO AUTOMATICAMENTE Y NO RECIBE RESPUESTAS.<br/>
            Para cualquier información adicional, comentario o sugerencia: <a href="http://www.tumercato.com/contactanos">http://www.tumercato.com/contactanos</a> o envíe un mensaje a: {{ Config::get('emails/addresses.email_info') }}
        </div><!--/span-->
    </div><!--/row-->
</div><!--/.fluid-container-->
<br/>
<br/>
<footer>
    <div style="line-height: 45px; text-align: center; background-color: #2C2C2C; color:#AAAAAA">
        {{Lang::get('content.copyright')}} <a href="http://www.androb.com" target="_blank" style="color: #AAAAAA">{{Lang::get('content.androb')}}</a>
    </div>
</footer>
</body>
</html>