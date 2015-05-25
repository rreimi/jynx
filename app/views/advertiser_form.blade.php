@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    {{ Form::open(array('url' => 'anunciante/guardar', 'method' => 'post', 'class' => 'form-horizontal advertiser-form', 'enctype' => 'multipart/form-data')) }}
        @if (!isset($user->id))
        <h1>{{Lang::get('content.new_advertiser')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_advertiser')}}: {{ $user->full_name }}</h1>
        @endif

        <h2 id="basico">{{Lang::get('content.advertiser_edit_basic')}}</h2>
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

        <div class="control-group {{ $errors->has('name') ? 'error':'' }}">
            <label class="control-label required-field" for="name">{{ Lang::get('content.user_name') }}</label>
            <div class="controls">
                {{ Form::text('full_name', $user->full_name, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.user_name'))) }}
                {{ $errors->first('full_name', '<div class="field-error alert alert-error">:message</div>') }}
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

        <div class="control-group {{ $errors->has('group') ? 'error':'' }}">
            <label class="control-label required-field" for="group">{{ Lang::get('content.user_group') }}</label>
            <div class="controls">
                @if (Auth::user()->isAdmin())
                    {{ Form::select('group', $groups, $user->group_id, array('class'=>'required group-field')) }}
                @else
                    <label class="label-value">{{ $groups[$user->group_id] }}</label>
                @endif
                {{ $errors->first('group', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <button class="btn btn-password" type="button" data-toggle="collapse" data-target=".collapse-password">{{Lang::get('content.advertiser_change_password')}}</button>
            </div>
        </div>
        <div class="collapse collapse-password out">
            <div class="control-group {{ $errors->has('password') ? 'error':'' }}">
                <label class="control-label required-field" for="long_description">{{ Lang::get('content.advertiser_password') }}</label>
                <div class="controls">
                    {{ Form::password('password', null, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.advertiser_password'))) }}
                    {{ $errors->first('password', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
            <div class="control-group {{ $errors->has('password_confirmation') ? 'error':'' }}">
                <label class="control-label required-field" for="long_description">{{ Lang::get('content.advertiser_password_confirmation') }}</label>
                <div class="controls">
                    {{ Form::password('password_confirmation', null, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.advertiser_password_confirmation'))) }}
                    {{ $errors->first('password_confirmation', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
        </div>

        <h2 id="anunciante">{{Lang::get('content.advertiser_edit_publisher')}}</h2>
        <div class="control-group {{ $errors->has('publisher_seller') ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_seller">{{ Lang::get('content.publisher_seller') }}</label>
            <div class="controls">
                {{ Form::text('publisher_seller', $advertiser->seller_name, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.publisher_seller'))) }}
                {{ $errors->first('seller_name', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

    <div class="control-group {{ $errors->has('description') ? 'error':'' }}">
        <label class="control-label required-field" for="description">{{ Lang::get('content.description') }}</label>
        <div class="controls">
            {{ Form::textarea('description', $advertiser->description, array('class' => 'input-xxlarge required'))}}
            {{ $errors->first('description', '<div class="field-error alert alert-error">:message</div>') }}
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

        <div class="control-group letter-rif-ci {{ ($errors->has('publisher_id_type') || $errors->has('publisher_id')) ? 'error':'' }}">
            <label class="control-label required-field" for="publisher_id_type">{{ Lang::get('content.advertiser_id') }}</label>
            <div class="controls">
                {{ Form::select('publisher_id_type', array('' => Lang::get('content.select')), $advertiser->letter_rif_ci, array('class'=>'input-small publisher_id_type required')) }}
                {{ Form::text('publisher_id', $advertiser->rif_ci, array('class' => 'input-medium required rif-ci', 'placeholder'=> Lang::get('content.publisher_id'))) }}
                {{ $errors->first('letter_rif_ci', '<div class="field-error alert alert-error">:message</div>') }}
                {{ $errors->first('rif_ci', '<div class="field-error alert alert-error">:message</div>') }}
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

        <div class="control-group {{ $errors->has('publisher_address') ? 'error':'' }}">
            <label class="control-label" for="publisher_address">{{ Lang::get('content.publisher_address') }}</label>
            <div class="controls">
                {{ Form::text('publisher_address', $advertiser->address, array('class' => 'input-xlarge', 'placeholder'=> Lang::get('content.publisher_address'))) }}
                {{ $errors->first('address', '<div class="field-error alert alert-error">:message</div>') }}
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

        <div class="control-group {{ $errors->has('publisher_web') ? 'error':'' }}">
            <label class="control-label" for="publisher_web">{{ Lang::get('content.publisher_web_page') }}</label>
            <div class="controls">
                {{ Form::text('publisher_web', $advertiser->web, array('class' => 'input-xlarge url', 'placeholder'=> Lang::get('content.publisher_web_page'))) }}
                <label class="external-url-label">{{ Lang::get('content.external_url_label') }}</label>
                {{ $errors->first('web', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_media') ? 'error':'' }}">
            <label class="control-label" for="publisher_media">{{ Lang::get('content.publisher_media') }}</label>
            <div class="controls">
                {{ Form::text('publisher_media', $advertiser->media, array('class' => 'input-xlarge', 'placeholder'=> Lang::get('content.publisher_media'))) }}
                {{ $errors->first('media', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_avatar') ? 'error':'' }}">
            <label class="control-label" for="long_description">{{ Lang::get('content.publisher_avatar') }}</label>
            <div class="controls">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
                    <div>
                        <span class="btn btn-file">
                            <span class="fileupload-new">{{ Lang::get('content.fileuploader_select_image') }}</span>
                            <span class="fileupload-exists">{{ Lang::get('content.fileuploader_change') }}</span>
                            <input type="file" name="publisher_avatar" />
                            <input type="hidden" name="avatar_action" />
                        </span>
                        <a href="#" class="btn fileupload-exists remove-avatar-button" data-dismiss="fileupload">{{ Lang::get('content.fileuploader_remove') }}</a>
                    </div>
                </div>
                {{ $errors->first('avatar', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <!-- Categories -->
        <div class="control-group categories-form">
            <h2 id="sectores" class="required-field">{{Lang::get('content.advertiser_edit_sectors')}}</h2>
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

                <div class="suggest-section suggest-products">
                    <div class="selection">
                        {{ Form::checkbox('publisher_suggest_products', Input::old('publisher_suggest_products'), $user->publisher->suggest_products) }}
                        <label class="description">{{ Lang::get('content.profile_suggest_products_label') }}</label>
                    </div>
                    <div class="suggestions hide">
                        {{ Form::text('publisher_suggested_products', $user->publisher->suggested_products, array('data-role' => 'tagsinput','class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_placeholder_add_suggest'))) }}
                        {{ $errors->first('suggested_products', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>
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

                <div class="suggest-section suggest-services">
                    <div class="selection">
                        {{ Form::checkbox('publisher_suggest_services', Input::old('publisher_suggest_services'), $user->publisher->suggest_services) }}
                        <label class="description">{{ Lang::get('content.profile_suggest_services_label') }}</label>
                    </div>
                    <div class="suggestions hide">
                        {{ Form::text('publisher_suggested_services', $user->publisher->suggested_services, array('data-role' => 'tagsinput','class' => 'input-xlarge tessst','placeholder'=> Lang::get('content.profile_placeholder_add_suggest'))) }}
                        {{ $errors->first('suggested_services', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>
            </ul>

        </div>

        <h2 id="contactos">{{Lang::get('content.advertiser_edit_contacts')}}
            <a class="btn btn-info btn-small modal-contact" data-target="#addContact" data-remote="{{URL::to('contacto/agregar') }}">
                {{Lang::get('content.contact_add_contact')}}
            </a>
        </h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>{{ Lang::get('content.contact_full_name') }}</th>
                <th>{{ Lang::get('content.contact_email') }}</th>
                <th>{{ Lang::get('content.contact_phone') }}</th>
                <th>{{ Lang::get('content.contact_city') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if(count($user->publisher->contacts)==0)
            <tr>
                <td colspan="5">{{ Lang::get('content.contact_not_found') }}</td>
            </tr>
            @endif
            @foreach ($user->publisher->contacts as $contact)
            @if (!$contact->isMainContact())
            <tr>
                <td>{{ $contact->full_name }}</td>
                <td>{{ $contact->email }}</td>
                <td>
                    {{ $contact->phone }}
                    @if($contact->other_phone)
                    , {{ $contact->other_phone }}
                    @endif
                </td>
                <td>{{ $contact->city }}</td>
                <td class="table-cell-controls">
                    <div class="btn-group">
                        <a rel="tooltip" title="{{Lang::get('content.view')}}" class="btn modal-contact" type="button" data-target="#viewContact" data-remote="{{URL::to('contacto/detalle/'.$contact->id) }}">
                            <i class="icon-search"></i>
                        </a>
                        <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn modal-contact" type="button" data-target="#editContact" data-remote="{{URL::to('contacto/editar/'.$contact->id) }}">
                            <i class="icon-pencil"></i>
                        </a>
                        <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn delete-contact" data-id="{{ $contact->id }}">
                            <i class="icon-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>

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

    {{ Form::open(array('url' => 'contacto/editar', 'class' => 'form-horizontal edit-contact-form' )) }}
    <div id="editContact" class="modal hide fade" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>{{ Lang::get('content.profile_edit_contact') }}</h3>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">{{ Lang::get('content.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ Lang::get('content.save') }}</button>
        </div>
    </div>
    {{ Form::hidden('referer', URL::full()) }}
    {{ Form::close() }}

    {{ Form::open(array('url' => 'contacto', 'class' => 'big-form add-contact-form' )) }}
    <div id="addContact" class="modal hide fade" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>{{ Lang::get('content.contact_add_contact') }}</h3>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">{{ Lang::get('content.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ Lang::get('content.save') }}</button>
        </div>
    </div>
    {{ Form::hidden('advertiser_id', $advertiser->id) }}
    {{ Form::close() }}

    <div id="viewContact" class="modal hide fade" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>{{ Lang::get('content.profile_view_contact') }}</h3>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">{{ Lang::get('content.close') }}</button>
        </div>
    </div>

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/bootstrap-tagsinput.min.js') }}
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
        jQuery('.add-contact-form').validateBootstrap({placement:'bottom'});
        jQuery('.edit-contact-form').validateBootstrap({placement:'bottom'});

        // Set if exists avatar
        if ('{{ $avatar }}'){
            jQuery('.fileupload').removeClass('fileupload-new');
            jQuery('.fileupload').addClass('fileupload-exists');
            jQuery('.fileupload-preview').html('<img src="{{ $avatar }}" />');
        }

        // Avatar delete action
        jQuery('.remove-avatar-button').click(function(){
            jQuery('input[name=avatar_action]').val('delete-avatar');
        });

        // Password
        jQuery('.btn-password').click(function() {
            if (!jQuery('.collapse-password').hasClass('in')){
                jQuery("input:password").removeAttr('disabled');
                jQuery('.btn-password').button('toggle');
                jQuery("input:password").addClass('required');
            } else {
                jQuery('.btn-password').button('toggle');
                jQuery("input:password").val('');
                jQuery("input:password").attr('disabled', 'disabled');
                jQuery("input:password").removeClass('required');
            }
        });

        var passwordError = {{ $errors->has('password') || $errors->has('password_confirmation') ? 'true' : 'false' }};

        if (passwordError){
            jQuery("input:password").val('');
            jQuery('.btn-password').click();
        } else {
            jQuery("input:password").val('');
            jQuery("input:password").attr('disabled', 'disabled');
        }

        jQuery('.status_publisher').bind("change", function(){
            if (jQuery('select.status_publisher').val() == '{{ Publisher::STATUS_SUSPENDED }}'){
                jQuery('.advertiser-status-publisher-warning').show();
            } else {
                jQuery('.advertiser-status-publisher-warning').hide();
            }
        });

        jQuery('.status_publisher').trigger('change');

        jQuery('.modal-contact').on('click',function(){
            var remote=jQuery(this).data('remote');
            var target=jQuery(this).data('target');

            jQuery.ajax({
                url: remote,
                cache: false,
                success: function(html){
                    jQuery(target).children('.modal-body').html(html);
                    jQuery(target).modal('show');
                }
            });
        });

        jQuery('.delete-contact').on('click',function(){
            Mercatino.modalConfirm.show(
                '{{ Lang::get('content.profile_delete_contact_title') }}',
                '{{ Lang::get('content.profile_delete_contact_content') }}',
                '{{ URL::to('contacto/eliminar/') }}' + '/' + jQuery(this).data('id') + '?referer={{ URL::full() }}'
            );
        });

        // Manage suggest sections
        Mercatino.prepareSuggestions();

        // Phone mask
        jQuery('.phone-number-format').mask("9999-9999999");
    });
</script>
@stop