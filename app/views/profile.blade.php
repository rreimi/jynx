@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid profile-form">
        {{ Form::open(array('url' => 'perfil', 'class' => 'form-horizontal' )) }}

        <h1>{{Lang::get('content.profile_edit')}}</h1>
        <h2 id="basico">{{Lang::get('content.profile_edit_basic')}}</h2>
        <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
            <label class="control-label" for="title">{{ Lang::get('content.profile_email') }}</label>
            <div class="controls">
                {{ Form::text('profile_email', $user->email, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_email'), 'readonly' => 'readonly')) }}
                {{ $errors->first('profile_email', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('profile_full_name') ? 'error':'' }}">
            <label class="control-label" for="long_description">{{ Lang::get('content.profile_full_name') }}</label>
            <div class="controls">
                {{ Form::text('profile_full_name', $user->full_name, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_full_name'))) }}
                {{ $errors->first('profile_full_name', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <button class="btn btn-password" type="button" data-toggle="collapse" data-target=".collapse-password">{{Lang::get('content.profile_change_password')}}</button>
            </div>
        </div>
        <div class="collapse collapse-password out">
            <div class="control-group {{ $errors->has('profile_password') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_password') }}</label>
                <div class="controls">
                    {{ Form::password('profile_password', null, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_password'), 'disabled' => 'disabled')) }}
                    {{ $errors->first('profile_password', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_password_confirmation') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_password_confirmation') }}</label>
                <div class="controls">
                    {{ Form::password('profile_password_confirmation', null, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_password_confirmation'), 'disabled' => 'disabled')) }}
                    {{ $errors->first('profile_password_confirmation', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
        </div>

        @if($user->isPublisher())
            <h2 id="publicador">{{Lang::get('content.profile_edit_publisher')}}</h2>
            <div class="control-group {{ $errors->has('profile_seller_name') ? 'error':'' }}">
                <label class="control-label" for="title">{{ Lang::get('content.profile_seller_name') }}</label>
                <div class="controls">
                    {{ Form::text('profile_seller_name', $user->publisher->seller_name, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_seller_name'))) }}
                    {{ $errors->first('profile_seller_name', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_publisher_type') ? 'error':'' }}">
                <label class="control-label" for="title">{{ Lang::get('content.profile_publisher_type') }}</label>
                <div class="controls">
                    {{ Form::select('publisher_type',
                        array(
                            '' => Lang::get('content.select'),
                            'Person' => Lang::get('content.publisher_type_person'),
                            'Business' => Lang::get('content.publisher_type_business')
                        ),
                        $user->publisher->publisher_type,
                        array('class'=>'input-xlarge publisher_type')
                    ) }}
                    {{ $errors->first('profile_publisher_type', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ ($errors->has('profile_id_type') || $errors->has('profile_id')) ? 'error':'' }}">
                <label class="control-label" for="title">{{ Lang::get('content.profile_id') }}</label>
                <div class="controls controls-row">
                    {{ Form::select('letter_rif_ci',
                        array('' => Lang::get('content.select')),
                        $user->publisher->letter_rif_ci,
                        array('class'=>'input-small publisher_id_type')
                    ) }}
                    {{ Form::text('profile_id', $user->publisher->rif_ci, array('class' => 'input-medium','placeholder'=> Lang::get('content.profile_id'))) }}

                    {{ $errors->first('profile_id', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_state') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_state') }}</label>
                <div class="controls">
                    {{ Form::select('profile_state',
                        array_merge(array('' => Lang::get('content.select')), $states),
                        $user->publisher->state_id,
                        array('class'=>'input-xlarge'))
                    }}
                    {{ $errors->first('profile_state', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_city') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_city') }}</label>
                <div class="controls">
                    {{ Form::text('profile_city', $user->publisher->city, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_city'))) }}
                    {{ $errors->first('profile_city', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_phone1') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_phone1') }}</label>
                <div class="controls">
                    {{ Form::text('profile_phone1', $user->publisher->phone1, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_phone1'))) }}
                    {{ $errors->first('profile_phone1', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_phone2') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_phone2') }}</label>
                <div class="controls">
                    {{ Form::text('profile_phone2', $user->publisher->phone2, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_phone2'))) }}
                    {{ $errors->first('profile_phoAuth::user()ne2', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
            @if(Auth::user()->isPublisher())
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

                        @if ((($key+1)%4) == 0)
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{ Form::close() }}
        @endif
        @if(Auth::user()->isPublisher())
            <h2 id="contactos">{{Lang::get('content.profile_edit_contacts')}}</h2>
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
<!--                            TODO: FALTA LA FUNCIONALIDAD DE ELIMINAR-->
                            <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn">
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
                <button type="submit" class="btn">{{ Lang::get('content.cancel') }}</button>
                <button type="submit" class="btn btn-success">{{ Lang::get('content.save') }}</button>
            </div>
        </div>

    </div>


    {{ Form::open(array('url' => 'contact', 'class' => 'form-horizontal' )) }}
        <div id="editContact" class="modal hide fade" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>{{ Lang::get('profile.edit_contact') }}</h3>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">{{ Lang::get('content.cancel') }}</button>
                <button class="btn btn-primary">{{ Lang::get('content.save') }}</button>
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

@stop

@section('scripts')
@parent
    <script type="text/javascript">
        jQuery(function(){
            jQuery('.collapse-password')
                .on('show',function(){
                    jQuery('.btn-password').button('toggle');
                })
                .on('hide',function(){
                    jQuery('.btn-password').button('toggle');
                    jQuery("input:password").val('');
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

            jQuery('.publisher_type').on('change',function(){
                if(this.value=='Person'){
                    $('option:not(.default)', '.publisher_id_type').remove();
                    $('.publisher_id_type').append(new Option('V-', 'V')).append(new Option('E-', 'E'));
                }else if(this.value=='Business'){
                    $('option:not(.default)', '.publisher_id_type').remove();
                    $('.publisher_id_type').append(new Option('J-', 'J')).append(new Option('G-', 'G'));
                }else{
                    $('option:not(.default)', '.publisher_id_type').remove();
                }
            });

            jQuery("input:password").val('');

            //TODO insisto debe existir una mejor forma de hacer esto
            if(jQuery('.publisher_type').val()=='Person'){
                $('.publisher_id_type').append(new Option('V-', 'V')).append(new Option('E-', 'E'));
            }else if(jQuery('.publisher_type').val()=='Business'){
                $('.publisher_id_type').append(new Option('J-', 'J')).append(new Option('G-', 'G'));
            }

            jQuery('.publisher_type').trigger('change');
            jQuery('.publisher_id_type').val("{{ !is_null(Input::old('letter_rif_ci'))? Input::old('letter_rif_ci'): $user->publisher->letter_rif_ci }}");
        });

    </script>
@stop
