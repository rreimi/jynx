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
                                        '' => Lang::get('content.select_person_type'),
                                        'Person' => Lang::get('content.publisher_type_person'),
                                        'Business' => Lang::get('content.publisher_type_business')
                                    ),
                                    Input::old('publisher_type'),
                                    array('class'=>'input-block-level publisher_type required')
                                ) }}
                            </div>
                        </div>

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
                    @if ($groupsQty > 1)
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group {{ $errors->has('publisher_seller')? 'error':'' }}">
                                    {{ Form::text('publisher_seller',null,array('placeholder' => Lang::get('content.publisher_seller'),'class' => 'input-block-level required')) }}
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group {{ $errors->has('publisher_group')? 'error':'' }}">
                                    {{ Form::select('publisher_group',
                                    $groups,
                                    Input::old('publisher_group'),
                                    array('class'=>'input-block-level required'))
                                    }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="control-group {{ $errors->has('publisher_seller')? 'error':'' }}">
                            {{ Form::text('publisher_seller',null,array('placeholder' => Lang::get('content.publisher_seller'),'class' => 'input-block-level required')) }}
                        </div>
                    @endif
                    <div class="control-group {{ $errors->has('publisher_media')? 'error':'' }}">
                        {{ Form::text('publisher_media',null,array('placeholder' => Lang::get('content.publisher_media'),'class' => 'input-block-level')) }}
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="control-group {{ $errors->has('publisher_country')? 'error':'' }}">
                                {{ Form::select('publisher_country',
                                    $countries,
                                    !is_null(Input::old('publisher_country'))? Input::old('publisher_country') : $defaultCountry,
                                    array('class'=>'input-block-level required', 'id' => 'publisher_country'))
                                }}
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group {{ $errors->has('publisher_state')? 'error':'' }}">
                                {{ Form::select('publisher_state',
                                    $states,
                                    Input::old('publisher_state'),
                                    array('class'=>'input-block-level required', 'id' => 'publisher_state'))
                                }}
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group {{ $errors->has('publisher_city')? 'error':'' }}">
                                {{ Form::text('publisher_city',null,array('placeholder' => Lang::get('content.publisher_city'),'class' => 'input-block-level required')) }}
                            </div>
                        </div>
                    </div>
                    <div class="control-group {{ $errors->has('publisher_address')? 'error':'' }}">
                        {{ Form::text('publisher_address',null,array('placeholder' => Lang::get('content.publisher_address'),'class' => 'input-block-level')) }}
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group {{ $errors->has('publisher_phone1')? 'error':'' }}">
                                {{ Form::text('publisher_phone1',null,array('placeholder' => Lang::get('content.register_phone1'),'class' => 'input-block-level required phone-number-format')) }}
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group {{ $errors->has('publisher_phone2')? 'error':'' }}">
                                {{ Form::text('publisher_phone2',null,array('placeholder' => Lang::get('content.register_phone2'),'class' => 'input-block-level phone-number-format')) }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5 class="required-field">{{ Lang::get('content.publisher_categories') }}</h5>
                        @foreach ($all_categories as $key => $category)
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
                    <a class="btn btn-large btn-default" href="{{ URL::to('/') }}">{{ Lang::get('content.cancel') }}</a>
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


    <div id="startDialog" class="modal hide fade" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3 id="myModalLabel">@if ($activation_flag) {{ Lang::get('content.register_dialog_activated') }}. @endif {{ Lang::get('content.register_dialog_header') }}</h3>
        </div>
        <div class="modal-body">
            <p>{{ Lang::get('content.register_dialog_description') }}</p>
            <p>{{ Lang::get('content.register_dialog_description2') }}</p>


            <div class="text-center">
                <a class="btn btn-large" style="float: none; width: 40%; font-size: 13px;" href="{{ URL::to('/') }}">{{ Lang::get('content.register_dialog_cancel') }}</a>
                <button class="btn btn-large btn-warning publisher-info" style="width:40%" data-dismiss="modal">{{ Lang::get('content.register_dialog_continue') }}</button>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>

@stop

@section('scripts')
@parent
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var countryStatesUrl = '{{ URL::to('ajax/country-states/') }}/';

            jQuery('.numeric-only').numericField();
            //TODO buscar la manera de que esto este por defecto en laravel o en algún otro lado
            jQuery('option').each(function(i,object){
                if(object.value==''){
                    $(object).addClass('default');
                }
            });

            @if(!$hide_modal)
            jQuery('#startDialog').modal('show').css({
                width: '76%',
                left:'12%',
                'margin-left':'0'
            });
            @endif

            var publisherType=jQuery('.publisher_type');
            var publisherIdType=jQuery('.publisher_id_type');

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
            }else if(publisherType.val()=='Business'){
                publisherIdType.append(new Option('J-', 'J')).append(new Option('G-', 'G'));
            }

            publisherIdType.val("{{ Input::old('publisher_id_type','') }}");

            jQuery('.big-form').validateBootstrap({placement:'bottom'});

            // Phone mask
            jQuery('.phone-number-format').mask("9999-9999999");

            jQuery('#publisher_country').bind('change', function() {
                var countryId = jQuery(this).val();

                if (!countryId) {
                    updateSelect('#publisher_state', []);
                    return;
                }

                jQuery.ajax({
                    url: countryStatesUrl,
                    type: 'GET',
                    data: {country: countryId},
                    success: function(result) {
                        updateSelect('#publisher_state', result);
                    },
                    error: function(result) {
                        Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.app_generic_error')}}", type:'error'});
                    }
                });
            });

            //Populate states
            jQuery('#publisher_country').change();

            function updateSelect(itemSelector, elements) {

                var selectedValue = jQuery(itemSelector).val();

                jQuery(itemSelector).find('option:gt(0)').remove();
                for (var index in elements) {
                    jQuery(itemSelector).append($("<option />").val(index).text(elements[index]));
                }

                jQuery(itemSelector).val(selectedValue);
            }
        });
    </script>
@stop

