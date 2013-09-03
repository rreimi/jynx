<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

Aqui va el Header


<div class="container main-container well-small" id="body">
    <div class="row-fluid">
        <div class="span9">
            @if ($contentEmail == 'new_user_welcome')
                @include('emails.new_user_welcome')
            @elseif ($contentEmail == 'admin_notification_new_adviser')
                @include('emails.admin_notification_new_adviser')
            @elseif ($contentEmail == 'approved_user_notification')
                @include('emails.approved_user_notification')
            @elseif ($contentEmail == 'admin_notification_new_report')
                @include('emails.admin_notification_new_report')
            @endif
        </div><!--/span-->
    </div><!--/row-->
</div><!--/.fluid-container-->
<footer>
    Aqui va el Footer
</footer>

</body>
</html>