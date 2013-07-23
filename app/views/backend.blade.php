@extends('layout_backend')

@section('content')
    <div class="well">
        <p>instrucciones de como usar el backend, un well tal vez se vea mejor que hero-unit aqui en backend</p>
    </div>
    <div class="row-fluid">

        {{ Form::open(array('url' => 'dashboard/approve','class'=>'')) }}

            <table class="table table-bordered table-condensed">
                <h2>{{ Lang::get('content.backend_users_section_title') }}</h2>
                <thead>
                <tr>
                    <th>{{ Lang::get('content.backend_full_name') }}</th>
                    <th>{{ Lang::get('content.backend_email') }}</th>
                    <th>{{ Lang::get('content.backend_id') }}</th>
                    <th>{{ Lang::get('content.backend_seller') }}</th>
                    <th>{{ Lang::get('content.backend_phone') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->publisher->letter_rif_ci }}-{{ $user->publisher->rif_ci }}</td>
                        <td>{{ $user->publisher->seller_name }}</td>
                        <td>{{ $user->publisher->phone1 }} @if (!empty($user->publisher->phone2)) / {{ $user->publisher->phone2 }} @endif</td>
                        <td>{{ Form::checkbox('approve_users[]',$user->id,in_array($user->id,Input::old('approve_users',array()))) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-right">
                <button class="btn btn-success" type="submit">{{ Lang::get('content.backend_user_approve') }}</button>
            </div>

            {{ $users->links() }}
        {{ Form::close() }}

        <table class="table table-bordered table-condensed">
            <h2>{{ Lang::get('content.backend_reports_section_title') }}</h2>
            <thead>
            <tr>
                <th>NAME</th>
                <th>XXX</th>
                <th>XXX</th>
                <th>XXX</th>
                <th>XXX</th>
                <th>XXX</th>
                <th>ACTIVE</th>
                <th>-</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>VALUE</td>
                    <td>VALUE</td>
                    <td>VALUE</td>
                    <td>VALUE</td>
                    <td>VALUE</td>
                    <td>VALUE</td>
                    <td>VALUE</td>
                    <td>VALUE</td>
                </tr>

            </tbody>
        </table>

    </div>

@stop