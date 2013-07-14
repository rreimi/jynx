@extends('layout_backend')

@section('sidebar')
@parent
    <p>This is appended to the master sidebar.</p>
@stop

@section('content')
    <div class="well">
        <p>paja de instrucciones de como usar el backend, un well tal vez se vea mejor que hero-unit aqui en backend</p>
    </div>
    <div class="row-fluid">
        <table class="table table-bordered table-condensed">
            <caption>Usuarios que quieren pub</caption>
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
                    <td>{{ $user->last_name }}, {{ $user->first_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->publisher->rif_ci }}</td>
                    <td>{{ $user->publisher->seller_name }}</td>
                    <td>{{ $user->publisher->phone1 }} / {{ $user->publisher->phone2 }}</td>
                    <td>{{ Form::checkbox('users',$user->id) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}

        <table class="table table-bordered table-condensed">
            <caption>denuncias</caption>
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