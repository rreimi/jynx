{{ Form::open(array('url' => 'contact', 'class' => 'form-horizontal' )) }}
    <div class="control-group {{ $errors->has('contact_full_name')? 'error':'' }}">
        {{ Form::text('contact_full_name',null,array('placeholder' => Lang::get('content.contact_full_name'),'class' => 'input-block-level')) }}
    </div>

    <div class="control-group {{ $errors->has('contact_distributor')? 'error':'' }}">
        {{ Form::text('contact_distributor',null,array('placeholder' => Lang::get('content.contact_distributor'),'class' => 'input-block-level')) }}
    </div>

    <div class="control-group {{ $errors->has('contact_email')? 'error':'' }}">
        {{ Form::text('contact_email',null,array('placeholder' => Lang::get('content.contact_email'),'class' => 'input-block-level')) }}
    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group {{ $errors->has('contact_phone')? 'error':'' }}">
                {{ Form::text('contact_phone',null,array('placeholder' => Lang::get('content.contact_phone'),'class' => 'input-block-level')) }}
            </div>
        </div>
        <div class="span6">
            <div class="control-group {{ $errors->has('contact_city')? 'error':'' }}">
                {{ Form::text('contact_city',null,array('placeholder' => Lang::get('content.contact_city'),'class' => 'input-block-level')) }}
            </div>
        </div>
    </div>
    <div class="control-group {{ $errors->has('contact_address')? 'error':'' }}">
        {{ Form::text('contact_address',null,array('placeholder' => Lang::get('content.contact_address'),'class' => 'input-block-level')) }}
    </div>
    <div class="register-controls text-right">
        {{ Form::submit(Lang::get('content.contact_add'),array('class' => 'btn btn-large btn-info')) }}
    </div>
{{ Form::close() }}