{{ HTML::script('js/jquery-1.10.1.min.js') }}
{{ HTML::script('js/jquery.validate.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/bootstrap-fileupload.js') }}
{{ HTML::script('js/jquery.pnotify.min.js') }}
{{ HTML::script('js/mercatino.js') }}
{{ HTML::script('js/verge/verge.min.js') }}
{{ HTML::script('js/footer.js') }}
{{ HTML::script('js/messages_es.js') }}
{{ HTML::script('js/jquery.maskedinput.min.js') }}

@if (!is_null(Session::get('flash_global_message')))
<script type="text/javascript">
    jQuery(document).ready(function(){
        if (Mercatino) {
            Mercatino.showFlashMessage({{ Session::get('flash_global_message') }});
        }
    });
</script>
@endif

@if (Auth::guest())
<script type="text/javascript">
    jQuery(document).ready(function(){
        var hash=location.hash.substr(1);

        if(hash){
            if(hash.indexOf('token')!=-1){
                var elements=hash.split('/token/');
                var email=elements[0];
                var token=elements[1];
                Mercatino.resetForm.show(token,email);
            }
        }

        Mercatino.registerForm.init();
        Mercatino.loginForm.init();
    });
</script>
@endif

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-48140865-1', 'tumercato.com');
    ga('send', 'pageview');

</script>