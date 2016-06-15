@extends('layout_home_no_sidebar')

@section('head_after')
    {{ HTML::style('css/colorbox.css') }}
@show

@section('sidebar')
@parent
@stop

@section('content')

    <div class="row-fluid publication-detail">

        <h1>{{ $publication->title }}</h1>
        <div class="by-share">
            <div class="seller-name">{{Lang::get('content.sell_by')}} <a href="{{ URL::to('search?seller=' . $publication->publisher->id)}}">{{ $publication->publisher->seller_name }}</a></div>
            @include('include.add_this')
        </div>

        <div class="publication-options">
            <a href="{{ URL::previous() }}" title="{{Lang::get('content.previous')}}"><i class="icon-share-alt icon-flipped icon-gray"></i></a>

                                @if (!is_null(Auth::user()))
                                @if ((Auth::user()->isAdmin()) || (Auth::user()->isPublisher() && ($publication->publisher_id == Auth::user()->publisher->id)))
                                <a href="{{ URL::to('publicacion/lista') }}" title="{{Lang::get('content.back_to_publications')}}"><i class="icon-plus icon-gray"></i></a>
                                <a title="{{ Lang::get('content.edit') }}" href="{{ URL::to('publicacion/editar/' . $publication->id)}}"><i class="icon-pencil icon-gray"></i></a>
                                @endif
                                @endif
        </div>
        <div class="clear-both"></div>

        <div class="float-left">
            <!-- Carousel -->
            <div id="pub-images-box" class="pub-images-carousel carousel slide">
                @if (count($publication->images) > 0)
                    @if (count($publication->images) > 1)
                        <ol class="carousel-indicators">
                            @foreach ($publication->images as $key => $img)
                                <li data-target="#pub-images-box" data-slide-to="{{ $key }}"></li>
                            @endforeach
                        </ol>
                    @endif
                    <div class="carousel-inner">
                        @foreach ($publication->images as $key => $img)
                        <div class="item @if ($key == 0) active @endif">
                            <div class="pub-image-wrapper">
                                <a href="{{ URL::to('/uploads/pub/' . $publication->id . '/' . $img->image_url) }}" class="publication-gallery">
                                    <img class="pub-img-medium"  src="
                                    {{ UrlHelper::imageUrl('/uploads/pub/' . $publication->id . '/' . $img->image_url, '_' . $detailSize['width']) }}" alt="{{ $publication->title }}"/>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if (count($publication->images) > 1)
                        <a data-slide="prev" href="#pub-images-box" class="left carousel-control">‹</a>
                        <a data-slide="next" href="#pub-images-box" class="right carousel-control">›</a>
                    @endif
                @else
                    <!--<!--<div class="carousel-inner">
                        <div class="item active">
                            <div class="pub-image-wrapper">-->
                                <img class="pub-img-medium"  src="
                                 {{ UrlHelper::imageUrl('/img/default_image.jpg', '_' . $detailSize['width']) }}" alt="{{ $publication->title }}"/>
                            <!--</div>
                        </div>
                    </div>-->
                @endif
            </div>

            <div class="visits float-left">{{Lang::get('content.visits_number')}}: {{$publication->visits_number}} </div>
            <div class="evaluation float-right"><span>{{Lang::get('content.evaluation')}}: {{ RatingHelper::getRatingBar($publication->rating_avg) }}</span></div>
            <div class="clear-both"></div>


        </div><!-- pub-images-box -->
        <!-- End Carousel -->


        <div class="publication-info">

            <div>
                <h2>{{Lang::get('content.descripcion')}}</h2>
                <p>{{ $publication->long_description }}</p>
            </div>

            <div>
                <h2>{{Lang::get('content.categories_title')}}</h2>
                <ul>
                    @foreach ($publication->categories as $cat)
                    <li>{{ $cat->name }}</li>
                    @endforeach
                </ul>
            </div>

            @if (count($publication->contacts) > 0)
            <div>
                <h2 class="contacts-title">{{ Lang::get('content.contacts')}}</h2>
                @foreach ($publication->contacts as $contact)
                    <div class="contact">
                        <div class="block">
                            @if ($contact->full_name || $contact->distributor)
                                <p class="pub-name">{{ $contact->full_name }}
                                    <b>@if (isset($contact->distributor)) {{ (($contact->full_name)?'- ':''). $contact->distributor }} @endif</b>
                                <p/>
                            @endif
                            @if (Auth::check())
                                <p class="pub-email">{{Lang::get('content.user_email')}}: <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                            @endif
                        </div>
                        @if (Auth::check())
                        <div class="block">
                            <p class="pub-phone">{{Lang::get('content.phone')}}: {{ $contact->phone }}
                                @if ($contact->other_phone)
                                / {{ $contact->other_phone }}
                                @endif
                            </p>
                            @if ($contact->state_id || $contact->city || $contact->address)
                            <p class="pub-location">{{Lang::get('content.location')}}:
                                @if ($contact->state_id) {{ $contact->state->name.(($contact->city || $contact->address)?',':'') }} @endif
                                @if ($contact->city) {{ $contact->city.(($contact->address)?',':'') }} @endif
                                @if ($contact->address) {{ $contact->address }} @endif
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div><!--/.contacs-info-->
            @endif

            @if (!is_null($publication->latitude) && !is_null($publication->longitude))
                <div>
                    <h2>{{Lang::get('content.map')}}</h2>
                    <img src="{{ 'http://maps.googleapis.com/maps/api/staticmap?&zoom=15&size=250x250&markers=color:blue%7C' . $publication->latitude . ',' . $publication->longitude . '&sensor=false' }}"/>
                </div>
            @endif

        </div>

        <div class="clear-both publication-line"></div>

        @if (!Auth::check())
        <div class="contact-more-info">
            {{ Lang::get('content.contacts_more_info', array('loginUrl' => URL::to('login'))) }}
        </div>
        @endif
        <br/>

        <!-- Ratings -->
        <div class="publication-rating">

            <div class="title-block">
                <h2 class="title">{{Lang::get('content.ratings')}}</h2>
            </div>

            @if (Auth::check())
            <div class="publication-buttons">
                <div class="report-info">
                    @if (!(Auth::check() && Auth::user()->isPublisher() && ($publication->publisher_id == Auth::user()->publisher->id)))
                            <div class="rating-box">
                                {{ Form::open(array('url' => 'evaluacion','class'=>'big-form register-form', 'id' => 'rating-form')) }}
                                <div class="rating-form rating-c">
                                    <select id="rating-sel" name="rating-select">
                                        <option value="" selected="selected"></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                </div>
                                <input type="text" id="report_title_txt" name="title" max-length="80" placeholder="{{Lang::get('content.rate_title')}}"/>
                                <textarea id="report_comment_txt" class="input-block-level" name="report_comment" rows="8" placeholder="{{Lang::get('content.rate_comment_instructions')}}"></textarea>
                                <input type="hidden" id="rating_publication_id" name="rating_publication_id" value="{{$publication->id}}" />
                                {{ Form::close() }}
                            </div>
                            <div>
                                <a nohref onclick="javascript:Mercatino.rateitForm.send()" class="btn btn-primary">{{Lang::get('content.rate_it')}}</a>
                            </div>

                     @endif
                </div>
            </div>
            <div class="rating-text">
                {{Lang::get('content.rating_text')}}
            </div>
            <div class="clear-both"></div>
            @endif
            <div class="items">
                <!-- Here is placed the content with the ajax function-->
            </div>

            @include('include.modal_report')
            @include('include.modal_rateit')

            @if (!(Auth::check() && Auth::user()->isPublisher() && ($publication->publisher_id == Auth::user()->publisher->id)))
                <div class="report-publication">
                    <a nohref class="btn" id="report-link">{{Lang::get('content.report_it')}}</a>
                </div>
            @endif

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
                                        @include('include.publication_box')
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
{{ HTML::script('js/colorbox/jquery.colorbox-min.js') }}
{{ HTML::script('js/jquery.barrating.min.js') }}
{{ HTML::script('js/imagecow.js') }}
<script type="text/javascript">

    jQuery('.publication-gallery').colorbox({
        rel: 'publication-gallery',
        maxHeight: '95%'
    });

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