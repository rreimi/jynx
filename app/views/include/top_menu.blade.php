<div class="navbar navbar-inverse navbar-static-top">
    <div class="navbar-inner">
        <div class="container">

            <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <!-- Be sure to leave the brand out there if you want it shown -->
            <a class="brand" href="{{URL::to('/')}}">{{ HTML::image('img/logo.png')}}</a>



            <!-- Everything you want hidden at 940px or less, place within here -->
            <div class="nav-collapse collapse">
                @if(Auth::check())
                <ul class="nav pull-right">
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->email }} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ URL::to('perfil') }}">{{ Lang::get('content.auth_menu_my_profile') }}</a></li>
                            @if(Auth::user()->isPublisher() && Auth::user()->isApproved())
                                <li><a href="#">Mis publicaciones</a></li>
                            @endif
                            @if(Auth::user()->isBasic())
                                <li><a href="{{URL::to('/registro/datos-publicador')}}">{{Lang::get('content.postulation')}}</a></li>
                            @endif
                            <li class="divider"></li>
                            <li><a href="{{ URL::to('logout') }}">Salir</a></li>
                        </ul>
                    </li>
                </ul>
                @endif
                <ul class="nav pull-right">
                    @if(Auth::check())
                        <li class="active"><a href="{{URL::to('/')}}">{{Lang::get('content.home')}}</a></li>
                    @endif

                    <li><a href="{{URL::to('')}}">{{Lang::get('content.about_us')}}</a></li>

                    <li><a href="{{URL::to('')}}">{{Lang::get('content.partners')}}</a></li>

                    <li><a href="{{URL::to('')}}">{{Lang::get('content.contact')}}</a></li>

                    @if(Auth::check())
                        <li><a href="{{URL::to('')}}">{{Lang::get('content.help')}}</a></li>

                        @if(Auth::user()->isPublisher() && Auth::user()->isApproved())
                            <li><a href="{{URL::to('/publicacion/lista')}}">{{Lang::get('content.my_publications')}}</a></li>
                        @endif

                    @endif
                </ul>

            </div>

        </div>
    </div>
</div>
