<div class="container text-center">
    <div class="navbar">
        <ul class="nav">
            <li><a href="{{URL::to('/')}}">{{Lang::get('content.home')}}</a></li>
            <li><a href="{{URL::to('')}}">{{Lang::get('content.about_us')}}</a></li>
            @if (Auth::user()->role == 'Publisher')
            <li><a href="{{URL::to('/publicacion/lista')}}">{{Lang::get('content.my_publications')}}</a></li>
            @endif
            <li><a href="{{URL::to('/logout')}}">{{Lang::get('content.logout')}} ({{ Auth::user()->first_name . ' ' . Auth::user()->last_name }})</a></li>
        </ul>
    </div>
</div>