<!DOCTYPE html>
<head>
    <title>{{ $title }} - {{ App::environment() }}
    </title>
    <meta name="description" content="Mercatino"/>
    <meta name="viewport" content="width=device-width"/>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.min.css') }}
    {{ HTML::style('css/jquery.pnotify.default.css') }}
    {{ HTML::style('css/jquery.pnotify.default.icons.css') }}
    {{ HTML::style('css/basic.css') }}
    {{ HTML::style('css/base.css') }}
    {{ HTML::style('css/module.css') }}
</head>
<body>

@include('include.top_menu')
<div class="container-fluid main-container" id="body">

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
</div>
<footer>
    @section('footer')
    @include('include.footer')
    @show
</footer>

@section('modal-confirm')
@include('include.modal_confirm')
@show

@section('scripts')
{{ HTML::script('js/jquery-1.10.1.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/jquery.pnotify.min.js') }}
{{ HTML::script('js/mercatino.js') }}
{{ HTML::script('js/verge/verge.min.js') }}
{{ HTML::script('js/footer.js') }}
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