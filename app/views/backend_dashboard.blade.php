@extends('layout_backend')

@section('sidebar')
@parent
    <p>This is appended to the master sidebar.</p>
@stop

@section('content')

    <div class="hero-unit">

        <p>paja de instrucciones de como usar el backend, un well tal vez se vea mejor que hero-unit aqui en backend</p>

    </div>



    <div class="row-fluid">


        <table class="table table-bordered table-condensed">
            <caption>Usuarios que quieren pub</caption>
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