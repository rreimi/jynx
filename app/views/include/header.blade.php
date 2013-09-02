<!-- Be sure to leave the brand out there if you want it shown -->

<div class="container">
    <div class="row">
        <div class="logo span2">
            <a class="brand" href="{{URL::to('/')}}">{{ HTML::image('img/logo.png')}}</a>
        </div>

        <div class="header-form span10">
            <div class="row">
                <div class="span5">
                    {{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => 'form-inline top-menu-search')) }}
                    <div class="input-append">
                        {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'input-block-level')) }}
                        <button class="btn btn-warning" type="submit"><i class="icon-search icon-white"></i></button>
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="header-login-form span5">
                    {{ Form::open(array('method' => 'post', 'action' => 'HomeController@getSearch', 'class' => 'form-inline pull-right')) }}
                        {{ Form::text('q', '', array('placeholder' => Lang::get('content.login_email'), 'class' => 'input-medium')) }}
                        {{ Form::text('q', '', array('placeholder' => Lang::get('content.login_password'), 'class' => 'input-medium')) }}
                        <button type="submit" class="btn btn-primary btn-small">{{ Lang::get('content.login_signin') }}</button>
                    {{ Form::close() }}
                    <div class="guest-options clear-both">
                        <a href="{{URL::to('/olvido')}}">{{Lang::get('content.forgot_password')}}</a> | {{Lang::get('content.have_account')}} <a href="{{URL::to('/login')}}"><b>{{Lang::get('content.register_signup')}}</b></a>
                    </div>
                </div>
            </div>

        </div>

        <div class="navbar header-menu-container span9">
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
                        @if(Auth::check())
                        <ul class="nav user-menu">
                            <li class="divider-vertical"></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->email }} <b class="caret"></b></a>
                                <ul class="dropdown-menu">

                                    <li>
                                        <span class="category-tree-button">Categorías <i class="icon-chevron-down"></i></span>
                                    </li>
                                    <li><a href="{{ URL::to('perfil') }}">{{ Lang::get('content.auth_menu_my_profile') }}</a></li>
                                    @if(Auth::user()->isPublisher())
                                    <li><a href="{{URL::to('/publicacion/lista')}}">{{Lang::get('content.my_publications')}}</a></li>
                                    @endif
                                    @if(Auth::user()->isAdmin())
                                    <li><a href="{{URL::to('/dashboard')}}">{{ Lang::get('content.admin_dashboard') }}</a></li>
                                    @endif
                                    @if(Auth::user()->isBasic())
                                    <li><a href="{{URL::to('/registro/datos-anunciante')}}">{{ Lang::get('content.postulation') }}</a></li>
                                    @endif
                                    <li class="divider"></li>
                                    <li><a href="{{ URL::to('logout') }}">{{ Lang::get('content.exit') }}</a></li>

                                </ul>
                            </li>
                        </ul>
                        @endif
                        <ul class="nav public-menu">
                            <!--                    @if(Auth::check())-->
                            <!--                        <li class="active"><a href="{{URL::to('/')}}">{{Lang::get('content.home')}}</a></li>-->
                            <!--                    @endif-->

                            <li class="category-menu-link">
                                <a class="" href="{{URL::to('')}}">
                                    {{Lang::get('content.products_and_services')}}
                                </a>
                                @include('include.category_tree')
                            </li>

                            <li><a href="{{URL::to('')}}">{{Lang::get('content.about_us')}}</a></li>

                            <li><a href="{{URL::to('')}}">{{Lang::get('content.partners')}}</a></li>

                            <li><a href="{{URL::to('')}}">{{Lang::get('content.contact')}}</a></li>

                            <li><a href="{{URL::to('')}}">{{Lang::get('content.help')}}</a></li>
                            @if(Auth::check())

                            @if(Auth::user()->isPublisher())
                            <!--                            <li><a href="{{URL::to('/publicacion/lista')}}">{{Lang::get('content.my_publications')}}</a></li>-->
                            @endif

                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
