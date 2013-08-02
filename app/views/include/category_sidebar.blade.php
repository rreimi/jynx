{{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => '')) }}
<div class="row-fluid">
    <div class="span12">
        <div class="input-append span12">
            {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => 'span10')) }}
            <button class="btn" type="submit"><i class="icon-search"></i></button>
        </div>
    </div>
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

@if (isset($activeFilters) && (count($activeFilters) > 0))
<span class="nav-header">{{ Lang::get('content.filter_active') }}</span>
<ul class="nav-filter-active">
    @foreach ($activeFilters as $type => $filter)
    <li>
        <div class="label">{{ Lang::get('content.filter_' . $filter->type . '_title') }}: {{$filter->label}}<a class="close" href="{{ UrlHelper::fullExcept(array($filter->type)) }}">&times;</a></div>
    </li>
    @endforeach
</ul>
@endif

@if (isset($availableFilters))
<span class="nav-header">{{ Lang::get('content.filter_available') }}</span>
@foreach ($availableFilters as $type => $filters)
<span class="nav-header nav-header-subtitle">{{ Lang::get('content.filter_' . $type . '_title') }}</span>
<ul class="nav-filter-available">
    @foreach ($filters as $filter)
    <li>
        <a href="{{ URL::full() . '&' . $filter->type . '=' . $filter->item_id }}">{{$filter->label}} ({{$filter->total}})</a>
    </li>
    @endforeach
</ul>
@endforeach
@endif


<span class="nav-header">{{ Lang::get('content.categories_title') }}</span>
<ul class="nav-category">
    @foreach ($categories as $cat)
    <li class="@if (isset($category) && ($cat->id == $category->id)) active @endif">
        @if (isset($category) && ($cat->id == $category->id))
        <a class="active" nohref><b>{{ $cat->name }}</b></a>
        @else
        <a href="{{ URL::to('cat/' . $cat->slug)}}">{{ $cat->name }}</a>
        @endif

        @if (count($cat->subcategories) > 0)
        <i data-toggle="collapse" data-target="#cats_for_{{$cat->id}}" class="icon-chevron {{ (isset($category) && ($cat->id == $category->id))? 'icon-chevron-right':'icon-chevron-down' }}"></i>
        <ul id="cats_for_{{$cat->id}}" class="level2 {{ (isset($category_tree) && (in_array($cat->id, $category_tree)))? 'collapse in':'collapse' }}">
            @foreach ($cat->subcategories as $subcat)
            <li>
                @if (isset($category) && ($subcat->id == $category->id))
                    <a class="active" nohref><b>{{ $subcat->name }}</b></a>
                @else
                    <a href="{{ URL::to('cat/' . $subcat->slug)}}">{{ $subcat->name }}</a>
                @endif
                @if (count($subcat->subcategories) > 0)
                <i data-toggle="collapse" data-target="#cats_for_{{$subcat->id}}" class="icon-chevron {{ (isset($category) && ($subcat->id == $category->id))? 'icon-chevron-right':'icon-chevron-down' }}"></i>
                <ul id="cats_for_{{$subcat->id}}" class="level3 {{ (isset($category_tree) && (in_array($subcat->id, $category_tree)))? 'collapse in':'collapse' }}">
                    @foreach ($subcat->subcategories as $thirdcat)
                    <li>
                        @if (isset($category) && ($thirdcat->id == $category->id))
                        <a class="active" nohref><b>{{ $thirdcat->name }}</b></a>
                        @else
                        <a href="{{ URL::to('cat/' . $thirdcat->slug)}}">{{ $thirdcat->name }}</a>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>