@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid">
        <h1></h1>
        <h2>{{Lang::get('content.publication_images')}} - {{ $publication->title }}</h2>
        <div class="form-message-box alert alert-error">

        </div>
        <div id="dropzone" class="dropzone">

        </div>

    </div><!--/row-fluid-->
    <br/>
    <a href="{{URL::to('publicacion/detalle/' . $id)}}" class="btn btn-medium">{{Lang::get('content.see_publication')}}</a>
    <a href="{{URL::to('publicacion/lista')}}" class="btn btn-medium">{{Lang::get('content.see_my_publications')}}</a>
@stop

@section('scripts')
@parent
{{ HTML::script('js/dropzone.js') }}
<script type="text/javascript">

    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("div#dropzone", {
        paramName: "file",
        url: "{{URL::to('publicacion/imagenes/' . $id)}}",
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
                url: {{URL::to('publicacion/imagenes/'. $publication->id )}} + '/' + file.server_id,
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
