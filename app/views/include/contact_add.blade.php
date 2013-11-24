<div class="contact-form">
    <div class="control-group {{ $errors->has('full_name')? 'error':'' }}">
        {{ Form::text('contact_full_name', null,array('placeholder' => Lang::get('content.contact_full_name'),'class' => 'input-block-level')) }}
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
                {{ Form::text('contact_phone',null,array('placeholder' => Lang::get('content.contact_phone1'),'class' => 'input-block-level required phone-number-format')) }}
            </div>
        </div>
        <div class="span6">
            <div class="control-group {{ $errors->has('other_phone')? 'error':'' }}">
                {{ Form::text('contact_other_phone',null,array('placeholder' => Lang::get('content.contact_phone2'),'class' => 'input-block-level phone-number-format')) }}
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group {{ $errors->has('state')? 'error':'' }}">
                {{ Form::select('contact_state',
                $states,
                Input::old('contact_state'),
                array('class'=>'input-block-level states'))
                }}
            </div>
        </div>
        <div class="span6">
            <div class="control-group {{ $errors->has('city')? 'error':'' }}">
                {{ Form::text('contact_city',null,array('placeholder' => Lang::get('content.contact_city'),'class' => 'input-block-level')) }}
            </div>
        </div>
    </div>
    <div class="control-group {{ $errors->has('address')? 'error':'' }}">
        {{ Form::text('contact_address',null,array('placeholder' => Lang::get('content.contact_address'),'class' => 'input-block-level')) }}
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        // Phone mask
        jQuery('.phone-number-format').mask("9999-9999999");

        jQuery(".states option[value!='']").addClass('state-option');

        jQuery(".states").change(function(){
            if (jQuery(this).val() == ''){
                jQuery(this).addClass('states');
            } else {
                jQuery(this).removeClass('states');
            }
        });

    });
</script>