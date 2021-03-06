@extends('layout_home')

@section('sidebar')
    @if (!is_null($category))
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
        @include('include.filter_sidebar')
        @endif
    @else
        <h1>Productos</h1>
        <ul class="level2">
            @foreach ($categories as $cat)
            <li>
                <a href="{{ URL::to('cat/' . $cat->slug)}}">{{ $cat->name }}</a>
            </li>
            @endforeach
        </ul>
        <h1>Servicios</h1>
        <ul class="level2">
            @foreach ($services as $cat)
            <li>
                <a href="{{ URL::to('cat/' . $cat->slug)}}">{{ $cat->name }}</a>
            </li>
            @endforeach
        </ul>
    @endif
@stop

@section('content')



    <div class="publication-grid">
        @if (is_null($category))
        <h2 class="home-title"><span class="title-arrow">&gt;</span> {{Lang::get('content.recent_items')}}</h2>
        @endif
        @if (count($publications) === 0)
            <h5 class="alert alert-warning">{{Lang::get('content.search_no_results', array('item' => Lang::choice('content.publication',2), 'criteria' => $category->name))}}</h5>
        @endif

        @foreach ($publications as $key => $pub)
            @if ($key % 3 == 0)
            <div class="row-fluid">
            @endif
                <div class="span4 pub-thumb">
                    @include('include.publication_box')
                </div><!--/span-->
            @if (((($key+1)%3) == 0) || ($key+1 == count($publications)))
            </div><!--/div.row-fluid-->
            @endif
        @endforeach
    </div>
    {{ $publications->appends(Input::except('page'))->links() }}
</div><!--/row-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
//    jQuery(document).ready(function(){
//        Mercatino.longTextTooltips();
//    });
</script>
@stop