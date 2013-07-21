<div class="control-group {{ $errors->has('contact_full_name')? 'error':'' }}">
    {{ Form::text('contact_full_name',$contact->full_name,array('placeholder' => Lang::get('content.contact_full_name'),'class' => 'input-block-level')) }}
</div>

<div class="control-group {{ $errors->has('contact_distributor')? 'error':'' }}">
    {{ Form::text('contact_distributor',$contact->distributor,array('placeholder' => Lang::get('content.contact_distributor'),'class' => 'input-block-level')) }}
</div>

<div class="control-group {{ $errors->has('contact_email')? 'error':'' }}">
    {{ Form::text('contact_email',$contact->email,array('placeholder' => Lang::get('content.contact_email'),'class' => 'input-block-level')) }}
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="control-group {{ $errors->has('contact_phone')? 'error':'' }}">
            {{ Form::text('contact_phone',$contact->phone,array('placeholder' => Lang::get('content.contact_phone'),'class' => 'input-block-level')) }}
        </div>
    </div>
    <div class="span6">
        <div class="control-group {{ $errors->has('contact_city')? 'error':'' }}">
            {{ Form::text('contact_city',$contact->city,array('placeholder' => Lang::get('content.contact_city'),'class' => 'input-block-level')) }}
        </div>
    </div>
</div>
{{ Form::hidden('contact_id',$contact->id) }}
