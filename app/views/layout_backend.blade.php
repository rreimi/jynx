<!DOCTYPE html>
<html lang="es">
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="Tumercato"/>
    <meta name="viewport" content="width=device-width"/>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/jquery-ui-1.10.3.custom.min.css') }}
    {{ HTML::style('css/jquery.pnotify.default.css') }}
    {{ HTML::style('css/jquery.pnotify.default.icons.css') }}
    {{ HTML::style('css/chosen.min.css') }}
    {{ HTML::style('css/basic.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/module.css') }}
</head>
<body>
    <header id="header" class="site-header">
        @include('include.header')
    </header>

    <div id="body" class="container-fluid main-container">
        <div class="row-fluid">
            <div class="span3">
                <div class="sidebar-nav well well-small">
                    @section('sidebar')
                        @include('include.backend_sidebar')
                    @show
                </div>
            </div>

            <div class="span9">
                @yield('content')
            </div>
        </div>
    </div>

    <footer id="footer">
        @section('footer')
        @include('include.footer')
        @show
    </footer>

    @section('modal-confirm')
    @include('include.modal_confirm')
    @show

    @section('scripts')
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
    {{ HTML::style('css/bootstrap-tagsinput.css') }}
    @show

    @if (!is_null(Session::get('flash_global_message')))
    <script type="text/javascript">
        jQuery(document).ready(function(){
            if (Mercatino) {
                Mercatino.showFlashMessage({{ Session::get('flash_global_message') }});
            }
        });
    </script>
    @endif
</body>
</html>