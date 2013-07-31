<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }} - {{ App::environment() }}
    </title>
    <meta name="description" content="Mercatino"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/module.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/jquery.pnotify.default.css') }}
    {{ HTML::style('css/jquery.pnotify.default.icons.css') }}
</head>
@include('include.top_menu')
<body class="login">
    <div class="container main-container">
        @yield('content')
    </div>
    @section('scripts')
        {{ HTML::script('js/jquery-1.10.1.min.js') }}
        {{ HTML::script('js/bootstrap.min.js') }}
        {{ HTML::script('js/jquery.pnotify.min.js') }}
        {{ HTML::script('js/jquery.validate.min.js') }}
        {{ HTML::script('js/messages_es.js') }}
        {{ HTML::script('js/mercatino.js') }}
        <script type="text/javascript">
            jQuery(document).ready(function(){
                Messages.configErrors({{ $errors }},"{{ Lang::get('content.site_messages_title_error') }}").show();
            })
        </script>
    @show

</body>
</html>