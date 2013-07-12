<!DOCTYPE html>
<head>
    <title>{{ $title }} - {{ App::environment() }}
    </title>
    <meta name="description" content="Mercatino"/>
    <meta name="viewport" content="width=device-width"/>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/module.css') }}
</head>
<body>

<div class="container">
    <header id="heading">
        @include('include.top_menu')
    </header>

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
    <hr>
    <footer>
        @section('footer')
            @include('include.footer')
        @show
    </footer>
</div>
@section('scripts')
{{ HTML::script('js/jquery-1.10.1.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
@show
</body>
</html>