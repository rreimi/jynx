<div class="control-group {{ $errors->has('full_name')? 'error':'' }}">
    {{ Form::text('full_name',$contact->full_name,array('placeholder' => Lang::get('content.contact_full_name'),'class' => 'input-block-level')) }}
</div>

<div class="control-group {{ $errors->has('distributor')? 'error':'' }}">
    {{ Form::text('distributor',$contact->distributor,array('placeholder' => Lang::get('content.contact_distributor'),'class' => 'input-block-level')) }}
</div>

<div class="control-group {{ $errors->has('email')? 'error':'' }}">
    {{ Form::text('email',$contact->email,array('placeholder' => Lang::get('content.contact_email'),'class' => 'input-block-level required email')) }}
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="control-group {{ $errors->has('phone')? 'error':'' }}">
            {{ Form::text('phone',$contact->phone,array('placeholder' => Lang::get('content.contact_phone1'),'class' => 'input-block-level required phone-number-format')) }}
        </div>
    </div>
    <div class="span6">
        <div class="control-group {{ $errors->has('other_phone')? 'error':'' }}">
            {{ Form::text('other_phone',$contact->other_phone,array('placeholder' => Lang::get('content.contact_phone2'),'class' => 'input-block-level phone-number-format')) }}
        </div>
    </div>
</div>
<div class="control-group {{ $errors->has('city')? 'error':'' }}">
    {{ Form::text('city',$contact->city,array('placeholder' => Lang::get('content.contact_city'),'class' => 'input-block-level')) }}
</div>
<div class="control-group {{ $errors->has('address')? 'error':'' }}">
    {{ Form::text('address',$contact->address,array('placeholder' => Lang::get('content.contact_address'),'class' => 'input-block-level')) }}
</div>

{{ Form::hidden('id', $contact->id) }}

<script type="text/javascript">
    jQuery('.phone-number-format').mask("9999-9999999");
</script>
