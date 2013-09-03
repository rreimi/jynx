@extends('layout_home_no_sidebar')


@section('slideshow')
<div id="home-banner-top" class="home-slideshow carousel container slide">

    <ol class="carousel-indicators">
        @foreach ($activeadvertisings as $key => $adv)
        <li data-target="#home-banner-top" data-slide-to="{{ $key }}" @if ($key == 0) class="active" @endif></li>
        @endforeach
    </ol>

    <div class="carousel-inner">
        @foreach ($activeadvertisings as $key => $adv)
        <div class="item @if ($key == 0) active @endif">
            <img class="main-banner-img"  src="{{ Image::path('/uploads/adv/' . $adv->id . '/' . $adv->image_url, 'resizeCrop', $bannerTopHomeSize['width'], $bannerTopHomeSize['height'])->responsive('max-width=940', 'resize', 724) }}" alt="{{ $adv->name }}"/>
        </div>
        @endforeach
    </div>

    <!--a data-slide="prev" href="#home-banner-top" class="left carousel-control">‹</a-->
    <!--a data-slide="next" href="#home-banner-top" class="right carousel-control">›</a-->
</div><!-- pub-images-box -->
@stop

@section('content')
<?php //var_dump($activeadvertisings); die(); ?>

<h2>{{Lang::get('content.mostvisited_items')}}</h2>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="carousel slide product-carousel" id="mostvisited-carousel">
            <div class="carousel-inner">
                @foreach ($mostvisited as $key => $pubVisit)
                @if ($key%4 == 0)
                <div class="item @if ($key==0) active @endif">
                    <ul class="row-fluid most-visited-items dashboard-item-list thumbnails">
                @endif
                        <li class="span3 pub-thumb">
                            <div class="put-info-box">
                                @if (isset($pubVisit->publication->images[0]))
                                <a href="{{ URL::to('publicacion/detalle/' . $pubVisit->publication->id)}}">
                                    <img class="pub-img-small"  src="{{ Image::path('/uploads/pub/' . $pubVisit->publication->id . '/' . $pubVisit->publication->images[0]->image_url, 'resize', $thumbSize['width'], $thumbSize['height']) }}" alt="{{ $pubVisit->publication->title }}"/>
                                </a>
                                @endif
                                <div class="pub-info-desc">
                                    <a href="{{ URL::to('publicacion/detalle/' . $pubVisit->publication->id)}}">
                                        <h2 class="pub-title">{{ $pubVisit->publication->title }}</h2>
                                    </a>
                                    <span class="pub-seller">{{Lang::get('content.sell_by')}}: {{ $pubVisit->publication->publisher->seller_name }}</span>
                                    <!--                <p class="pub-short-desc"> $pubVisit->publication->short_description </p>-->
                                </div>
                            </div>
                        </li>
                @if (((($key+1)%4) == 0) || (($key+1) == count($mostvisited)))
                    </ul>
                </div>
                @endif
                @endforeach
            </div>
            <a data-slide="prev" href="#mostvisited-carousel" class="left carousel-control"></a>
            <a data-slide="next" href="#mostvisited-carousel" class="right carousel-control"></a>
        </div>
    </div>
</div>

<h2>{{Lang::get('content.recent_items')}}</h2>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="carousel slide product-carousel" id="recentitems-carousel">
            <div class="carousel-inner">
                @foreach ($recent as $key => $pub)
                @if ($key%4 == 0)
                <div class="item @if ($key==0) active @endif">
                    <ul class="row-fluid recent-items dashboard-item-list thumbnails">
                        @endif
                        <li class="span3 pub-thumb">

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
                            </a>
                        </li>
                @if (((($key+1)%4) == 0) || (($key+1) == count($recent)))
                    </ul>
                </div>
                @endif
                @endforeach
            </div>
            <a data-slide="prev" href="#recentitems-carousel" class="left carousel-control"></a>
            <a data-slide="next" href="#recentitems-carousel" class="right carousel-control"></a>
        </div>
    </div>
</div>

@if ($lastvisited)
    <h2>{{Lang::get('content.last_visited_items')}}</h2>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="carousel slide product-carousel" id="lastvisiteditems-carousel">
                <div class="carousel-inner">
                    @foreach ($lastvisited as $key => $pub)
                    @if ($key%4 == 0)
                    <div class="item @if ($key==0) active @endif">
                        <ul class="row-fluid last-visited-items dashboard-item-list thumbnails">
                            @endif
                            <li class="span3 pub-thumb">
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
                            </li>
                            @if (((($key+1)%4) == 0) || (($key+1) == count($lastvisited)))
                        </ul>
                    </div>
                    @endif
                    @endforeach
                </div>
                <a data-slide="prev" href="#lastvisiteditems-carousel" class="left carousel-control"></a>
                <a data-slide="next" href="#lastvisiteditems-carousel" class="right carousel-control"></a>
            </div>
        </div>
    </div>
@endif

@stop

@section('scripts')
@parent
{{ HTML::script('js/imagecow.js') }}
<script type="text/javascript">
    Imagecow.init();

    jQuery(document).ready(function(){
        jQuery('#home-banner-top.carousel').carousel({
            interval: 8000
        });
        jQuery('#mostvisited-carousel.carousel').carousel({
            interval: false
        });
        jQuery('#recentitems-carousel.carousel').carousel({
            interval: false
        });
        jQuery('#lastvisiteditems-carousel.carousel').carousel({
            interval: false
        });
    });
</script>
@stop
