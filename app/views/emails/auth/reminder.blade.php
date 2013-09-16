<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <img src="http://dev.jynx.com/img/logo.png">
</div>
<br/>
<br/>
<div class="container main-container well-small" id="body">
    <div class="row-fluid">
        <div class="span9">
            Estimado usuario,<br/>
            <br/>
            Para recuperar su contraseña haga clic <a href="{{ URL::to('/restaurar/#'.$token) }}">aquí</a>.<br/>
            <br/>
            Atentamente,<br/>
            El equipo de TuMercato.com<br/>
            ESTE MENSAJE FUE ENVIADO AUTOMATICAMENTE Y NO RECIBE RESPUESTAS.<br/>
            Para cualquier información adicional, comentario o sugerencia: http://www.tumercato.com/contactanos/  o envíe un mensaje a: {{ Config::get('emails/addresses.email_info') }}
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