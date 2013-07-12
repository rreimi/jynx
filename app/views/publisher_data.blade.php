@extends('layout_login')
@section('content')
    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span10">
            {{ Form::open(array('url' => 'registro/publicador','class'=>'big-form')) }}
            <div class="pull-right">{{ Auth::user()->last_name.', '.Auth::user()->first_name }}</div>
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
                                        'Person' => Lang::get('content.publisher_type_person'),
                                        'Business' => Lang::get('content.publisher_type_business')),
                                'Person',
                                array('class'=>'input-block-level')
                            ) }}
                        </div>
                    </div>
                </div>
                <div class="control-group @if($errors->has('publisher_seller')) error @endif">
                    {{ Form::text('publisher_seller',null,array('placeholder' => Lang::get('content.publisher_seller'),'class' => 'input-block-level')) }}
                </div>

                <div class="control-group @if($errors->has('publisher_media')) error @endif">
                    {{ Form::text('publisher_media',null,array('placeholder' => Lang::get('content.publisher_media'),'class' => 'input-block-level')) }}
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group @if($errors->has('publisher_state')) error @endif">
                            {{ Form::select('publisher_state',$states,Input::old('publisher_type'),array('class'=>'input-block-level')) }}
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
                <div class="register-controls text-right">
                    {{ Form::submit(Lang::get('content.publisher_create'),array('class' => 'btn btn-large btn-warning')) }}
                </div>
                @if($errors->any() && ($errors->has('publisher_id') || $errors->has('publisher_seller') || $errors->has('publisher_media') ||
                        $errors->has('publisher_city') || $errors->has('publisher_phone1') || $errors->has('publisher_phone2')))
                    <div class="alert alert-error">{{ Lang::get('content.publisher_error') }}</div>
                @endif
            </fieldset>
            {{ Form::close() }}
        </div>
        <div class="span1"></div>
    </div>
@stop