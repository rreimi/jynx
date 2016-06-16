<a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
    <div class="pub-rating-box">
        @if ($pub->rating_avg != "")
        {{ RatingHelper::getRatingBar($pub->rating_avg) }}
        @endif
    </div>
</a>
<div class="pub-info-box">
    <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
        <div class="pub-img-box">
            @if (isset($pub->image_url))
            <img class="pub-img-small"  src="{{ UrlHelper::imageUrl('/uploads/pub/' . $pub->id . '/' . $pub->image_url, '_' . $thumbSize['width']) }}" alt="{{ $pub->title }}"/>
            @else
            <img class="pub-img-small"  src="{{ UrlHelper::imageUrl('/img/default_image.jpg', '_' . $thumbSize['width']) }}" alt="{{ $pub->title }}"/>
            @endif
        </div>
    </a>
    <div class="pub-info-desc">
        <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
            <h2 class="pub-title" title="{{ $pub->title }}">{{ TextHelper::truncate($pub->title, 40) }}</h2>
        </a>
        <span class="pub-seller masterTooltip" title="{{ $pub->seller_name }}">{{Lang::get('content.sell_by')}} <a href="{{ URL::to('search?seller=' . $pub->publisher_id)}}"> {{ $pub->seller_name }} </a></span>
    </div>
</div>

