{{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => '')) }}
<div class="input-append">
    {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'span12')) }}
    <button class="btn" type="submit"><i class="icon-search"></i></button>
</div>

{{ Form::close() }}
<span class="nav-header">OPTIONS</span>
<ul class="nav nav-tabs nav-stacked side-nav">
    <li><a href="#">NAME</a></li>
    <li class="sub-category"><a class="sub-category-text" href="#">SUB</a></li>
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
