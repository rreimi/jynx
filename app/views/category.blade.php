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
                    <img src="{{URL::to('uploads/pub/' . $pub->id . '/' . $pub->images[0]->image_url )}}" alt="Image"/>
                    <h2 class="pub-title">{{ $pub->title }}</h2>
                    <span class="pub-seller">{{Lang::get('content.sell_by')}}: {{ $pub->publisher->seller_name }}</span>
                    <p class="pub-short-desc">{{ $pub->short_description }}</p>
                    <p><a class="btn see-pub-link" href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">{{Lang::get('content.see_product_detail')}} &raquo;</a></p>
                </div>
            </div><!--/span-->
        @if ((($key+1)%3) == 0)
        </div>
        @endif
    @endforeach
    {{ $publications->links() }}
</div><!--/row-->
@stop