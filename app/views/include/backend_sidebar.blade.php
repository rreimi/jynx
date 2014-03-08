<ul class="nav nav-tabs nav-stacked side-nav">
    <li><a href="{{ URL::to('dashboard')}}">{{ Lang::get('content.backend_menu_dashboard') }}</a></li>
    <li><a href="{{ URL::to('usuario/lista')}}">{{ Lang::get('content.backend_menu_users') }}</a></li>
    <li><a href="{{ URL::to('anunciante/lista')}}">{{ Lang::get('content.backend_menu_publishers') }}</a></li>
    <li><a href="{{ URL::to('publicacion/lista')}}">{{ Lang::get('content.backend_menu_publications') }}</a></li>
    <li><a href="{{ URL::to('publicidad/lista')}}">{{ Lang::get('content.backend_menu_advertisings') }}</a></li>
    <li><a href="{{ URL::to('estadisticas')}}">{{ Lang::get('content.backend_menu_stats') }}</a></li>
    <li><a href="{{ URL::to('dashboard/mass-email')}}">{{ Lang::get('content.backend_menu_mass_email') }}</a></li>
    <!--    <li><a href="{{ URL::to('denuncia/lista')}}">{{ Lang::get('content.backend_menu_reports') }}</a></li>
        <li class="sub-category"><a class="sub-category-text" href="#">SUB</a></li>-->
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
