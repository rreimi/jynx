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
                    array_merge(array('' => Lang::get('content.select')), $advertiser_statuses),
                    $user->status,
                    array('class'=>'required')
                ) }}
                {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

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

        <div class="control-group">
            <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
        </div>

        <!-- Categories -->
        <div class="control-group categories-form">
            <h5>{{ Lang::get('content.publisher_categories') }}</h5>
            @if ($errors->has('categories'))
            <div class="field-error alert alert-error">{{ $errors->first('categories') }}</div>
            @endif

            <ul class="float-left categories-form-list">
                <li><h2>{{Lang::get('content.categories_title')}}</h2></li>
                @foreach ($categories as $cat)
                <li>
                    @if (count($cat->subcategories) > 0)
                    <span class="float-left cursor-pointer collpase-subcategories" data-toggle="collapse" data-target="#subcategories_for_{{ $cat->id }}">+</span>
                    @endif
                    <label class="checkbox checkbox-category-form">
                        {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $advertiser_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
                    </label>
                    <ul id="subcategories_for_{{ $cat->id }}" class="subcategories-list collapse @if ( in_array($cat->id, (array) $advertiser_categories)) in @endif">
                        @foreach ($cat->subcategories as $subcat)
                        <li>
                            <label class="checkbox">
                                {{ Form::checkbox('categories[]', $subcat->id, in_array($subcat->id, (array) $advertiser_categories), array('class' => 'chk-sub-cat', 'data-parent-id' => $cat->id)) }} {{ $subcat->name }}
                            </label>
                            <ul>
                                @foreach ($subcat->subcategories as $thirdcat)
                                <li>
                                    <label class="checkbox">
                                        {{ Form::checkbox('categories[]', $thirdcat->id, in_array($thirdcat->id, (array) $advertiser_categories), array('class' => 'chk-third-cat', 'data-parent-id' => $subcat->id)) }} {{ $thirdcat->name }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>

            <ul class="float-left">
                <li><h2>{{Lang::get('content.services_title')}}</h2></li>
                @foreach ($services as $cat)
                <li>
                    @if (count($cat->subcategories) > 0)
                    <span class="float-left cursor-pointer collpase-subcategories" data-toggle="collapse" data-target="#subcategories_for_{{ $cat->id }}">+</span>
                    @endif
                    <label class="checkbox checkbox-category-form">
                        {{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, (array) $advertiser_categories), array('class' => 'chk-cat')) }} {{ $cat->name }}
                    </label>
                    <ul id="subcategories_for_{{ $cat->id }}" class="subcategories-list collapse @if ( in_array($cat->id, (array) $advertiser_categories)) in @endif">
                        @foreach ($cat->subcategories as $subcat)
                        <li>
                            <label class="checkbox">
                                {{ Form::checkbox('categories[]', $subcat->id, in_array($subcat->id, (array) $advertiser_categories), array('class' => 'chk-sub-cat', 'data-parent-id' => $cat->id)) }} {{ $subcat->name }}
                            </label>
                            <ul>
                                @foreach ($subcat->subcategories as $thirdcat)
                                <li>
                                    <label class="checkbox">
                                        {{ Form::checkbox('categories[]', $thirdcat->id, in_array($thirdcat->id, (array) $advertiser_categories), array('class' => 'chk-third-cat', 'data-parent-id' => $subcat->id)) }} {{ $thirdcat->name }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </div>

        {{ Form::hidden('id', $advertiser->id) }}
        {{ Form::hidden('referer', $referer) }}

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
    });
</script>
@stop