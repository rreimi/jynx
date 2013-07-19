@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <h1>{{ $category->name }}</h1>
    @foreach ($publications as $key => $pub)
        @if ($key % 3 == 0)
        <div class="row-fluid">
        @endif
            <div class="span4 pub-thumb">
                <div class="put-info-box">
                    @if (isset($pub->images[0]))
                    <img class="pub-img-small"  src="{{ Image::path('/uploads/pub/' . $pub->id . '/' . $pub->images[0]->image_url, 'resize', $thumbSize['width'], $thumbSize['height'])  }}" alt="{{ $pub->title }}"/>
                    @endif
                    <div class="pub-info-desc">
                        <h2 class="pub-title">{{ $pub->title }}</h2>
                        <span class="pub-seller">{{Lang::get('content.sell_by')}}: {{ $pub->publisher->seller_name }}</span>
                        <!--                <p class="pub-short-desc"> $pub->short_description </p>-->
                    </div>
                </div>
            </div><!--/span-->
        @if ((($key+1)%3) == 0)
        </div>
        @endif
    @endforeach
    {{ $publications->links() }}
</div><!--/row-->
@stop