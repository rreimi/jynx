@extends('layout_home_no_sidebar')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid publication-detail">
        <!-- Carousel -->
        <div id="job-images-box" class="float-right pub-images">
            <div class="pub-image-wrapper">
                <img src="{{ $companyPicture }}"/>
            </div>
        </div>


        <h1>{{ $job->company_name }}
            @if (!is_null(Auth::user()))
                @if (Auth::user()->isPublisher() && ($job->publisher_id == Auth::user()->publisher->id))
                    <br/>
                    <a class="action btn btn-mini btn-info" href="{{ URL::to('bolsa-trabajo/editar/' . $job->id)}}">{{ Lang::get('content.edit') }}</a>
                @endif
            @endif
        </h1><div class="triangle"></div>

        <div class="publication-info">
            <div>{{ $job->job_title }}</div>

            <div><b>{{Lang::get('content.vacancy')}}</b>: {{$job->vacancy}} </div>

            <div>
                <h2><span class="title-arrow">></span>{{Lang::get('content.descripcion')}}</h2>
                <p>{{ job->description }}</p>
            </div>

            <div>
                <h2><span class="title-arrow">></span>{{Lang::get('content.areas')}}</h2>
                <ul>

                </ul>
            </div>

            <div>
                <h2><span class="title-arrow">></span>{{Lang::get('content.concat')}}</h2>
                <p class="pub-email">{{Lang::get('content.user_email')}}:  {{ $job->contact_email }}</p>
            </div>
        </div>
        <div class="publication-buttons">

        <div class="clearfix"></div>

        {{ $publication->ratings }}

        @if ($lastvisited)
        <div class="last-visited-box">
            <h2 class="home-title">{{Lang::get('content.last_visited_items')}}</h2>
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
                                        <div class="pub-rating-box">
                                            @if ($pub->rating_avg != "")
                                            {{ RatingHelper::getRatingBar($pub->rating_avg) }}
                                            @endif
                                        </div>
                                        <div class="pub-info-box">
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
        </div>
        @endif

    </div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/jquery.barrating.min.js') }}
{{ HTML::script('js/imagecow.js') }}
<script type="text/javascript">
    Imagecow.init();

    Mercatino.reportForm = {
        show: function(){
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
                Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.report_commend_required')}}", type:'error'});
                return;
            }

            this.hide();

            jQuery.ajax({
                url: "{{ URL::to('denuncia/crear/') }}",
                type: 'POST',
                data: { publication_id: '{{ $publication->id }}' , comment: comment },
                success: function(result) {
                    Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.report_send_success')}}", type:'success'});
                    jQuery('#modal-report .modal-body textarea').val('');
                },
                error: function(result) {
                    Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.report_send_error')}}", type:'error'});
                }
            });
        }
    };

    Mercatino.rateitForm = {
        show: function(publicationId){
            //jQuery('#modal-confirm .modal-header h3').html(title);
            //jQuery('#modal-confirm .modal-body p').html(content);
            //jQuery('#modal-confirm .modal-footer a.danger').attr('href', url);
            jQuery('#rating-form').get(0).reset();
            jQuery('#modal-rateit').modal('show');
            jQuery('#rating-form input[name="rating_publication_id"]').val(publicationId);
            jQuery('#rating-sel').barrating('clear');

        },
        hide: function(){
            jQuery('#modal-rateit').modal('hide')
        },
        send: function(){
            var comment = jQuery('#modal-rateit textarea').val();

            if (comment == ""){
                Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.rating_comment_required')}}", type:'error'});
                return;
            }

            var formData = jQuery('#rating-form').serializeObject();
            var url = jQuery('#rating-form').attr('action');

            this.hide();

            jQuery.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(result) {
                    Mercatino.showFlashMessage({title:'', message: result.message, type:'success'});
                    jQuery('#rating-form').reset();
                },
                error: function(result) {
                    var data = result.responseJSON;
                    if (data.status_code == 'validation') {
                        for (var i = 0; i < data.errors.length; i++){
                            Mercatino.showFlashMessage({title:'', message: data.errors[i], type:'error'});
                        }
                        return false;
                    };

                    if (data.status_code == 'invalid_token') {
                        window.location.href = "/";
                    };
                }
            });
        }
    };

    jQuery(document).ready(function(){
        jQuery('#report-link').bind('click', function(){
          Mercatino.reportForm.show();
        });

        jQuery('#rateit-link').bind('click', function(){
            Mercatino.rateitForm.show('{{ $publication->id }}');
        });

        jQuery('#rating-sel').barrating({showValues:true, showSelectedRating:false});
    });
</script>
@stop