@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')

    <div class="row-fluid">
        {{ Form::open(array('url' => 'publicidad/guardar', 'method' => 'post', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) }}
            @if (!isset($advertising->id))
                <h1>{{Lang::get('content.new_advertising')}}</h1>
            @else
                <h1>{{Lang::get('content.edit_advertising')}}: {{ $advertising->name }}</h1>
            @endif

            <div class="control-group">
                <label class="control-label" for="name">{{ Lang::get('content.name') }}</label>
                <div class="controls">
                    {{ Form::text('name', $advertising->name, array('placeholder'=> Lang::get('content.name'))) }}
                    {{ $errors->first('name', '<p class="error">:message</p>') }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="status">{{ Lang::get('content.status') }}</label>
                <div class="controls">
                    {{ Form::select('status', $adv_statuses, $advertising->status) }}
                    {{ $errors->first('status', '<p class="error">:message</p>') }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="external_url">{{ Lang::get('content.external_url') }}</label>
                <div class="controls">
                    {{ Form::text('external_url', $advertising->external_url, array('placeholder'=> Lang::get('content.external_url'))) }}
                    {{ $errors->first('external_url', '<p class="error">:message</p>') }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="full_name">{{ Lang::get('content.full_name') }}</label>
                <div class="controls">
                    {{ Form::text('full_name', $advertising->full_name, array('placeholder'=> Lang::get('content.full_name'))) }}
                    {{ $errors->first('full_name', '<p class="error">:message</p>') }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="email">{{ Lang::get('content.email') }}</label>
                <div class="controls">
                    {{ Form::text('email', $advertising->email, array('placeholder'=> Lang::get('content.email'))) }}
                    {{ $errors->first('email', '<p class="error">:message</p>') }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="phone1">{{ Lang::get('content.phone1') }}</label>
                <div class="controls">
                    {{ Form::text('phone1', $advertising->phone1, array('placeholder'=> Lang::get('content.phone1'))) }}
                    {{ $errors->first('phone1', '<p class="error">:message</p>') }}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="phone2">{{ Lang::get('content.phone2') }}</label>
                <div class="controls">
                    {{ Form::text('phone2', $advertising->phone2, array('placeholder'=> Lang::get('content.phone2'))) }}
                    {{ $errors->first('phone2', '<p class="error">:message</p>') }}
                </div>
            </div>

            {{ Form::hidden('id', $advertising->id) }}
            {{ Form::hidden('referer', $referer) }}

            @if (!is_null($advertising->id))
            <div class="row-fluid">
                <h2 id="imagenes">{{Lang::get('content.advertising_images')}}</h2>

                <div id="dropzone" class="dropzone">

                </div>
            </div><!--/row-fluid-->
            <br/>
            @endif

            <a href="{{ $referer }}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
            <button class="btn btn-medium btn-warning" type="submit">
                @if (!isset($advertising->id))
                    {{Lang::get('content.continue')}}
                @else
                    {{Lang::get('content.save')}}
                @endif
            </button>

        {{ Form::close() }}

    </div><!--/row-fluid-->

@stop

@section('scripts')
@parent
{{ HTML::script('js/dropzone.js') }}
@if (isset($advertising->id))
    <script type="text/javascript">
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("div#dropzone", {
            paramName: "file",
            url: "{{URL::to('publicidad/imagenes/' . $id)}}",
            addRemoveLinks: true,
            dictRemoveFile: "{{Lang::get('content.remove_image')}}",
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFilesize: 2,
            createImageThumbnails: false,
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

        @if (!empty($advertising->image_url))
            (function(){
                var mockFile = { name: "{{ $advertising->image_url }}", size: 0, server_id : {{ $id }} };

            // Call the default addedfile event handler
            myDropzone.options.addedfile.call(myDropzone, mockFile);

            // And optionally show the thumbnail of the file:
            myDropzone.options. thumbnail.call(myDropzone, mockFile, "{{URL::to('uploads/adv/' . $id . '/' . $advertising->image_url )}}");
            }());
        @endif

        /* After upload, server response the generated id, so we can assing it to file object to enable deletion */
        myDropzone.on("success", function(file, fileServerId) {
            file.server_id = fileServerId;
        });

        myDropzone.on("error", function(file, errorMessage) {
            console.log(errorMessage);
        });

        myDropzone.on("removedfile", function(file) {
            /* File may not be accepted based on validations so it never get uploaded */

            if (file.server_id != undefined) {
                jQuery.ajax({
                    url: "{{ URL::to('publicidad/imagenes/' .$id) }}",
                    type: 'DELETE',
                    success: function(result) {
                        // Do something with the result
                    },
                    error: function(result) {
                        console.log(result);
                        alert('No se pudo eliminar la imagen');
                    }
                });
            }
        });

    </script>
@endif
@stop