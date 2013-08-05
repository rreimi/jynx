@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    {{ Form::open(array('url' => 'anunciante/guardar', 'method' => 'post', 'class' => 'form-horizontal')) }}
        @if (!isset($advertiser->id))
        <h1>{{Lang::get('content.new_advertiser')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_advertirser')}}: {{ $advertiser->full_name }}</h1>
        @endif

        <div class="control-group {{ $errors->has('name') ? 'error':'' }}">
            <label class="control-label" for="name">{{ Lang::get('content.user_name') }}</label>
            <div class="controls">
                {{ Form::text('full_name', $advertiser->full_name, array('class' => 'input-xlarge','placeholder'=> Lang::get('content.user_name'))) }}
                {{ $errors->first('full_name', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('email') ? 'error':'' }}">
            <label class="control-label" for="email">{{ Lang::get('content.user_email') }}</label>
            <div class="controls">
                {{ Form::text('email', $advertiser->email, array('class' => 'input-xlarge', 'placeholder'=> Lang::get('content.user_email'))) }}
                {{ $errors->first('email', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
            <label class="control-label" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select('status', $advertiser_statuses, $advertiser->status) }}
                {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('publisher_type') ? 'error':'' }}">
            <label class="control-label" for="status">{{ Lang::get('content.publisher_type') }}</label>
            <div class="controls">
                {{ Form::select('publisher_type',
                            array(
                            '' => Lang::get('content.select_person_type'),
                            'Person' => Lang::get('content.publisher_type_person'),
                            'Business' => Lang::get('content.publisher_type_business')),
                            $advertiser->publisher) }}
                {{ $errors->first('publisher_type', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>












            <div class="row-fluid">

                <div class="span2">
                    <div class="control-group {{ $errors->has('publisher_id_type')? 'error':'' }}">
                        {{ Form::select('publisher_id_type',
                        array('' => Lang::get('content.select')),
                        Input::old('publisher_id_type'),
                        array('class'=>'input-block-level publisher_id_type required')
                        ) }}
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group {{ $errors->has('publisher_id')? 'error':'' }}">
                        {{ Form::text('publisher_id',null,array('placeholder' => Lang::get('content.publisher_id'),'class' => 'input-block-level numeric-only required')) }}
                    </div>
                </div>

            </div>
            <div class="control-group {{ $errors->has('publisher_seller')? 'error':'' }}">
                {{ Form::text('publisher_seller',null,array('placeholder' => Lang::get('content.publisher_seller'),'class' => 'input-block-level required')) }}
            </div>

            <div class="control-group {{ $errors->has('publisher_media')? 'error':'' }}">
                {{ Form::text('publisher_media',null,array('placeholder' => Lang::get('content.publisher_media'),'class' => 'input-block-level required')) }}
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group {{ $errors->has('publisher_state')? 'error':'' }}">
                        {{ Form::select('publisher_state',
                        array_merge(array('' => Lang::get('content.select_state')),$states),
                        Input::old('publisher_state'),
                        array('class'=>'input-block-level required'))
                        }}
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group {{ $errors->has('publisher_city')? 'error':'' }}">
                        {{ Form::text('publisher_city',null,array('placeholder' => Lang::get('content.publisher_city'),'class' => 'input-block-level required')) }}
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group {{ $errors->has('publisher_phone1')? 'error':'' }}">
                        {{ Form::text('publisher_phone1',null,array('placeholder' => Lang::get('content.publisher_phone1'),'class' => 'input-block-level required')) }}
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group {{ $errors->has('publisher_phone2')? 'error':'' }}">
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

                    <label class="span3 checkbox checkbox-category">
                        {{ Form::checkbox('publisher_categories[]',$category->id,in_array($category->id,Input::old('publisher_categories',array()))) }}
                        {{ $category->name }}
                    </label>

                    @if ((($key+1)%4) == 0)
                </div>
                @endif
                @endforeach
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