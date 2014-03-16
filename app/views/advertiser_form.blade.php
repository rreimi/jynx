@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    {{ Form::open(array('url' => 'anunciante/guardar', 'method' => 'post', 'class' => 'form-horizontal advertiser-form')) }}
        @if (!isset($user->id))
        <h1>{{Lang::get('content.new_advertiser')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_advertiser')}}: {{ $user->full_name }}</h1>
        @endif

        <div class="control-group {{ $errors->has('name') ? 'error':'' }}">
            <label class="control-label required-field" for="name">{{ Lang::get('content.user_name') }}</label>
            <div class="controls">
                {{ Form::text('full_name', $user->full_name, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.user_name'))) }}
                {{ $errors->first('full_name', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
            <label class="control-label required-field" for="email">{{ Lang::get('content.user_email') }}</label>
            <div class="controls">
                @if (!isset($user->id))
                    {{ Form::text('email', $user->email, array('class' => 'input-xlarge required email', 'placeholder'=> Lang::get('content.user_email'))) }}
                @else
                    {{ Form::text('email', $user->email, array('class' => 'input-xlarge required email', 'placeholder'=> Lang::get('content.user_email'), 'readonly' => 'readonly')) }}
                @endif
                {{ $errors->first('email', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
            <label class="control-label required-field" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select(
                    'status',
                    array_merge(array('' => Lang::get('content.select')), $user_statuses),
                    $user->status,
                    array('class'=>'required')
                ) }}
                {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('status_publisher') ? 'error':'' }}">
            <label class="control-label required-field" for="status_publisher">{{ Lang::get('content.status_publisher') }}</label>
            <div class="controls">
                {{ Form::select(
                'status_publisher',
                array_merge(array('' => Lang::get('content.select')), $advertiser_statuses),
                $advertiser->status_publisher,
                array('class'=>'status_publisher')
                ) }}
                {{ $errors->first('status_publisher', '<div class="field-error alert alert-error">:message</div>') }}
                <div class="text-warning alert-block advertiser-status-publisher-warning">{{ Lang::get('content.publisher_status_publisher_warning') }}</div>
            </div>
        </div>

        @if (isset($user->id))
            <div class="control-group {{ $errors->has('role') ? 'error':'' }}">
                <label class="control-label required-field" for="role">{{ Lang::get('content.role') }}</label>
                <div class="controls">
                    {{ Form::select(
                    'role',
                    array_merge(array('' => Lang::get('content.select')), $advertiser_roles),
                    $user->role,
                    array('class'=>'role')
                    ) }}
                    {{ $errors->first('role', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
        @endif

        <div class="control-group {{ $errors->has('publisher_type') ? 'error':'' }}">
            <label class="control-label required-field" for="status">{{ Lang::get('content.publisher_type') }}</label>
            <div class="controls">
                {{ Form::select('publisher_type',
                            array(
                            '' => Lang::get('content.select'),
                            'Person' => Lang::get('content.publisher_type_person'),
                            'Business' => Lang::get('content.publisher_type_business')),
                            $advertiser->publisher_type,
                            array('class'=>'publisher_type required')
                ) }}
                {{ $errors->first('publisher_type', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_id_type') ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_id_type">{{ Lang::get('content.select_id_type') }}</label>
            <div class="controls">
                {{ Form::select('publisher_id_type', array('' => Lang::get('content.select')), $advertiser->letter_rif_ci, array('class'=>'publisher_id_type required')) }}
                {{ $errors->first('letter_rif_ci', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_id') ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_id">{{ Lang::get('content.publisher_id') }}</label>
            <div class="controls">
                {{ Form::text('publisher_id', $advertiser->rif_ci, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.publisher_id'))) }}
                {{ $errors->first('rif_ci', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_seller') ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_seller">{{ Lang::get('content.publisher_seller') }}</label>
            <div class="controls">
                {{ Form::text('publisher_seller', $advertiser->seller_name, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.publisher_seller'))) }}
                {{ $errors->first('seller_name', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_media') ? 'error':'' }}">
            <label class="control-label" for="publisher_media">{{ Lang::get('content.publisher_media') }}</label>
            <div class="controls">
                {{ Form::text('publisher_media', $advertiser->media, array('class' => 'input-xlarge', 'placeholder'=> Lang::get('content.publisher_media'))) }}
                {{ $errors->first('media', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_state') ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_state">{{ Lang::get('content.publisher_state') }}</label>
            <div class="controls">
                {{ Form::select(
                    'publisher_state',
                    $states,
                    $advertiser->state_id,
                    array('class'=>'required')
                ) }}
                {{ $errors->first('state_id', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_city') ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_city">{{ Lang::get('content.publisher_city') }}</label>
            <div class="controls">
                {{ Form::text('publisher_city', $advertiser->city, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.publisher_city'))) }}
                {{ $errors->first('city', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_phone1') ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_phone1">{{ Lang::get('content.publisher_phone1') }}</label>
            <div class="controls">
                {{ Form::text('publisher_phone1', $advertiser->phone1, array('class' => 'input-xlarge required phone-number-format', 'placeholder'=> Lang::get('content.publisher_phone1'))) }}
                <label class="phone-format-label">{{ Lang::get('content.phone_format_label') }}</label>
                {{ $errors->first('phone1', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_phone2') ? 'error':'' }}">
            <label class="control-label" for="publisher_phone2">{{ Lang::get('content.publisher_phone2') }}</label>
            <div class="controls">
                {{ Form::text('publisher_phone2', $advertiser->phone2, array('class' => 'input-xlarge phone-number-format', 'placeholder'=> Lang::get('content.publisher_phone2'))) }}
                <label class="phone-format-label">{{ Lang::get('content.phone_format_label') }}</label>
                {{ $errors->first('phone2', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <!-- Categories -->
        <div class="control-group categories-form">
            <h2 id="sectores" class="required-field">{{Lang::get('content.profile_edit_sectors')}}</h2>
            @if ($errors->has('categories'))
            <div class="field-error alert alert-error">{{ $errors->first('categories') }}</div>
            @endif

            <ul class="float-left categories-form-list">
                <li><h5>{{Lang::get('content.product_title')}}</h5></li>
                @foreach ($products as $cat)
                <li>
                    <label class="checkbox checkbox-category-form">
                        {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $advertiser_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
                    </label>
                </li>
                @endforeach
            </ul>

            <ul class="float-left">
                <li><h5>{{Lang::get('content.services_title')}}</h5></li>
                @foreach ($services as $cat)
                <li>
                    <label class="checkbox checkbox-category-form">
                        {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $advertiser_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
                    </label>
                </li>
                @endforeach
            </ul>
        </div>

        {{ Form::hidden('id', $advertiser->id) }}
        {{ Form::hidden('referer', $referer) }}

        <div class="control-group">
            <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
        </div>

        <div class="control-group">
            <div class="controls">
                <a href="{{ $referer }}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
                <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.save')}}</button>
            </div>
        </div>


    {{ Form::close() }}

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        var publisherType = jQuery('.publisher_type');
        var publisherIdType = jQuery('.publisher_id_type');

        publisherType.on('change',function(){
            jQuery('option:not(.default)', '.publisher_id_type').remove();
            if(this.value=='Person'){
                jQuery('.publisher_id_type').append(new Option('V-', 'V')).append(new Option('E-', 'E'));
            }else if(this.value=='Business'){
                jQuery('.publisher_id_type').append(new Option('J-', 'J')).append(new Option('G-', 'G'));
            }
        });

        if(publisherType.val()=='Person'){
            publisherIdType.append(new Option('V-', 'V')).append(new Option('E-', 'E'));
        } else if(publisherType.val()=='Business'){
            publisherIdType.append(new Option('J-', 'J')).append(new Option('G-', 'G'));
        }

        publisherIdType.val("{{ $advertiser->letter_rif_ci }}");

        jQuery('.advertiser-form').validateBootstrap();

        // Phone mask
        jQuery('.phone-number-format').mask("9999-9999999");

        jQuery('.status_publisher').bind("change", function(){
            if (jQuery('select.status_publisher').val() == '{{ Publisher::STATUS_SUSPENDED }}'){
                jQuery('.advertiser-status-publisher-warning').show();
            } else {
                jQuery('.advertiser-status-publisher-warning').hide();
            }
        });

        jQuery('.status_publisher').trigger('change');
    });
</script>
@stop