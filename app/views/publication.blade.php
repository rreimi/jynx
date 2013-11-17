@extends('layout_home_no_sidebar')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid publication-detail">
        <div class="float-right">
            <!-- Carousel -->
            <div id="pub-images-box" class=" pub-images-carousel carousel slide">
                <ol class="carousel-indicators">
                    @foreach ($publication->images as $key => $img)
                        <li data-target="#pub-images-box" data-slide-to="{{ $key }}"></li>
                    @endforeach
                </ol>

                <div class="carousel-inner">
                    @foreach ($publication->images as $key => $img)
                    <div class="item @if ($key == 0) active @endif">
                        <div class="pub-image-wrapper">
                            <img class="pub-img-medium"  src="
                            {{ UrlHelper::imageUrl('/uploads/pub/' . $publication->id . '/' . $img->image_url, '_' . $detailSize['width']) }}" alt="{{ $publication->title }}"/>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if (count($publication->images) > 1)
                    <a data-slide="prev" href="#pub-images-box" class="left carousel-control">‹</a>
                    <a data-slide="next" href="#pub-images-box" class="right carousel-control">›</a>
                @endif

            </div>
            @if (!is_null($publication->latitude) && !is_null($publication->longitude))
                <div class="google-map">
                    Mapa con ubicación principal
                    <br/><br/>
                    <img src="{{ 'http://maps.googleapis.com/maps/api/staticmap?&zoom=15&size=250x250&markers=color:blue%7C' . $publication->latitude . ',' . $publication->longitude . '&sensor=false' }}"/>
                </div>
            @endif
        </div><!-- pub-images-box -->
        <!-- End Carousel -->

        <h1>{{ $publication->title }}</h1>
        <div class="triangle"></div>

        <div class="publication-info">

            @include('include.add_this')

            <a href="{{ URL::previous() }}" class="btn btn-mini">{{Lang::get('content.previous')}}</a>

            @if (!is_null(Auth::user()))
            @if ((Auth::user()->isAdmin()) || (Auth::user()->isPublisher() && ($publication->publisher_id == Auth::user()->publisher->id)))
            <a href="{{ URL::to('publicacion/lista') }}" class="btn btn-mini btn-success">{{Lang::get('content.back_to_publications')}}</a>
            <a class="action btn btn-mini btn-info" href="{{ URL::to('publicacion/editar/' . $publication->id)}}">{{ Lang::get('content.edit') }}</a>
            @endif
            @endif

            <div>{{ $publication->short_description }}</div>

            <div><b>{{Lang::get('content.visits_number')}}</b>: {{$publication->visits_number}} </div>

            <div class="evaluation"><b>{{Lang::get('content.evaluation')}}</b>: {{ RatingHelper::getRatingBar($publication->rating_avg) }} </div>

            <div>
                <h2><span class="title-arrow">></span>{{Lang::get('content.descripcion')}}</h2>
                <p>{{ $publication->long_description }}</p>
            </div>

            <div>
                <h2><span class="title-arrow">></span>{{Lang::get('content.categories_title')}}</h2>
                <ul>
                    @foreach ($publication->categories as $cat)
                    <li>{{ $cat->name }}</li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2><span class="title-arrow">></span>{{Lang::get('content.sell_by_full')}}</h2>
                <p class="pub-name">{{ $publication->publisher->seller_name }}</p>
                @if (Auth::check())
                    <p class="pub-email">{{Lang::get('content.user_email')}}:  <a href="mailto:{{ $publisher_email }}">{{ $publisher_email }}</a></p>
                    <p class="pub-phone">{{Lang::get('content.phone')}}:  {{ $publication->publisher->phone1 }}
                        @if ($publication->publisher->phone2)
                        / {{ $publication->publisher->phone2 }}
                        @endif</p>
                    <p class="pub-location">{{Lang::get('content.location')}}:  {{ $publication->publisher->city . ', ' . $publication->publisher->state->name }}</p>
                    @if ($publication->publisher->web)
                        <p class="pub-web">{{Lang::get('content.web_page')}}:  <a href="{{ $publication->publisher->web }}">{{ $publication->publisher->web }}</a></p>
                    @endif
                @endif
            </div><!--/.publisher-info-->

            @if (count($publication->contacts) > 0)
            <div>
                <h2 class="contacts-title"><span class="title-arrow">></span>{{ Lang::get('content.contacts')}}</h2>
                    @foreach ($publication->contacts as $contact)
                        <div class="contact">
                            <p class="pub-name">{{ $contact->full_name }}
                                @if (isset($contact->distributor)) - {{ $contact->distributor }} @endif
                            <p/>
                            @if (Auth::check())
                                <p class="pub-email">{{Lang::get('content.user_email')}}: {{ $contact->email }}</p>
                                <p class="pub-phone">{{Lang::get('content.phone')}}: {{ $contact->phone }}</p>
                                <p class="pub-location">{{Lang::get('content.location')}}: {{ $contact->address }}, {{ $contact->city }}</p>
                            @endif
                        </div>
                    @endforeach
            </div><!--/.contacs-info-->
            @endif
        </div>

        <div class="clear-both"></div>
        @if (!Auth::check())
        <div class="contact-more-info text-warning">
            {{ Lang::get('content.contacts_more_info', array('loginUrl' => URL::to('login'))) }}
        </div>
        @endif

        <!-- Ratings -->
        <div class="publication-rating">

            <div class="title-block">
                <div class="publication-buttons">
                    <div class="report-info">
                        @if (!(Auth::check() && Auth::user()->isPublisher() && ($publication->publisher_id == Auth::user()->publisher->id)))
                            <a nohref class="btn btn-primary btn" id="rateit-link">{{Lang::get('content.rate_it')}}</a>
                            <a nohref class="btn btn-primary btn" id="report-link">{{Lang::get('content.report_it')}}</a>
                        @endif
                    </div>
                </div>
                <h2 class="title">{{Lang::get('content.ratings')}}</h2>
            </div>
            <div class="items">
                <!-- Here is placed the content with the ajax function-->
            </div>

            @include('include.modal_report')
            @include('include.modal_rateit')

        </div>

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
                                            @if (isset($pub->image_url))
                                            <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
                                                <img class="pub-img-small"  src="{{ UrlHelper::imageUrl('/uploads/pub/' . $pub->id . '/' . $pub->image_url, '_' . $thumbSize['width']) }}" alt="{{ $pub->title }}"/>
                                            </a>
                                            @endif
                                            <div class="pub-info-desc">
                                                <a href="{{ URL::to('publicacion/detalle/' . $pub->id)}}">
                                                    <h2 class="pub-title">{{ $pub->title }}</h2>
                                                </a>
                                                <span class="pub-seller">{{Lang::get('content.sell_by')}}: {{ $pub->seller_name }}</span>
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

    Mercatino.ratings = {
        retrieveUrl: '{{ URL::to("evaluacion/denuncias-publicacion/" . $publication->id) }}',
        changeStatusUrl: '{{ URL::to("evaluacion/cambiar-estatus/") }}',
        deleteUrl: '{{ URL::to("evaluacion/delete/") }}',
        currentPage:  0,
        lastAction: 'next',

        previousPage: function(){
            this.currentPage--;
            this.retrieve(this.currentPage);
        },

        nextPage: function(){
            this.currentPage++;
            this.retrieve(this.currentPage);
        },

        retrieve: function(pageNumber){
            jQuery.ajax({
                url: this.retrieveUrl + "/" + this.currentPage,
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    jQuery('.publication-rating .items').html(result.ratings);
                    console.log('calling retrieve');
                    Mercatino.ratings.prepare();

                },
                error: function(result) {
                    Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.rating_publication_retrieve_error')}}", type:'error'});
                }
            });
        },

        prepare: function(){
            // Iterate each btn-group of ratings
            jQuery(".rating-block .actions .btn-group.group-admin").each(function(){
                // Iterate each admin button by group
                jQuery('button', jQuery(this)).each(function(){
                    // Add click event to each button of the current group
                    jQuery(this).click(function() {
                        var parentGroup = jQuery(this).parent();
                        var previousValue = jQuery('input[type=hidden][name=rating_hidden_'+parentGroup.attr('data-toggle-id')+']').val();
                        var currentValue = jQuery(this).prop('value');
                        // Don't do anything when is clicked the same value
                        if (previousValue == currentValue){
                            return;
                        }

                        // If changed save the current value
                        jQuery('input[type=hidden][name=rating_hidden_'+parentGroup.attr('data-toggle-id')+']').val(currentValue);

                        // Change rating's status
                        Mercatino.ratings.changeStatus(parentGroup.attr('data-toggle-id'), currentValue);
                    });
                });
            });

            jQuery(".rating-block .actions .btn-group.group-owner").each(function(){
                // Iterate each owner button by group
                jQuery('button', jQuery(this)).each(function(){
                    // Add click event to each button of the current group
                    jQuery(this).click(function() {
                        Mercatino.ratings.delete(jQuery(this).attr('data-id'));
                    });
                });
            });
        },

        changeStatus: function(ratingId, status){
            jQuery.ajax({
                url: this.changeStatusUrl + '/' + ratingId + '/' + status,
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    if (result.result == 'success'){
                        Mercatino.showFlashMessage({title:'', message: result.message, type:'success'});
                    } else {
                        Mercatino.showFlashMessage({title:'', message: "{{Lang::get('content.rating_change_status_error')}}", type:'error'});
                    }
                },
                error: function(result) {
                    Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.rating_change_status_error')}}", type:'error'});
                }
            });
        },

        delete: function(ratingId){
            console.log('delete: ' + ratingId);
            jQuery.ajax({
                url: this.deleteUrl + '/' + ratingId,
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    Mercatino.showFlashMessage({title:'', message: "{{Lang::get('content.rating_delete_success')}}", type:'success'});
                    Mercatino.ratings.currentPage -= 1;
                    Mercatino.ratings.nextPage();
                },
                error: function(result) {
                    Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.rating_delete_error')}}", type:'error'});
                }
            });
        },

        defaultState: function() {
            jQuery('#get_more_preload').hide();
            jQuery('.get-more-button').show();
        },

        loadingState: function() {
            jQuery('.get-more-button').hide();
            jQuery('#get_more_preload').show();
        }
    }

    jQuery(document).ready(function(){
        jQuery('#report-link').bind('click', function(){
            @if (Auth::check())
                Mercatino.reportForm.show();
            @else
                window.location = "{{ URL::to('/login') }}";
            @endif
        });

        Mercatino.rateitForm.init();

        jQuery('#rateit-link').bind('click', function(){
            @if (Auth::check())
                Mercatino.rateitForm.show('{{ $publication->id }}');
            @else
                window.location = "{{ URL::to('/login') }}";
            @endif
            /* Configure validations */
        });

        Mercatino.ratings.nextPage();
    });
</script>
@stop