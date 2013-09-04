<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{Lang::get('content.contactUs_email_new_message_subject')}}</h2>

{{ Lang::get('content.contactus_name') }}: {{ $name }}<br/>
{{ Lang::get('content.contactus_email') }}: {{ $email }}<br/>
{{ Lang::get('content.contactus_phone') }}: {{ $phone}}<br/>
{{ Lang::get('content.contactus_subject') }}: {{ $subject }}<br/>
{{ Lang::get('content.contactus_message') }}: <br/> {{ $contact_message }}<br/>

</body>
</html>