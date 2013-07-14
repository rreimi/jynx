<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }} - {{ App::environment() }}
    </title>
    <meta name="description" content="Mercatino"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/module.css') }}
</head>
<body class="login">
    <div class="container">
        @yield('content')
    </div>
    {{ HTML::script('js/jquery-1.10.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
</body>
</html>