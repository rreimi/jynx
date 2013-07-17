@extends('layout_home')

@section('sidebar')
@parent
<p>This is appended to the master sidebar.</p>
@stop

@section('content')

<div class="hero-unit">
    <!--                <h1>Hello, world!</h1>-->
    <!--                <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>-->
    <!--                <p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p>-->
</div>

<h2>{{Lang::get('content.mostvisited_items')}}</h2>
<ul class="row-fluid  most-visited-items dashboard-item-list">
    @foreach ($mostvisited as $key => $pub)
    <div class="span3 pub-thumb">

        <div class="put-info-box">
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
    @endforeach
</ul><!-- pub-images-box -->

<h2>{{Lang::get('content.recent_items')}}</h2>
<ul class="row-fluid recent-items dashboard-item-list">
    @foreach ($recent as $key => $pub)
    <div class="span3 pub-thumb">
        <div class="put-info-box">
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
    @endforeach
</ul><!-- pub-images-box -->

@stop

