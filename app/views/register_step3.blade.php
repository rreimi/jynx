@extends('layout_login')
@section('content')
    <div class="step">
        <div class="row-fluid">
            <div class="span1"></div>
            <div class="span10">
                {{ Form::open(array('url' => 'contacto','class'=>'big-form contact-form')) }}
                <div class="pull-right">{{ Auth::user()->full_name }}</div>
                <h3 class='header'>{{ Lang::get('content.contacts_header') }}</h3>
                <div class="row-fluid">
                    <div class="span4">
                        <div class="control-group {{ $errors->has('contact_full_name')? 'error':'' }}">
                            {{ Form::text('contact_full_name',null,array('placeholder' => Lang::get('content.contact_full_name'),'class' => 'input-block-level')) }}
                        </div>

                        <div class="control-group {{ $errors->has('contact_distributor')? 'error':'' }}">
                            {{ Form::text('contact_distributor',null,array('placeholder' => Lang::get('content.contact_distributor'),'class' => 'input-block-level')) }}
                        </div>

                        <div class="control-group {{ $errors->has('contact_email')? 'error':'' }}">
                            {{ Form::text('contact_email',null,array('placeholder' => Lang::get('content.contact_email'),'class' => 'input-block-level required')) }}
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group {{ $errors->has('contact_phone')? 'error':'' }}">
                                    {{ Form::text('contact_phone',null,array('placeholder' => Lang::get('content.contact_phone1'),'class' => 'phone-number-format input-block-level required','data-placement'=>'bottom')) }}
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group {{ $errors->has('contact_other_phone')? 'error':'' }}">
                                    {{ Form::text('contact_other_phone',null,array('placeholder' => Lang::get('content.contact_phone2'),'class' => 'phone-number-format input-block-level')) }}
                                </div>
                            </div>
                        </div>
                        <div class="control-group {{ $errors->has('contact_state')? 'error':'' }}">
                            {{ Form::select('contact_state',
                            $states,
                            Input::old('contact_state'),
                            array('class'=>'input-block-level states'))
                            }}
                        </div>
                        <div class="control-group {{ $errors->has('contact_city')? 'error':'' }}">
                            {{ Form::text('contact_city',null,array('placeholder' => Lang::get('content.contact_city'),'class' => 'input-block-level')) }}
                        </div>
                        <div class="control-group {{ $errors->has('contact_address')? 'error':'' }}">
                            {{ Form::text('contact_address',null,array('placeholder' => Lang::get('content.contact_address'),'class' => 'input-block-level')) }}
                        </div>
                        <div class="register-controls text-right">
                            {{ Form::submit(Lang::get('content.contact_add'),array('class' => 'btn btn-large btn-info')) }}
                        </div>
                    </div>
                    <div class="span8">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ Lang::get('content.contact_full_name') }}</th>
                                    <th>{{ Lang::get('content.contact_email') }}</th>
                                    <th>{{ Lang::get('content.contact_phones') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($contacts))
                                    <tr>
                                        <td colspan="5">{{ Lang::get('content.contact_not_found') }}</td>
                                    </tr>
                                @endif
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td>{{ $contact->full_name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>
                                            {{ $contact->phone }}
                                            @if($contact->other_phone)
                                            , {{ $contact->other_phone }}
                                            @endif
                                        </td>
                                        <td class="table-cell-controls table-cell-controls-one">
                                            <div class="btn-group">
                                                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn delete-contact" data-id="{{ $contact->id }}">
                                                    <i class="icon-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <a class="btn btn-large btn-success pull-right" href="{{ URL::to('registro/finalizar') }}">{{ Lang::get('content.publisher_finalize') }}</a>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="span1"></div>
        </div>
    </div>
@stop


@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery('.big-form').validateBootstrap();

        jQuery(document).on('click','.delete-contact',function(){
            Mercatino.modalConfirm.show(
                '{{ Lang::get('content.profile_delete_contact_title') }}',
                '{{ Lang::get('content.profile_delete_contact_content') }}',
                '{{ URL::to('contacto/eliminar/') }}'+'/'+jQuery(this).data('id')
            );
        });

        jQuery('.phone-number-format').mask("9999-9999999");

        jQuery(".contact-form .states option[value!='']").addClass('state-option');

        jQuery(".contact-form .states").change(function(){
            if (jQuery(this).val() == ''){
                jQuery(this).addClass('states');
            } else {
                jQuery(this).removeClass('states');
            }
        });

    });
</script>
@stop

