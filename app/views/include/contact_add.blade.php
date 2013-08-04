<div class="control-group {{ $errors->has('full_name')? 'error':'' }}">
    {{ Form::text('contact_full_name', null,array('placeholder' => Lang::get('content.contact_full_name'),'class' => 'input-block-level required')) }}
</div>

<div class="control-group {{ $errors->has('distributor')? 'error':'' }}">
    {{ Form::text('contact_distributor',null,array('placeholder' => Lang::get('content.contact_distributor'),'class' => 'input-block-level')) }}
</div>

<div class="control-group {{ $errors->has('email')? 'error':'' }}">
    {{ Form::text('contact_email',null,array('placeholder' => Lang::get('content.contact_email'),'class' => 'input-block-level required email')) }}
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="control-group {{ $errors->has('phone')? 'error':'' }}">
            {{ Form::text('contact_phone',null,array('placeholder' => Lang::get('content.contact_phone'),'class' => 'input-block-level required')) }}
        </div>
    </div>
    <div class="span6">
        <div class="control-group {{ $errors->has('city')? 'error':'' }}">
            {{ Form::text('contact_city',null,array('placeholder' => Lang::get('content.contact_city'),'class' => 'input-block-level required')) }}
        </div>
    </div>
</div>

<div class="control-group {{ $errors->has('address')? 'error':'' }}">
    {{ Form::text('contact_address',null,array('placeholder' => Lang::get('content.contact_address'),'class' => 'input-block-level required')) }}
</div>