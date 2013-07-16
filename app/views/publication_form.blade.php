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

        <h2>{{Lang::get('content.publication_categories')}}</h2>

        @if ($errors->has('categories'))
        <div class="field-error alert alert-error">{{ $errors->first('categories') }}</div>
        @endif

        @foreach ($categories as $cat)
            <dt>
            <label class="checkbox">
                {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $publication_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
            </label>
            </dt>
            <dd>
                @foreach ($cat->subcategories as $subcat)
                <label class="checkbox">
                    {{ Form::checkbox('categories[]', $subcat->id, in_array($subcat->id, (array) $publication_categories), array('class' => 'chk-sub-cat', 'data-parent-id' => $cat->id)) }} {{ $subcat->name }}
                </label>
                @endforeach
            </dd>

        @endforeach
        </div>

        @if (!is_null($publication->id))
        <a href="#imagenes"></a>
        <div class="row-fluid">
            <h1></h1>
            <h2>{{Lang::get('content.publication_images')}} - {{ $publication->title }}</h2>
            <div class="form-message-box alert alert-error">

            </div>
            <div id="dropzone" class="dropzone">

            </div>

        </div><!--/row-fluid-->
        <br/>
        @endif

        {{ Form::hidden('id', $publication->id) }}
        {{ Form::hidden('publisher_id', $publication->publisher_id) }}
        {{ Form::hidden('referer', $referer) }}

        <a href="{{ $referer }}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
        @if (isset($publication->id))
        <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.save')}}</button>
        @else
        <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.continue')}}</button>
        @endif
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

        /* Set dynamic date range */
        jQuery('.datepicker.from-date').bind("change", function(){
            jQuery('.datepicker.to-date').datepicker( "option", "minDate", jQuery(this).val());
        });
        jQuery('.datepicker.to-date').bind("change", function(){
            jQuery('.datepicker.from-date').datepicker( "option", "maxDate", jQuery(this).val());
        });

        /* When subcat got checked parents also */
        jQuery('.chk-sub-cat').bind('click', function() {
            jQuery('input.chk-cat[value=' + jQuery(this).attr('data-parent-id') + ']').attr('checked', true);
        })
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