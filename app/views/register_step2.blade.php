@extends('layout_login')
@section('content')
    <div class="step">
        <div class="row-fluid">
            <div class="span1"></div>
            <div class="span10">
                {{ Form::open(array('url' => 'registro/step2','class'=>'big-form')) }}
                <div class="pull-right">{{ Auth::user()->full_name }}</div>
                <h3 class='header'>{{ Lang::get('content.publisher_header') }}</h3>
                <fieldset>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group @if($errors->has('publisher_id')) error @endif">
                                {{ Form::text('publisher_id',null,array('placeholder' => Lang::get('content.publisher_id'),'class' => 'input-block-level')) }}
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group @if($errors->has('publisher_type')) error @endif">
                                {{ Form::select('publisher_type',
                                    array(
                                            '' => Lang::get('content.select'),
                                            'Person' => Lang::get('content.publisher_type_person'),
                                            'Business' => Lang::get('content.publisher_type_business')
                                    ),
                                    Input::old('publisher_type'),
                                    array('class'=>'input-block-level')
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="control-group @if($errors->has('publisher_seller')) error @endif">
                        {{ Form::text('publisher_seller',Auth::user()->full_name,array('placeholder' => Lang::get('content.publisher_seller'),'class' => 'input-block-level')) }}
                    </div>

                    <div class="control-group @if($errors->has('publisher_media')) error @endif">
                        {{ Form::text('publisher_media',null,array('placeholder' => Lang::get('content.publisher_media'),'class' => 'input-block-level')) }}
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group @if($errors->has('publisher_state')) error @endif">
                                {{ Form::select('publisher_state',
                                    array_merge(array('' => Lang::get('content.select')),$states),
                                    Input::old('publisher_state'),
                                    array('class'=>'input-block-level'))
                                }}
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group @if($errors->has('publisher_city')) error @endif">
                                {{ Form::text('publisher_city',null,array('placeholder' => Lang::get('content.publisher_city'),'class' => 'input-block-level')) }}
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group @if($errors->has('publisher_phone1')) error @endif">
                                {{ Form::text('publisher_phone1',null,array('placeholder' => Lang::get('content.publisher_phone1'),'class' => 'input-block-level')) }}
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group @if($errors->has('publisher_phone2')) error @endif">
                                {{ Form::text('publisher_phone2',null,array('placeholder' => Lang::get('content.publisher_phone2'),'class' => 'input-block-level')) }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5>{{ Lang::get('content.publisher_categories') }}</h5>
                        @foreach ($categories as $key => $category)
                            @if ($key % 4 == 0)
                                <div class="row-fluid">
                            @endif

                            <label class="span3 checkbox">
                                {{ Form::checkbox('publisher_categories[]',$category->id,in_array($category->id,Input::old('publisher_categories',array()))) }}
                                {{ $category->name }}
                            </label>

                            @if ((($key+1)%4) == 0)
                                </div>
                            @endif
                        @endforeach
                    </div>

                </fieldset>

                <div class="register-controls text-right">
                    <a class="btn btn-large btn-info" href="{{ URL::to('/') }}">{{ Lang::get('content.register_finalize') }}</a>
                    {{ Form::submit(Lang::get('content.publisher_create'),array('class' => 'btn btn-large btn-warning')) }}
                </div>

                @if($errors->any())
                    <div class="alert alert-error">{{ Lang::get('content.publisher_error') }}</div>
                @endif
                {{ Form::close() }}
            </div>
            <div class="span1"></div>
        </div>
    </div>
@stop