<!-- Stored in app/views/layouts/master.blade.php -->
<!DOCTYPE html>
<head>
    <title>{{ $title }} - {{ App::environment() }}
    </title>
    <meta name="description" content="Mercatino"/>
    <meta name="viewport" content="width=device-width"/>
    {{ HTML::style('http://static.jynx.com/css/bootstrap.min.css') }}
    {{ HTML::style('http://static.jynx.com/css/bootstrap-responsive.min.css') }}
    {{ HTML::style('http://static.jynx.com/css/base.css') }}
    {{ HTML::style('http://static.jynx.com/css/module.css') }}
</head>
<body>

<div class="container">

    <header id="heading">
        @include('include.top_menu')
    </header>

    <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">
                @section('sidebar')
                    @include('include.category_sidebar')
                @show
            </div>
        </div><!--/span-->

        <div class="span9">
            @yield('content')
        </div><!--/span-->
    </div><!--/row-->
    <hr>

    <footer>
        @section('footer')
            @include('include.footer')
        @show
    </footer>

</div><!--/.fluid-container-->

@section('scripts')
{{ HTML::script('http://static.jynx.com/js/jquery-1.10.1.min.js') }}
{{ HTML::script('http://static.jynx.com/js/bootstrap.min.js') }}
@show
</body>
</html>