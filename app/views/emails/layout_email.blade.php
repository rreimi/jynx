<!DOCTYPE html>
<html>
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
            @endif
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