<!-- Stored in app/views/layouts/master.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }} - {{ App::environment() }}
    </title>
    <meta name="description" content="Mercatino"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/jquery-ui-1.10.3.custom.min.css') }}
    {{ HTML::style('css/jquery.pnotify.default.css') }}
    {{ HTML::style('css/jquery.pnotify.default.icons.css') }}
    {{ HTML::style('css/chosen.min.css') }}
    {{ HTML::style('css/bar-rating.css') }}
    {{ HTML::style('css/basic.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/module.css') }}
</head>
<body>
    <header id="header" class="site-header">
        @include('include.header')
    </header>

    <div class="container-fluid slider-area">
        @section('slideshow')
        @show
    </div>

    <div id="body" class="container main-container">
        <div class="row-fluid">
            <div class="span12">
                @yield('content')
            </div><!--/span-->
        </div><!--/row-->
    </div><!--/.fluid-container-->

    <footer id="footer" class="container-fluid">
        @section('footer')
            @include('include.footer')
        @show
    </footer>

    @section('modal-confirm')
    @include('include.modal_confirm')
    @show
</footer>

@section('modal-remainder')
@include('include.modal_remainder')
@show

@section('modal-confirm')
@include('include.modal_confirm')
@show

@section('modal-register')
@include('include.modal_register')
@show

@section('scripts')
@include('include.scripts')
@show
</body>
</html>