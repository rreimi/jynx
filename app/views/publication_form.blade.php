@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid">
        <h1>{{Lang::get('content.my_publications')}}</h1>
        <div class="pub-list-btn-group">
            <a href="#create" class="btn btn-info">{{Lang::get('content.new_publication')}}</a>
        </div>
            <table class="pub-table">
                <thead>
                    <tr>
                        <th>{{Lang::get('content.id')}}</th>
                        <th>{{Lang::get('content.title')}}</th>
                        <th>{{Lang::get('content.from_date')}}</th>
                        <th>{{Lang::get('content.to_date')}}</th>
                        <th>{{Lang::get('content.visits_number')}}</th>
                        <th>{{Lang::get('content.created_at')}}</th>
                        <th>{{Lang::get('content.categories_name')}}</th>
                        <th>-</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publications as $key => $pub)
                    <tr>
                        <td>{{ $pub->id }}</td>
                        <td>{{ $pub->title }}</td>
                        <td>{{ $pub->from_date }}</td>
                        <td>{{ $pub->to_date }}</td>
                        <td>{{ $pub->visits_number }}</td>
                        <td>{{ $pub->created_at }}</td>
                        <td>{{ $pub->categories_name }}</td>
                        <td>
                            <a href="#edit">{{Lang::get('content.edit')}}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        {{ $publications->links() }}
    </div><!--/row-fluid-->
@stop