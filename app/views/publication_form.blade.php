@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')

    <div class="row-fluid">
        {{ Form::open(array('url' => 'publicacion/guardar', 'method' => 'post', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) }}
        @if (!isset($publication->id))
        <h1>{{Lang::get('content.new_publication')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_publication')}}: {{ $publication->title }}</h1>
        @endif
        <div class="control-group {{ $errors->has('title') ? 'error':'' }}">
            <label class="control-label" for="title">{{ Lang::get('content.title') }}</label>
            <div class="controls">
                {{ Form::text('title', $publication->title, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.title'))) }}
                {{ $errors->first('title', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('short_description') ? 'error':'' }}">
            <label class="control-label" for="short_description">{{ Lang::get('content.short_description') }}</label>
            <div class="controls">
                {{ Form::text('short_description', $publication->short_description, array('class' => 'input-xxlarge', 'placeholder'=> Lang::get('content.short_description'))) }}
                {{ $errors->first('short_description', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>


        <div class="control-group {{ $errors->has('long_description') ? 'error':'' }}">
            <label class="control-label" for="long_description">{{ Lang::get('content.long_description') }}</label>
            <div class="controls">
                {{ Form::textarea('long_description', $publication->long_description, array('class' => 'input-xxlarge', 'placeholder'=> Lang::get('content.long_description'))) }}
                {{ $errors->first('long_description', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
            <label class="control-label" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select('status', $pub_statuses, $publication->status) }}
                {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('from_date') ? 'error':'' }}">
            <label class="control-label" for="from_date">{{ Lang::get('content.from_date') }}</label>
            <div class="controls">
                {{ Form::text('from_date', date("d-m-Y",strtotime($publication->from_date)), array('class' => 'datepicker from-date', 'placeholder' => Lang::get('content.date_format'))) }}
                {{ $errors->first('from_date', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group ">
            <label class="control-label" for="to_date">{{ Lang::get('content.to_date') }}</label>
            <div class="controls">
                {{ Form::text('to_date', date("d-m-Y",strtotime($publication->to_date)), array('class' => 'datepicker to-date', 'placeholder' => Lang::get('content.date_format'))) }}
                {{ $errors->first('to_date', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('from_date') ? 'error':'' }}">
            <div class="controls">
                <label class="checkbox">
                    {{ Form::checkbox('remember', 1, ($publication->remember == 1), array('class' => 'chk-remember')) }} {{ Lang::get('content.remember_publication') }}
                </label>
            </div>
        </div>


        <!-- Categories -->
        <div class="control-group categories-form">
            @if ($errors->has('categories'))
            <div class="field-error alert alert-error">{{ $errors->first('categories') }}</div>
            @endif

            <ul class="float-left categories-form-list">
                <li><h2>{{Lang::get('content.categories_title')}}</h2></li>
            @foreach ($categories as $cat)
                <li>
                    @if (count($cat->subcategories) > 0)
                    <span class="float-left cursor-pointer collpase-subcategories" data-toggle="collapse" data-target="#subcategories_for_{{ $cat->id }}">+</span>
                    @endif
                    <label class="checkbox checkbox-category-form">
                        {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $publication_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
                    </label>
                    <ul id="subcategories_for_{{ $cat->id }}" class="subcategories-list collapse @if ( in_array($cat->id, (array) $publication_categories)) in @endif">
                        @foreach ($cat->subcategories as $subcat)
                        <li>
                            <label class="checkbox">
                               {{ Form::checkbox('categories[]', $subcat->id, in_array($subcat->id, (array) $publication_categories), array('class' => 'chk-sub-cat', 'data-parent-id' => $cat->id)) }} {{ $subcat->name }}
                            </label>
                            <ul>
                               @foreach ($subcat->subcategories as $thirdcat)
                                <li>
                                    <label class="checkbox">
                                        {{ Form::checkbox('categories[]', $thirdcat->id, in_array($thirdcat->id, (array) $publication_categories), array('class' => 'chk-third-cat', 'data-parent-id' => $subcat->id)) }} {{ $thirdcat->name }}
                                    </label>
                                </li>
                               @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
            </ul>

            <ul class="float-left">
                <li><h2>{{Lang::get('content.services_title')}}</h2></li>
                @foreach ($services as $cat)
                <li>
                    @if (count($cat->subcategories) > 0)
                    <span class="float-left cursor-pointer collpase-subcategories" data-toggle="collapse" data-target="#subcategories_for_{{ $cat->id }}">+</span>
                    @endif
                    <label class="checkbox checkbox-category-form">
                        {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $publication_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
                    </label>
                    <ul id="subcategories_for_{{ $cat->id }}" class="subcategories-list collapse @if ( in_array($cat->id, (array) $publication_categories)) in @endif">
                        @foreach ($cat->subcategories as $subcat)
                        <li>
                            <label class="checkbox">
                                {{ Form::checkbox('categories[]', $subcat->id, in_array($subcat->id, (array) $publication_categories), array('class' => 'chk-sub-cat', 'data-parent-id' => $cat->id)) }} {{ $subcat->name }}
                            </label>
                            <ul>
                                @foreach ($subcat->subcategories as $thirdcat)
                                <li>
                                    <label class="checkbox">
                                        {{ Form::checkbox('categories[]', $thirdcat->id, in_array($thirdcat->id, (array) $publication_categories), array('class' => 'chk-third-cat', 'data-parent-id' => $subcat->id)) }} {{ $thirdcat->name }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
            </ul>
        </div>

        <!-- Contacts -->
        <div class="control-group">
            <h2>{{Lang::get('content.publication_contacts')}}</h2>

            @if ($errors->has('contacts'))
            <div class="field-error alert alert-error">{{ $errors->first('contacts') }}</div>
            @endif

            @foreach ($contacts as $contact)
            <label class="checkbox">
                {{ $contact->full_name . ', ' .  $contact->city . ', ' .  $contact->address  . ', ' .  $contact->phone }}
                {{ Form::checkbox('contacts[]', $contact->id, in_array($contact->id, (array) $publication_contacts), array('class' => 'chk-contact')) }} {{ $contact->name }}
            </label>
            @endforeach

        </div>

        @if (!is_null($publication->id))
        <div class="row-fluid imagenes-section-box">
            <a name="imagenes"></a>
            <h2 id="imagenes-section-title">{{Lang::get('content.publication_images')}}</h2>
            <div class="form-message-box alert alert-error">

            </div>
            <div id="dropzone" class="dropzone"></div>
        </div>
        @endif

        {{ Form::hidden('id', $publication->id) }}
        {{ Form::hidden('publisher_id', $publication->publisher_id) }}
        {{ Form::hidden('referer', $referer) }}

        <div class="control-group">
            <div class="controls">
                <a href="{{ $referer }}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
                @if (isset($publication->id))
                <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.save')}}</button>
                @else
                <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.continue')}}</button>
                @endif
            </div>
        </div>
        {{ Form::close() }}
    </div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
{{ HTML::script('js/dropzone.js') }}
<script type="text/javascript">
    jQuery(document).ready(function(){

        /* All date filters */
        jQuery('.datepicker').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true
        });

        jQuery('.subcategories-list')
            .on('show',function(){
                jQuery('span[data-target=#' + jQuery(this).attr('id') + ']').html('-');
            })
            .on('hide',function(){
                jQuery('span[data-target=#' + jQuery(this).attr('id') + ']').html('+');
            });

        jQuery('.collpase-subcategories').bind('click', function() {
        });

        /* Set dynamic date range */
        jQuery('.datepicker.from-date').bind("change", function(){
            jQuery('.datepicker.to-date').datepicker( "option", "minDate", jQuery(this).val());
        });
        jQuery('.datepicker.to-date').bind("change", function(){
            jQuery('.datepicker.from-date').datepicker( "option", "maxDate", jQuery(this).val());
        });

        /* When subcat got checked parents also */
        jQuery('.chk-sub-cat').bind('click', function() {
            if (jQuery(this).is(':checked')){
                var parentValue = jQuery(this).attr('data-parent-id');
                jQuery('input.chk-cat[value=' + parentValue + ']:not(:checked)').trigger('click');
            }
        })

        jQuery('.chk-third-cat').bind('click', function() {
            if (jQuery(this).is(':checked')){
                var parentValue = jQuery(this).attr('data-parent-id');
                jQuery('input.chk-sub-cat[value=' + parentValue + ']:not(:checked)').trigger('click');
            }
        })

        /* If anchor #images is received, go to images and show popover */
        if (window.location.hash == '#imagenes') {

            jQuery('#dropzone').popover({
//                title: "{{Lang::get('content.help_publication_images_title')}}",
                content: "{{Lang::get('content.help_publication_images')}}",
                placement:'top',
                html:true
            });
            setTimeout("jQuery('#dropzone').popover('show')", 1000);
        }

        //Add client validations
        //jQuery('.form-horizontal').validateBootstrap({placement:'left'});

    });

    @if (!is_null($publication->id))

    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("div#dropzone", {
        paramName: "file",
        url: "{{URL::to('publicacion/imagenes/' . $publication->id)}}",
        addRemoveLinks: true,
        dictRemoveFile: "{{Lang::get('content.remove_image')}}",
        dictDefaultMessage: "{{Lang::get('content.add_images_msg')}}",
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        maxFilesize: 2,
        accept: function(file, done) {

            /* Validar el archivo */
            console.log(file);
            if ((file.width < 250) || (file.height < 250)) {
                alert("{{Lang::get('content.img_min_size_message')}}");
            }

            if (file.name == "html5.png") {
                alert('Y');
                //done("Naha, you don't.");

                myDropzone.removeFile(file);
            }
            else {
                done();
            }
        }
    });

    /** Add current images to dropzone */

    (function(){
        @foreach ($publication->images as $img)
        var mockFile = { name: "{{ $img->image_url }}", size: 0, server_id : {{ $img->id }} };

        // Call the default addedfile event handler
        myDropzone.options.addedfile.call(myDropzone, mockFile);

        // And optionally show the thumbnail of the file:
        myDropzone.options. thumbnail.call(myDropzone, mockFile, "{{URL::to('uploads/pub/' . $publication->id . '/' . $img->image_url )}}");
        // Create the mock file:
    @endforeach
    }());


    /* After upload, server response the generated id, so we can assing it to file object to enable deletion */
    myDropzone.on("success", function(file, fileServerId) {
        file.server_id = fileServerId;
        showMessage("{{Lang::get('content.add_publication_image_success')}}",'success');
    });


    myDropzone.on("error", function(file, errorMessage) {
        console.log(errorMessage);
    });

    myDropzone.on("removedfile", function(file) {
        /* File may not be accepted based on validations so it never get uploaded */
        if (file.server_id != undefined) {
            jQuery.ajax({
                url: "{{URL::to('publicacion/imagenes/' . $publication->id)}}" + "/" + file.server_id,
                type: 'DELETE',
                success: function(result) {
                // Do something with the result
                showMessage("{{Lang::get('content.delete_publication_image_success')}}",'success');
                },
                error: function(result) {
                    showMessage("{{Lang::get('content.delete_publication_image_error')}}",'error');
                }
            });
        }
    });

    @endif

    function showMessage(message, type) {
        jQuery('.form-message-box').hide();
        jQuery('.form-message-box').removeClass('alert-error alert-success alert-warning');
        jQuery('.form-message-box').addClass('alert-' + type);
        jQuery('.form-message-box').html(message);
        jQuery('.form-message-box').show();
        //setTimeout("jQuery('.form-message-box').hide()", 5000);
    }

</script>
@stop