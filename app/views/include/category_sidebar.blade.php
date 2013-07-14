{{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => '')) }}
<div class="input-append">
    {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'span12')) }}
    <button class="btn" type="submit"><i class="icon-search"></i></button>
</div>
{{ Form::close() }}

<ul class="nav nav-list">

    <li class="nav-header">{{ Lang::get('content.categories_title') }}</li>
    @foreach ($categories as $cat)
    <li><a href="{{ URL::to('cat/' . $cat->slug)}}">{{ $cat->name }}</a>
        @foreach ($cat->subcategories as $subcat)
        <a href="{{ URL::to('cat/' . $subcat->slug)}}">&raquo; {{ $subcat->name }}</a>
        @endforeach
    </li>
    @endforeach
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
