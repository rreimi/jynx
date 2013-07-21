@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid">
        {{ Form::open(array('url' => 'perfil', 'class' => 'form-horizontal' )) }}

        <h1>{{Lang::get('content.profile_edit')}}</h1>
        <h2 id="basico">{{Lang::get('content.profile_edit_basic')}}</h2>
        <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
            <label class="control-label" for="title">{{ Lang::get('content.profile_email') }}</label>
            <div class="controls">
                {{ Form::text('profile_email', $user->email, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_email'))) }}
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
                    {{ Form::text('profile_password', null, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_password'))) }}
                    {{ $errors->first('profile_password', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_password_confirmation') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_password_confirmation') }}</label>
                <div class="controls">
                    {{ Form::text('profile_password_confirmation', null, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.profile_password_confirmation'))) }}
                    {{ $errors->first('profile_password_confirmation', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
        </div>

        @if(Auth::user()->isPublisher())
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
                    {{ Form::select('profile_publisher_type',
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
                    {{ Form::select('profile_id_type',
                        array('' => Lang::get('content.select')),
                        Input::old('profile_id_type'),
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
                        array_merge(array('' => Lang::get('content.select')),$states),
                        Input::old('profile_state'),
                        array('class'=>'input-large'))
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
                    {{ Form::text('profile_phone1', $user->publisher->phone1, array('class' => 'input-large','placeholder'=> Lang::get('content.profile_phone1'))) }}
                    {{ $errors->first('profile_phone1', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>

            <div class="control-group {{ $errors->has('profile_phone2') ? 'error':'' }}">
                <label class="control-label" for="long_description">{{ Lang::get('content.profile_phone2') }}</label>
                <div class="controls">
                    {{ Form::text('profile_phone2', $user->publisher->phone2, array('class' => 'input-large','placeholder'=> Lang::get('content.profile_phone2'))) }}
                    {{ $errors->first('profile_phone2', '<div class="field-error alert alert-error">:message</div>') }}
                </div>
            </div>
            <h2 id="sectores">{{Lang::get('content.profile_edit_sectors')}}</h2>
            <div class="control-group">
                <div class="controls">
                @foreach ($categories as $key => $category)
                    @if ($key % 4 == 0)
                        <div class="row-fluid">
                    @endif

                    <label class="span3 checkbox checkbox-category">
                        {{ Form::checkbox('publisher_categories[]',$category->id,in_array($category->id,Input::old('publisher_categories',array()))) }}
                        {{ $category->name }}
                    </label>

                    @if ((($key+1)%4) == 0)
                        </div>
                    @endif
                @endforeach
                </div>
            </div>
        @endif


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
                });
        });
    </script>
@stop
