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
                var token=hash.replace('token/','');
                Mercatino.resetForm.show(token);
            }
        }

        Mercatino.registerForm.init();
        Mercatino.loginForm.init();
    });
</script>
@endif