@extends('layout_backend')

@section('sidebar')
    @parent
@endsection

@section('content')
<div class="row-fluid">
    <h1>{{Lang::get('content.backend_mass_email_section_title')}}</h1>
    {{ Form::open(array('url' => 'dashboard/mass-email', 'id' => 'mass_email_form', 'method' => 'post', 'class' => 'form-horizontal')) }}

    <div class="control-group {{ $errors->has('email_subject') ? 'error':'' }}">
        <label class="control-label required-field" for="email_subject">{{ Lang::get('content.mass_email_subject') }}</label>
        <div class="controls">
            {{ Form::text('email_subject', $email_content, array('class' => 'input-xxlarge required', 'maxlength' => '120')) }}
            {{ $errors->first('email_subject', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group {{ $errors->has('email_content') ? 'error':'' }}">
        <label class="control-label required-field" for="email_content">{{ Lang::get('content.mass_email_content') }}</label>
        <div class="controls">
            {{ Form::textarea('email_content', $email_content, array('class' => 'input-xxlarge required', 'maxlength' => '2000')) }}
            {{ $errors->first('email_content', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
        <label class="control-label required-field" for="status">{{ Lang::get('content.publisher_status') }}</label>
        <div class="controls">
            {{ Form::select('status', $advertiser_status, null, array('class' => 'status required', 'id' => 'mass_email_status')) }} <span id="mass_email_status_info" class="alert alert-warning hide">
                {{ Lang::get('content.mass_email_target_info', array('total_publishers' => $total_publishers))}}
            </span>
            {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group">
        <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
    </div>

    <div class="control-group">
        <div class="text-warning alert-block alert-description ">{{ Lang::get('content.help_mass_email_content') }}</div>
        <div class="controls">
            <button class="btn btn-medium btn-warning" id="mass_email_submit_btn" type="button">{{Lang::get('content.send')}}</button>
        </div>
    </div>
    {{ Form::close() }}
</div>
@stop

@section('scripts')
@parent
    <script type="text/javascript">
        var totalPublishers = 0;
        jQuery('.form-horizontal').validateBootstrap({placement:'right'});

        jQuery(document).ready(function(){
            jQuery('#mass_email_status').bind('change', function(){
                //clear warning
                jQuery('#mass_email_status_info').hide();
                jQuery('#mass_email_total_publishers').html(0);

                var value = jQuery(this).val();

                if (value == '') {
                    return;
                };

                var url = 'ajax-total-publishers/' + value;

                jQuery.getJSON(url, function(data){
                    jQuery('#mass_email_total_publishers').html(data.total_publishers);
                    totalPublishers = data.total_publishers;
                    jQuery('#mass_email_status_info').show();
                });
            });

            jQuery('#mass_email_submit_btn').bind('click', function(){
                if (jQuery('#mass_email_status').val() != ''){
                    if (totalPublishers < 1){
                        Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.mass_email_no_publishers')}}", type:'error'});
                        return false;
                    };
                };
                jQuery('#mass_email_form').submit();
            });

            //Just in case of server side validations
            if (jQuery('#mass_email_status').val() != ''){
                jQuery('#mass_email_status').trigger('change');
            };
        });
    </script>
@stop