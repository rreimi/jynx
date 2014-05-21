<!DOCTYPE html>
<html lang="es">
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="Tumercato"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/module.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/jquery.pnotify.default.css') }}
    {{ HTML::style('css/jquery.pnotify.default.icons.css') }}
    @include('include.analytics')
</head>

<body class="login">

    <header id="header" class="site-header">
        @include('include.header')
    </header>

    <div id="body" class="container main-container">
        @yield('content')
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
@include('include.scripts')
<script type="text/javascript">
    jQuery(document).ready(function(){
        Messages.configErrors({{ $errors }},"{{ Lang::get('content.site_messages_title_error') }}").show();
    })
</script>
@show
</body>
</html>