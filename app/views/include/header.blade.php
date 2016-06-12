<!-- Be sure to leave the brand out there if you want it shown -->

<div class="container">
    <div class="row">
        <div class="logo span2">
            <a class="brand" href="{{URL::to('/')}}">{{ HTML::image('img/logo.png')}}</a>
        </div>
        <div class="span10 header-text">
            <div class="navbar header-menu-container float-left">
                <div class="navbar-inner">
                    <div class="container">
                        <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>

                        <!-- Everything you want hidden at 940px or less, place within here -->
                        <div class="nav-collapse collapse">
                            <ul class="nav public-menu">
                                <li class="category-menu-link">
                                    <a href="{{URL::to('cat')}}">{{Lang::get('content.products_and_services')}}</a>
                                    <div class="products-services-menu">

                                    </div>
                                    @include('include.category_tree')
                                </li>

                                <li><a href="{{URL::to('directorio')}}">{{Lang::get('content.directory')}}</a></li>

                                <li><a href="{{URL::to('bolsa-trabajo')}}">{{Lang::get('content.jobs')}}</a></li>

                                <li><a href="{{URL::to('acerca-de')}}">{{Lang::get('content.about_us')}}</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-form float-right">
                <div class="row float-left header-right">
                    <div class="float-left">
                        {{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => 'form-inline top-menu-search')) }}
                        <div class="input-append">
                            <button class="search" type="submit"><i class="icon-search icon-gray"></i></button>
                            {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'input-block-level')) }}

                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="header-login-form float-left">
                        @if(Auth::check())
                            <div class="btn-group user-menu">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    {{ Auth::user()->email }}
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ URL::to('perfil') }}">{{ Lang::get('content.auth_menu_my_profile') }}</a></li>
                                    <li><a href="{{ URL::to('mi-directorio') }}">{{ Lang::get('content.my_directory') }}</a></li>
                                    @if(Auth::user()->isPublisher())
                                        <li><a href="{{URL::to('/publicacion/lista')}}">{{Lang::get('content.my_publications')}}</a></li>
                                        <li ><a href="{{URL::to('/bolsa-trabajo/lista')}}">{{Lang::get('content.my_jobs')}}</a></li>
                                    @endif
                                    @if(Auth::user()->isAdmin() || Auth::user()->isSubAdmin())
                                        <li><a href="{{URL::to('/estadisticas')}}">{{ Lang::get('content.admin_dashboard') }}</a></li>
                                    @endif
                                    @if(Auth::user()->canBePublisher())
                                        <li><a href="{{URL::to('/registro/datos-anunciante')}}">{{ Lang::get('content.postulation') }}</a></li>
                                    @endif
                                    <li class="divider"></li>
                                    <li><a href="{{ URL::to('logout') }}">{{ Lang::get('content.exit') }}</a></li>
                                </ul>
                            </div>
                        @else
                        @endif
                        <div class="guest-options">
                            <ul class="register-login">
                                @if(Auth::check())
                                    @if (Auth::user()->canBePublisher())
                                        <li><a href="{{URL::to('/registro/datos-anunciante')}}">{{Lang::get('content.register_dialog_header')}}</a>
                                        </li>
                                    @endif
                                @else
                                    <li><a nohref onclick="Mercatino.registerForm.show();">{{Lang::get('content.register_signup')}}</a><a href="{{URL::to('/login')}}">{{Lang::get('content.login_signin')}}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
