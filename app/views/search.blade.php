@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <h1>{{Lang::get('content.search_results')}}: {{ $q }}</h1>

    @if (count($publications) === 0)
    <p>{{Lang::get('content.search_no_results', array('item' => Lang::choice('content.publication',2), 'criteria' => $q))}}</p>
    @endif

    @foreach ($publications as $key => $pub)
        @if ($key % 3 == 0)
        <div class="row-fluid">
        @endif
            <div class="span4 pub-thumb">
                <div class="put-info-box">
                    @if (isset($pub->images[0]))
                    <img src="{{URL::to('uploads/pub/' . $pub->id . '/' . $pub->images[0]->image_url )}}" alt="Image"/>
                    @endif
                    <div class="pub-info-desc">
                        <h2 class="pub-title">{{ $pub->title }}</h2>
                        <span class="pub-seller">{{Lang::get('content.sell_by')}}: {{ $pub->publisher->seller_name }}</span>
                        <!--                <p class="pub-short-desc"> $pub->short_description </p>-->
                        <br/><a class="btn see-pub-link" href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">{{Lang::get('content.see_product_detail')}} &raquo;</a>
                    </div>
                </div>
            </div><!--/span-->
        @if ((($key+1)%3) == 0)
        </div>
        @endif
    @endforeach
    {{ $publications->appends(array('q' => $q))->links() }}
</div><!--/row-->
@stop