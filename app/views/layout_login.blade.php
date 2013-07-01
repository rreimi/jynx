<html>
<head>
    <title>{{ $title }} - {{ App::environment() }}
    </title>
    <meta name="description" content="Mercatino"/>
    <meta name="viewport" content="width=device-width"/>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/module.css') }}
    <style>
        body {
            padding-top: 20px;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
        @if ($errors->has())
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        @endif
    </div>
    {{ HTML::script('js/jquery-1.10.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
</body>
</html>