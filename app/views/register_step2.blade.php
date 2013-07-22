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
                            <div class="control-group {{ $errors->has('publisher_type')? 'error':'' }}">
                                {{ Form::select('publisher_type',
                                    array(
                                        '' => Lang::get('content.select'),
                                        'Person' => Lang::get('content.publisher_type_person'),
                                        'Business' => Lang::get('content.publisher_type_business')
                                    ),
                                    Input::old('publisher_type'),
                                    array('class'=>'input-block-level publisher_type')
                                ) }}
                            </div>
                        </div>
                        <div class="span2">
                            <div class="control-group {{ $errors->has('publisher_id_type')? 'error':'' }}">
                                {{ Form::select('publisher_id_type',
                                    array('' => Lang::get('content.select')),
                                    Input::old('publisher_id_type'),
                                    array('class'=>'input-block-level publisher_id_type')
                                ) }}
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group {{ $errors->has('publisher_id')? 'error':'' }}">
                                {{ Form::text('publisher_id',null,array('placeholder' => Lang::get('content.publisher_id'),'class' => 'input-block-level numeric-only')) }}
                            </div>
                        </div>

                    </div>
                    <div class="control-group {{ $errors->has('publisher_seller')? 'error':'' }}">
                        {{ Form::text('publisher_seller',Auth::user()->full_name,array('placeholder' => Lang::get('content.publisher_seller'),'class' => 'input-block-level')) }}
                    </div>

                    <div class="control-group {{ $errors->has('publisher_media')? 'error':'' }}">
                        {{ Form::text('publisher_media',null,array('placeholder' => Lang::get('content.publisher_media'),'class' => 'input-block-level')) }}
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group {{ $errors->has('publisher_state')? 'error':'' }}">
                                {{ Form::select('publisher_state',
                                    array_merge(array('' => Lang::get('content.select')),$states),
                                    Input::old('publisher_state'),
                                    array('class'=>'input-block-level'))
                                }}
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group {{ $errors->has('publisher_city')? 'error':'' }}">
                                {{ Form::text('publisher_city',null,array('placeholder' => Lang::get('content.publisher_city'),'class' => 'input-block-level')) }}
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group {{ $errors->has('publisher_phone1')? 'error':'' }}">
                                {{ Form::text('publisher_phone1',null,array('placeholder' => Lang::get('content.publisher_phone1'),'class' => 'input-block-level')) }}
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

@section('scripts')
@parent
    <script type="text/javascript">
        jQuery('.numeric-only').numericField();
        //TODO buscar la manera de que esto este por defecto en laravel o en algún otro lado
        jQuery('option').each(function(i,object){
            if(object.value==''){
                $(object).addClass('default');
            }
        });

        jQuery('.publisher_type').on('change',function(){
            if(this.value=='Person'){
                $('option:not(.default)', '.publisher_id_type').remove();
                $('.publisher_id_type').append(new Option('V-', 'V-')).append(new Option('E-', 'E-'));
            }else if(this.value=='Business'){
                $('option:not(.default)', '.publisher_id_type').remove();
                $('.publisher_id_type').append(new Option('J-', 'J-')).append(new Option('G-', 'G-'));
            }else{
                $('option:not(.default)', '.publisher_id_type').remove();
            }
        });

        //TODO insisto debe existir una mejor forma de hacer esto
        if(jQuery('.publisher_type').val()=='Person'){
            $('.publisher_id_type').append(new Option('V-', 'V-')).append(new Option('E-', 'E-'));
        }else if(jQuery('.publisher_type').val()=='Business'){
            $('.publisher_id_type').append(new Option('J-', 'J-')).append(new Option('G-', 'G-'));
        }

        jQuery('.publisher_id_type').val("{{ Input::old('publisher_id_type','') }}");

    </script>
@stop

