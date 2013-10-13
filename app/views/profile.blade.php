@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid profile-form">
        {{ Form::open(array('url' => 'perfil', 'class' => 'form-horizontal perfil-form', 'enctype' => 'multipart/form-data' )) }}

            <h1>{{Lang::get('content.profile_edit')}}</h1>
            <h2 id="basico">{{Lang::get('content.profile_edit_basic')}}</h2>
            <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
                <label class="control-label required-field" for="title">{{ Lang::get('content.profile_email') }}</label>
                <div class="controls">
                    {{ Form::text('email', $user->email, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.profile_email'), 'readonly' => 'readonly')) }}
                    {{ $errors->first('email', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('full_name') ? 'error':'' }}">
                <label class="control-label required-field" for="long_description">{{ Lang::get('content.profile_full_name') }}</label>
                <div class="controls">
                    {{ Form::text('full_name', $user->full_name, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.profile_full_name'))) }}
                    {{ $errors->first('full_name', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-password" type="button" data-toggle="collapse" data-target=".collapse-password">{{Lang::get('content.profile_change_password')}}</button>
                </div>
            </div>
            <div class="collapse collapse-password out">
                <div class="control-group {{ $errors->has('current-password') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.profile_current_password') }}</label>
                    <div class="controls">
                        {{ Form::password('current-password', null, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.profile_current_password'))) }}
                        {{ $errors->first('current-password', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.profile_password') }}</label>
                    <div class="controls">
                        {{ Form::password('password', null, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.profile_password'))) }}
                        {{ $errors->first('password', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password_confirmation') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.profile_password_confirmation') }}</label>
                    <div class="controls">
                        {{ Form::password('password_confirmation', null, array('class' => 'input-xlarge required', 'placeholder'=> Lang::get('content.profile_password_confirmation'))) }}
                        {{ $errors->first('password_confirmation', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
            </div>

            @if($user->isPublisher())
                <h2 id="anunciante">{{Lang::get('content.profile_edit_publisher')}}</h2>
                <div class="control-group {{ $errors->has('seller_name') ? 'error':'' }}">
                    <label class="control-label required-field" for="title">{{ Lang::get('content.profile_seller_name') }}</label>
                    <div class="controls">
                        {{ Form::text('seller_name', $user->publisher->seller_name, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.profile_seller_name'))) }}
                        {{ $errors->first('seller_name', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('publisher_type') ? 'error':'' }}">
                    <label class="control-label required-field" for="title">{{ Lang::get('content.profile_publisher_type') }}</label>
                    <div class="controls">
                        {{ Form::select('publisher_type',
                            array(
                                '' => Lang::get('content.select'),
                                'Person' => Lang::get('content.publisher_type_person'),
                                'Business' => Lang::get('content.publisher_type_business')
                            ),
                            $user->publisher->publisher_type,
                            array('class'=>'input-xlarge publisher_type required')
                        ) }}
                        {{ $errors->first('publisher_type', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group letter-rif-ci {{ ($errors->has('letter_rif_ci') || $errors->has('rif_ci')) ? 'error':'' }}">
                    <label class="control-label required-field" for="title">{{ Lang::get('content.profile_id') }}</label>
                    <div class="controls controls-row">
                        {{ Form::select('letter_rif_ci',
                            array('' => Lang::get('content.select')),
                            $user->publisher->letter_rif_ci,
                            array('class'=>'input-small publisher_id_type required')
                        ) }}
                        {{ Form::text('rif_ci', $user->publisher->rif_ci, array('class' => 'input-medium required','placeholder'=> Lang::get('content.profile_id'))) }}

                        {{ $errors->first('rif_ci', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('state') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.profile_state') }}</label>
                    <div class="controls">
                        {{ Form::select('state',
                            $states,
                            $user->publisher->state_id,
                            array('class'=>'input-xlarge required'))
                        }}
                        {{ $errors->first('state', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('city') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.profile_city') }}</label>
                    <div class="controls">
                        {{ Form::text('city', $user->publisher->city, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.profile_city'))) }}
                        {{ $errors->first('city', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('phone1') ? 'error':'' }}">
                    <label class="control-label required-field" for="long_description">{{ Lang::get('content.profile_phone1') }}</label>
                    <div class="controls">
                        {{ Form::text('phone1', $user->publisher->phone1, array('class' => 'input-xlarge required','placeholder'=> Lang::get('content.profile_phone1'))) }}
                        <label class="phone-format-label">{{ Lang::get('content.phone_format_label') }}</label>
                        {{ $errors->first('phone1', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('phone2') ? 'error':'' }}">
                    <label class="control-label" for="long_description">{{ Lang::get('content.profile_phone2') }}</label>
                    <div class="controls">
                        {{ Form::text('phone2', $user->publisher->phone2, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_phone2'))) }}
                        <label class="phone-format-label">{{ Lang::get('content.phone_format_label') }}</label>
                        {{ $errors->first('phone2', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="long_description">{{ Lang::get('content.profile_avatar') }}</label>
                    <div class="controls">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
                            <div>
                                <span class="btn btn-file">
                                    <span class="fileupload-new">{{ Lang::get('content.fileuploader_select_image') }}</span>
                                    <span class="fileupload-exists">{{ Lang::get('content.fileuploader_change') }}</span>
                                    <input type="file" name="avatar" />
                                </span>
                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">{{ Lang::get('content.fileuploader_remove') }}</a>
                            </div>
                        </div>
                        {{ $errors->first('avatar', '<div class="field-error alert alert-error">:message</div>') }}
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label required-label">{{ Lang::get('content.required_label') }}</label>
                </div>

                <h2 id="sectores">{{Lang::get('content.profile_edit_sectors')}}</h2>
                <div class="control-group">
                    @foreach ($categories as $key => $category)
                        @if ($key % 4 == 0)
                            <div class="row-fluid">
                        @endif

                        <label class="span3 checkbox checkbox-category">
                            {{ Form::checkbox('publisher_categories[]',$category->id,in_array($category->id,$categoriesSelected)) }}
                            {{ $category->name }}
                        </label>

                        @if ((($key+1)%4) == 0 || (($key+1) == count($categories)))
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            @if(Auth::user()->isPublisher())
                <h2 id="contactos">{{Lang::get('content.profile_edit_contacts')}}
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
                        <tr>
                            <td>{{ $contact->full_name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->phone }}</td>
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
                    @endforeach
                    </tbody>
                </table>
            @endif

            <div class="control-group">
                <div class="controls">
                    <a href="{{ URL::to('perfil') }}" class="btn">{{ Lang::get('content.cancel') }}</a>
                    <button type="submit" class="btn btn-success">{{ Lang::get('content.save') }}</button>
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
        {{ Form::hidden('referer', URL::to('perfil')) }}
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

    </div>
@stop

@section('scripts')
@parent
    <script type="text/javascript">
        jQuery(document).ready(function(){

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
                    '{{ URL::to('contacto/eliminar/') }}'+'/'+jQuery(this).data('id')
                );
            });

            var passwordError = {{ $errors->has('password') || $errors->has('current-password') || $errors->has('password_confirmation') ? 'true' : 'false' }};
            if (passwordError){
                jQuery("input:password").val('');
                jQuery('.btn-password').click();
            } else {
                jQuery("input:password").val('');
                jQuery("input:password").attr('disabled', 'disabled');
            }

            @if(Auth::user()->isPublisher())
            var publisherType=jQuery('.publisher_type');
            var publisherIdType=jQuery('.publisher_id_type');

            publisherType.on('change',function(){
                jQuery('option:not(.default)', '.publisher_id_type').remove();
                if(this.value=='Person'){
                    publisherIdType.append(new Option('{{ Lang::get('content.select') }}', '')).append(new Option('V-', 'V')).append(new Option('E-', 'E'));
                } else if(this.value=='Business'){
                    publisherIdType.append(new Option('{{ Lang::get('content.select') }}', '')).append(new Option('J-', 'J')).append(new Option('G-', 'G'));
                } else {
                    publisherIdType.append(new Option('{{ Lang::get('content.select') }}', ''));
                }
            });

            if(publisherType.val()=='Person'){
                publisherIdType.append(new Option('{{ Lang::get('content.select') }}', '')).append(new Option('V-', 'V')).append(new Option('E-', 'E'));
            } else if(publisherType.val()=='Business'){
                publisherIdType.append(new Option('{{ Lang::get('content.select') }}', '')).append(new Option('J-', 'J')).append(new Option('G-', 'G'));
            } else {
                publisherIdType.append(new Option('{{ Lang::get('content.select') }}', ''));
            }

            publisherType.trigger('change');
            publisherIdType.val("{{ !is_null(Input::old('letter_rif_ci'))? Input::old('letter_rif_ci'): $user->publisher->letter_rif_ci }}");
            @endif

            jQuery('.perfil-form').validateBootstrap();
            jQuery('.add-contact-form').validateBootstrap({placement:'bottom'});
            jQuery('.edit-contact-form').validateBootstrap({placement:'bottom'});

            // Set if exists avatar
            if ('{{ $avatar }}'){
                jQuery('.fileupload').removeClass('fileupload-new');
                jQuery('.fileupload').addClass('fileupload-exists');
                jQuery('.fileupload-preview').html('<img src="{{ $avatar }}" />');
            }

        });

    </script>
@stop
