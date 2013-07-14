<div class="navbar">
    <div class="navbar-inner">
        <div class="container">

            <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <!-- Be sure to leave the brand out there if you want it shown -->
            <a class="brand" href="{{URL::to('/')}}">{{ $title }}</a>



            <!-- Everything you want hidden at 940px or less, place within here -->
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="{{URL::to('/')}}">{{Lang::get('content.home')}}</a></li>
                    <li><a href="{{URL::to('')}}">{{Lang::get('content.about_us')}}</a></li>
                    <li><a href="{{URL::to('/publicacion/lista')}}">{{Lang::get('content.my_publications')}}</a></li>
                </ul>
                <ul class="nav pull-right">
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->email }} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ URL::to('logout') }}">Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
