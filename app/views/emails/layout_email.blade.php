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
            @endif
        </div><!--/span-->
    </div><!--/row-->
</div><!--/.fluid-container-->
<footer>
    Aqui va el Footer
</footer>

</body>
</html>