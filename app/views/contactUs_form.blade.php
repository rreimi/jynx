@extends('layout_home')
@parent
@stop
@section('sidebar')
@section('content')

<div class="row-fluid">

    {{ Form::open(array('url' => 'contactanos', 'method' => 'post', 'class' => 'form-horizontal contactus-form')) }}
    <h1>{{Lang::get('content.contactus')}}</h1>

    <div class="control-group {{ $errors->has('name') ? 'error':'' }}">
        <label class="control-label required-field" for="name">{{ Lang::get('content.contactus_name') }}</label>
        <div class="controls">
            {{ Form::text('name', $contactUs->name, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.contactus_name'))) }}
            {{ $errors->first('name', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
        <label class="control-label required-field" for="email">{{ Lang::get('content.contactus_email') }}</label>
        <div class="controls">
            {{ Form::text('email', $contactUs->email, array('class' => 'input-xlarge required email', 'placeholder'=> Lang::get('content.contactus_email'))) }}
            {{ $errors->first('email', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group {{ $errors->has('phone') ? 'error':'' }}">
        <label class="control-label required-field" for="phone">{{ Lang::get('content.contactus_phone') }}</label>
        <div class="controls">
            {{ Form::text('phone', $contactUs->phone, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.contactus_phone'))) }}
            <br/><label class="phone-format-label">{{ Lang::get('content.phone_format_label') }}</label>
            {{ $errors->first('phone', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group {{ $errors->has('subject') ? 'error':'' }}">
        <label class="control-label required-field" for="subject">{{ Lang::get('content.contactus_subject') }}</label>
        <div class="controls">
            {{ Form::text('subject', $contactUs->subject, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.contactus_subject'))) }}
            {{ $errors->first('subject', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group {{ $errors->has('message') ? 'error':'' }}">
        <label class="control-label required-field" for="message">{{ Lang::get('content.contactus_message') }}</label>
        <div class="controls">
            {{ Form::textarea('contact_message', $contactUs->contact_message, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.contactus_message'))) }}
            {{ $errors->first('contact_message', '<div class="field-error alert alert-error">:message</div>') }}
        </div>
    </div>

    <div class="control-group">
        <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
    </div>

    <div class="control-group">
        <div class="controls">
            <a href="{{URL::to('/contactanos')}}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
            <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.send')}}</button>
        </div>
    </div>

    {{ Form::close() }}


    <div class="contact-info">
        <div class="contact-row address contact-text">
            {{Lang::get('content.address_line1')}}<br/>
            {{Lang::get('content.address_line2')}}
        </div>
        <br/>
        <div class="contact-row phone contact-text">
            {{Lang::get('content.phones_label')}} {{Lang::get('content.phones_line1')}}<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            {{Lang::get('content.phones_line2')}}
        </div>
        <br/>
        <a href="mailto:informatica@cavenit.com">
            <div class="contact-row mail contact-text">
                {{Lang::get('content.cavenit_email')}}
            </div>
        </a>

    </div>
</div>


</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.contactus-form').validateBootstrap();
    });
</script>
@stop