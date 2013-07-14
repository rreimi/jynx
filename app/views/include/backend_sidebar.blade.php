{{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => '')) }}
<div class="input-append">
    {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'span12')) }}
    <button class="btn" type="submit"><i class="icon-search"></i></button>
</div>

{{ Form::close() }}
<ul class="nav nav-list">
    <li class="nav-header">OPTIONS</li>
    <li><a href="#">NAME</a>

        <a href="#">SUB</a>

    </li>
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
