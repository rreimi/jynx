@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid publication-detail">
        <div id="pub-images-box" class="float-right pub-images-carousel carousel slide">
            <ol class="carousel-indicators">
                @foreach ($publication->images as $key => $img)
                    <li data-target="#pub-images-box" data-slide-to="{{ $key }}"></li>
                @endforeach
            </ol>

            <div class="carousel-inner">
                @foreach ($publication->images as $key => $img)
                <div class="item @if ($key == 0) active @endif">
                    <img class="pub-img-medium"  src="{{ Image::path('/uploads/pub/' . $publication->id . '/' . $img->image_url, 'resizeCrop', $detailSize['width'], $detailSize['height'])  }}" alt="{{ $publication->title }}"/>
                </div>
                @endforeach
            </div>

            <a data-slide="prev" href="#pub-images-box" class="left carousel-control">‹</a>
            <a data-slide="next" href="#pub-images-box" class="right carousel-control">›</a>
        </div><!-- pub-images-box -->
        <h1>{{ $publication->title }}
<!--            if ($publication->publisher_id == Auth::user()->publisher->id)-->
<!--                <br/>-->
<!--                <a class="action btn btn-mini btn-info" href=" URL::to('publicacion/editar/' . $publication->id)}}">Lang::get('content.edit')}}</a>-->
<!--            endif-->
        </h1>
        <h2>{{Lang::get('content.descripcion')}}</h2>
        <p class="pub-short-desc">{{ $publication->short_description }}</p>
        <p class="pub-long-desc">{{ $publication->long_description }}</p>
        <h2>{{Lang::get('content.categories_title')}}</h2>
        <div class="publication-categories">
            <ul>
                @foreach ($publication->categories as $cat)
                <li>{{ $cat->name }}</li>
                @endforeach
            </ul>
        </div>

        <div class="publisher-info">
            <h2>{{Lang::get('content.sell_by_full')}}</h2>
            <p>
            <span class="pub-seller pub-line">{{ $publication->publisher->seller_name }}</span>
            <span class="pub-phone pub-line">{{Lang::get('content.phone')}}:  {{ $publication->publisher->phone1 }}</span>
            <span class="pub-location pub-line">{{Lang::get('content.location')}}:  {{ $publication->publisher->city . ', ' . $publication->publisher->state->name }}</span>
            @if ($publication->publisher->phone2)
                <span class="pub-phone">{{Lang::get('content.phone')}}:  {{ $publication->publisher->phone2 }}</span>
            @endif
            </p>
        </div><!--/.publisher-info-->

        @if (count($publication->contacts) > 0)
        <div class="contacs-info">
            <h2 class="contacts-title">{{ Lang::get('content.contacts')}}</h2>
            <ol class="contact-list">
                @foreach ($publication->contacts as $contact)
                <li>
                    {{ $contact->full_name }}<br/>
                    @if (isset($contact->distributor)) {{ $contact->distributor }}<br/> @endif
                    {{ $contact->email }}<br/>
                    {{ $contact->phone }}
                </li>
                @endforeach
            </ol>
        </div><!--/.contacs-info-->
        @endif

        @if (Auth::user()->id != $publication->publisher->user_id)
            <div class="report-info">
                <p>{{ Lang::get('content.report_publication_msg') }}: <a nohref class="btn btn-warning btn-small" id="report-link">{{Lang::get('content.report_it')}}</a></p>
            </div>
        @endif

        @include('include.modal_report')

    </div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/imagecow.js') }}
<script type="text/javascript">
    Imagecow.init();

    Mercatino.reportForm = {
        show: function(title, content, url){
            //jQuery('#modal-confirm .modal-header h3').html(title);
            //jQuery('#modal-confirm .modal-body p').html(content);
            //jQuery('#modal-confirm .modal-footer a.danger').attr('href', url);
            jQuery('#modal-report').modal('show');

        },
        hide: function(){
            jQuery('#modal-report').modal('hide')
        },
        send: function(){
            var comment = jQuery('#modal-report textarea').val();

            if (comment == ""){
                alert('{{Lang::get('content.report_commend_required')}}');
                return;
            }

            this.hide();
            //TODO send report
            Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.report_send_success')}}", type:'success'});
        }
    };

    jQuery(document).ready(function(){
      jQuery('#report-link').bind('click', function(){
          Mercatino.reportForm.show();
      })
    });
</script>
@stop