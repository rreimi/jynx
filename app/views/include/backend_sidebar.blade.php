@if (!Auth::guest())
    @if (Auth::user()->isAdmin())
        <ul class="nav nav-tabs nav-stacked side-nav">
            <li><a href="{{ URL::to('estadisticas')}}">{{ Lang::get('content.backend_menu_stats') }}</a></li>
            <li><a href="{{ URL::to('dashboard')}}">{{ Lang::get('content.backend_menu_dashboard') }}</a></li>
            <li><a href="{{ URL::to('usuario/lista')}}">{{ Lang::get('content.backend_menu_users') }}</a></li>
            <li><a href="{{ URL::to('grupo/lista')}}">{{ Lang::get('content.backend_menu_groups') }}</a></li>
            <li><a href="{{ URL::to('anunciante/lista')}}">{{ Lang::get('content.backend_menu_publishers') }}</a></li>
            <li><a href="{{ URL::to('publicacion/lista')}}">{{ Lang::get('content.backend_menu_publications') }}</a></li>
            <li><a href="{{ URL::to('publicidad/lista')}}">{{ Lang::get('content.backend_menu_advertisings') }}</a></li>
            <li><a href="{{ URL::to('denuncia/lista')}}">{{ Lang::get('content.backend_menu_reports') }}</a></li>
                <!--<li class="sub-category"><a class="sub-category-text" href="#">SUB</a></li>-->
        <!--    <li class="active"><a href="#">Link</a></li>-->
            <li><a href="{{ URL::to('dashboard/mass-email')}}">{{ Lang::get('content.backend_menu_mass_email') }}</a></li>
        </ul>
    @endif

    @if (Auth::user()->isSubAdmin())
        <ul class="nav nav-tabs nav-stacked side-nav">
            <li><a href="{{ URL::to('estadisticas')}}">P-{{ Lang::get('content.backend_menu_stats') }}</a></li>
            <li><a href="{{ URL::to('dashboard')}}">P-{{ Lang::get('content.backend_menu_dashboard') }}</a></li>
            <li><a href="{{ URL::to('usuario/lista')}}">P-{{ Lang::get('content.backend_menu_users') }}</a></li>
            <li><a href="{{ URL::to('anunciante/lista')}}">P-{{ Lang::get('content.backend_menu_publishers') }}</a></li>
            <li><a href="{{ URL::to('publicacion/lista')}}">P-{{ Lang::get('content.backend_menu_publications') }}</a></li>
            <li><a href="{{ URL::to('denuncia/lista')}}">P-{{ Lang::get('content.backend_menu_reports') }}</a></li>
            <li><a href="{{ URL::to('dashboard/mass-email')}}">P-{{ Lang::get('content.backend_menu_mass_email') }}</a></li>
        </ul>
    @endif

    @if (Auth::user()->isPublisher())
        <ul class="nav nav-tabs nav-stacked side-nav">
            <li class="main"><a nohref><b>{{ Lang::get('content.profile') }}</b></a></li>
            <li><a href="{{ URL::to('perfil#basico')}}">- {{ Lang::get('content.profile_edit_basic') }}</a></li>
            <li><a href="{{ URL::to('perfil#anunciante')}}">- {{ Lang::get('content.profile_edit_publisher') }}</a></li>
            <li><a href="{{ URL::to('perfil#sectores')}}">- {{ Lang::get('content.profile_edit_sectors') }}</a></li>
            <li><a href="{{ URL::to('perfil#contactos')}}">- {{ Lang::get('content.profile_edit_contacts') }}</a></li>
            <li><a nohref><b>{{Lang::get('content.backend_menu_publications')}}</b></a></li>
            <li><a href="{{ URL::to('publicacion/crear')}}">- {{ Lang::get('content.create_new_publication') }}</a></li>
            <li><a href="{{ URL::to('publicacion/lista')}}">- {{Lang::get('content.my_publications')}}</a></li>
            <li><a nohref><b>{{Lang::get('content.backend_menu_jobs')}}</b></a></li>
            <li><a href="{{ URL::to('bolsa-trabajo/lista')}}">- {{ Lang::get('content.my_jobs') }}</a></li>
            <li><a href="{{ URL::to('bolsa-trabajo/crear')}}">- {{ Lang::get('content.create_new_job') }}</a></li>
        </ul>
    @endif
@endif

<!--
o Editar Perfil (Todas las opciones llevarían a la sección de Editar perfil, colocando al usuario a la altura donde se encuentra cada encabezado respectivo)
 Datos básicos
 Datos de publicador
 Sectores de negocio
 Contactos
o Publicaciones
 Crear nueva publicación (lleva al formulario de “Nueva publicación”)
 Mis publicaciones (este lleva a la lista de publicaciones)
o Ofertas laborales
 Crear nueva oferta laboral  (lleva al formulario de “Nueva oferta”)
 Mis ofertas laborales  (este lleva a la lista de ofertas)
-->