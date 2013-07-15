@extends('layout_login')
@section('content')
    <div class="step">
        <div class="row-fluid">
            <div class="span1"></div>
            <div class="span10">
                {{ Form::open(array('url' => 'registro/step3','class'=>'big-form')) }}
                <div class="pull-right">{{ Auth::user()->full_name }}</div>
                <h3 class='header'>{{ Lang::get('content.contacts_header') }}</h3>
                <div class="row-fluid">
                    <div class="span4">
                        <fieldset>
                            <div class="control-group @if($errors->has('contact_full_name')) error @endif">
                                {{ Form::text('contact_full_name',null,array('placeholder' => Lang::get('content.contact_full_name'),'class' => 'input-block-level')) }}
                            </div>

                            <div class="control-group @if($errors->has('contact_email')) error @endif">
                                {{ Form::text('contact_email',null,array('placeholder' => Lang::get('content.contact_email'),'class' => 'input-block-level')) }}
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group @if($errors->has('contact_phone')) error @endif">
                                        {{ Form::text('contact_phone',null,array('placeholder' => Lang::get('content.contact_phone'),'class' => 'input-block-level')) }}
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group @if($errors->has('contact_city')) error @endif">
                                        {{ Form::text('contact_city',null,array('placeholder' => Lang::get('content.contact_city'),'class' => 'input-block-level')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="control-group @if($errors->has('contact_address')) error @endif">
                                {{ Form::text('contact_address',null,array('placeholder' => Lang::get('content.contact_address'),'class' => 'input-block-level')) }}
                            </div>
                            <div class="register-controls text-right">
                                {{ Form::submit(Lang::get('content.contact_add'),array('class' => 'btn btn-large btn-info')) }}
                            </div>
                        </fieldset>
                    </div>
                    <div class="span8">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ Lang::get('content.contact_full_name') }}</th>
                                    <th>{{ Lang::get('content.contact_email') }}</th>
                                    <th>{{ Lang::get('content.contact_phone') }}</th>
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
                                        <td>{{ $contact->phone }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <a class="btn btn-large btn-success pull-right" href="{{ URL::to('/') }}">{{ Lang::get('content.publisher_finalize') }}</a>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="span1"></div>
        </div>
    </div>
@stop