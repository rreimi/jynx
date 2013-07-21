{{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => '')) }}
<div class="input-append">
    {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'span12')) }}
    <button class="btn" type="submit"><i class="icon-search"></i></button>
</div>
{{ Form::close() }}

@if(!empty($custom_title))
    <span class="nav-header">{{ $custom_title }}</span>
@endif
@if(!empty($custom_options) && count($custom_options))
    <ul class="nav nav-tabs nav-stacked side-nav">

        @foreach ($custom_options as $custom_name => $custom_anchor)
        <li>
            <a href="{{ $custom_anchor }}">{{ $custom_name }}</a>
        </li>
        @endforeach
    </ul>
@endif

<span class="nav-header">{{ Lang::get('content.categories_title') }}</span>
<ul class="nav nav-tabs nav-stacked side-nav">

    @foreach ($categories as $cat)
    <li>
        <a href="{{ URL::to('cat/' . $cat->slug)}}"><i class="{{ count($cat->subcategories)>0? 'icon-chevron-down':'' }}"></i>{{ $cat->name }}</a>
    </li>
        @foreach ($cat->subcategories as $subcat)
        <li class="sub-category">
            <a class="sub-category-text" href="{{ URL::to('cat/' . $subcat->slug)}}">{{ $subcat->name }}</a>
        </li>
        @endforeach
    @endforeach
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
