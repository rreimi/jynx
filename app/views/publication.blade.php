@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid">
        <div id="pub-images-box" class="float-right pub-images-carousel carousel slide">
            <ol class="carousel-indicators">
                @foreach ($publication->images as $key => $img)
                    <li data-target="#pub-images-box" data-slide-to="{{ $key }}"></li>
                @endforeach
            </ol>

            <div class="carousel-inner">
                @foreach ($publication->images as $key => $img)
                <div class="item @if ($key == 0) active @endif">
                    <img class="pub-img-medium" src="{{URL::to('uploads/pub/' . $publication->id . '/' . $img->image_url )}}" alt="{{ $publication->title }}"/>
                </div>
                @endforeach
            </div>

            <a data-slide="prev" href="#pub-images-box" class="left carousel-control">‹</a>
            <a data-slide="next" href="#pub-images-box" class="right carousel-control">›</a>
        </div><!-- pub-images-box -->
        <h1>{{ $publication->title }}</h1>
        <div class="publisher-info">
            <span class="pub-seller pub-line">{{Lang::get('content.sell_by')}}: {{ $publication->publisher->seller_name }}</span>
            <span class="pub-phone pub-line">{{Lang::get('content.phone')}}:  {{ $publication->publisher->phone1 }}</span>
            <span class="pub-location pub-line">{{Lang::get('content.location')}}:  {{ $publication->publisher->city . ', ' . $publication->publisher->state->name }}</span>
            @if ($publication->publisher->phone2)
                <span class="pub-phone">{{Lang::get('content.phone')}}:  {{ $publication->publisher->phone2 }}</span>
            @endif
            <div class="contacs-info">
                <h2 class="contacts-title">{{ Lang::get('content.contacts')}}</h2>
                <ul class="contact-list">
                    @foreach ($publication->publisher->contacts as $contact)
                        <li>
                        {{ $contact->first_name . ' ' . $contact->last_name }}<br/>
                        {{ $contact->email }}<br/>
                        {{ $contact->phone }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div><!--/.publisher-info-->
        <p class="pub-short-desc">{{ $publication->short_description }}</p>
        <p class="pub-long-desc">{{ $publication->long_description }}</p>
        <div class="report-info">
            {{ Lang::get('content.report_publication_msg', array('url' => '#')) }}
        </div>
    </div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/jquery.barrating.min.js') }}
<script type="text/javascript">
    $('#pub_rating').barrating();
</script>
@stop