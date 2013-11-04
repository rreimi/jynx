<div class="pub-rating-box">
    @if ($pub->rating_avg != "")
    {{ RatingHelper::getRatingBar($pub->rating_avg) }}
    @endif
</div>
<div class="pub-info-box">
    @if (isset($pub->image_url))
    <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
        <img class="pub-img-small"  src="{{ UrlHelper::imageUrl('/uploads/pub/' . $pub->id . '/' . $pub->image_url, '_' . $thumbSize['width']) }}" alt="{{ $pub->title }}"/>
    </a>
    @endif
    <div class="pub-info-desc">
        <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
            <h2 class="pub-title" title="{{ $pub->title }}">{{ TextHelper::truncate($pub->title, 45) }}</h2>
        </a>
        <span class="pub-seller masterTooltip" title="{{ $pub->seller_name }}">{{Lang::get('content.sell_by')}}: {{ $pub->seller_name }}</span>
    </div>
</div>

