{{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => '')) }}
<div class="input-append">
    {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'span12')) }}
    <button class="btn" type="submit"><i class="icon-search"></i></button>
</div>

{{ Form::close() }}
<span class="nav-header">{{ Lang::get('content.backend_menu_title') }}</span>
<ul class="nav nav-tabs nav-stacked side-nav">
    <li><a href="{{ URL::to('dashboard')}}">{{ Lang::get('content.backend_menu_dashboard') }}</a></li>
    <li><a href="#">{{ Lang::get('content.backend_menu_users') }}</a></li>
    <li><a href="#">{{ Lang::get('content.backend_menu_publishers') }}</a></li>
    <li><a href="#">{{ Lang::get('content.backend_menu_publications') }}</a></li>
    <li><a href="{{ URL::to('publicidad/lista')}}">{{ Lang::get('content.backend_menu_advertisings') }}</a></li>
    <li><a href="#">{{ Lang::get('content.backend_menu_stats') }}</a></li>
<!--    <li class="sub-category"><a class="sub-category-text" href="#">SUB</a></li>-->
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
