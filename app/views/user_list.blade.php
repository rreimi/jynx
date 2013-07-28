@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')
<div class="row-fluid">
    <h1>{{Lang::get('content.users')}} <a href="{{URL::to('usuario/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_user_admin')}}</a></h1>

    {{ Form::open(array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'user_list_form')) }}

        <div class="row-fluid user-list-btn-group">
            {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.user_search_placeholder')))}}
            {{ Form::select('filter_status', $user_statuses, $state['filter_status'], array('class' => 'input-medium filter-field')) }}
            <button class="btn btn-warning" type="submit">{{Lang::get('content.search')}}</button>
        </div>

        <div class="span11 user-list-filters">

        </div>

    {{ Form::close() }}

    <table class="user-table table table-bordered table-condensed">
        <thead>
        <tr>
            <th class="small"><a href="{{UrlHelper::fullUrltoogleSort('id')}}">{{Lang::get('content.id')}}</a></th>
            <th class="title"><a href="{{UrlHelper::fullUrltoogleSort('full_name')}}">{{Lang::get('content.user_name')}}</a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('email')}}">{{Lang::get('content.user_email')}}</a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('role')}}">{{Lang::get('content.user_role')}}</a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.user_status')}}</a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('created_at')}}">{{Lang::get('content.created_at')}}</a></th>
            <th class="options">-</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $key => $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->full_name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ Lang::get('content.user_role_'. $user->role) }}</td>
            <td>{{ Lang::get('content.status_'. $user->status) }}</td>
            <td>{{ $user->created_at }}</td>
            <td>
                <a class="btn" href="{{URL::to('usuario/editar/' . $user->id)}}"><i rel="tooltip" title="{{Lang::get('content.edit')}}" class="icon-pencil"></i></a>
                <a class="btn" href="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_user_delete_title') }}', '{{ Lang::get('content.modal_user_delete_content') }}', '{{URL::to('usuario/eliminar/' . $user->id)}}')"><i rel="tooltip" title="{{Lang::get('content.delete')}}" class="icon-trash"></i></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('select.filter-field').bind('change', function(){
            jQuery('#user_list_form').submit();
        })

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
        })
    });
</script>
@stop