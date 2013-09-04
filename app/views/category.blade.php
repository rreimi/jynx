@extends('layout_home')

@section('sidebar')

@if (isset($category))
    <h1>{{ $category->name }}</h1>
    @if (count($category->subcategories) > 0)
    <span class="nav-header">{{ Lang::get('content.sub_categories_title') }}</span>
    <ul id="cats_for_{{$category->id}}" class="level2">
        @foreach ($category->subcategories as $subcat)
        <li>
            @if (isset($category) && ($subcat->id == $category->id))
            <a class="active" nohref><b>{{ $subcat->name }}</b></a>
            @else
            <a href="{{ URL::to('cat/' . $subcat->slug)}}">{{ $subcat->name }}</a>
            @endif
            @if (count($subcat->subcategories) > 0)
            <ul id="cats_for_{{$subcat->id}}" class="level3">
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
    <hr/>
    @endif
@endif
    @include('include.filter_sidebar')
@stop

@section('content')
    <div class="publication-grid">
    @if (count($publications) === 0)
        <h5 class="alert alert-warning">{{Lang::get('content.search_no_results', array('item' => Lang::choice('content.publication',2), 'criteria' => $category->name))}}</h5>
    @endif

    @foreach ($publications as $key => $pub)
        @if ($key % 3 == 0)
        <div class="row-fluid">
        @endif
            <div class="span4 pub-thumb">
                <div class="pub-info-box">
                    @if (isset($pub->images[0]))
                    <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
                        <img class="pub-img-small"  src="{{ Image::path('/uploads/pub/' . $pub->id . '/' . $pub->images[0]->image_url, 'resize', $thumbSize['width'], $thumbSize['height'])  }}" alt="{{ $pub->title }}"/>
                    </a>
                    @endif
                    <div class="pub-info-desc">
                        <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
                            <h2 class="pub-title">{{ $pub->title }}</h2>
                        </a>
                        <span class="pub-seller">{{Lang::get('content.sell_by')}}: {{ $pub->publisher->seller_name }}</span>
                        <!--                <p class="pub-short-desc"> $pub->short_description </p>-->
                    </div>
                </div>
            </div><!--/span-->
        @if (((($key+1)%3) == 0) || ($key+1 == count($publications)))
        </div><!--/div.row-fluid-->
        @endif
    @endforeach
    </div>
    {{ $publications->appends(Input::except('page'))->links() }}
</div><!--/row-->
@stop