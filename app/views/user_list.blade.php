@extends('layout_backend')

@section('sidebar')
<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'user_list_form')) }}
        <div class="span11 user-list-filters">
            <span class="nav-header">{{ Lang::get('content.backend_search_user_title') }}</span>
            <div class="control-group">
                <div class="controls">
                    {{Form::text('q', $state['q'], array('class' => 'input-large filter-field', 'placeholder' => Lang::get('content.user_search_placeholder')))}}
                    <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
                </div>
            </div>

            <div id="search-options-box" class="more-search-options collapse in">
                <div class="control-group">
                    <label class="control-label text-left" for="filter_status">{{ Lang::get('content.filter_publication_status') }}</label>
                    <div class="controls">
                        {{ Form::select('filter_status', $user_statuses, $state['filter_status'], array('class' => 'filter-field')) }}
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label text-left" for="filter_role">{{ Lang::get('content.filter_user_role') }}</label>
                    <div class="controls">
                        {{ Form::select('filter_role', $user_roles, $state['filter_role'], array('class' => 'filter-field')) }}
                    </div>
                </div>
            </div>
            @if ($state['active_filters'] > 0)
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-small reset-fields" type="button">{{Lang::get('content.reset_search')}} <i class="icon-remove"></i></button>
                </div>
            </div>
            @endif
        </div>
    {{ Form::close() }}
</div>
@parent
@stop

@section('content')
<div class="row-fluid">
    <h1>{{Lang::get('content.users')}} <a href="{{URL::to('usuario/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_user_admin')}}</a></h1>



    <table class="user-table table table-bordered table-condensed">
        <thead>
        <tr>
            <th class="small"><a href="{{UrlHelper::fullUrltoogleSort('id')}}">{{Lang::get('content.id')}} <i class="{{UrlHelper::getSortIcon('id')}}"></i></a></th>
            <th class="title"><a href="{{UrlHelper::fullUrltoogleSort('full_name')}}">{{Lang::get('content.user_name')}} <i class="{{UrlHelper::getSortIcon('full_name')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('email')}}">{{Lang::get('content.user_email')}} <i class="{{UrlHelper::getSortIcon('email')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('role')}}">{{Lang::get('content.user_role')}} <i class="{{UrlHelper::getSortIcon('role')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.user_status')}} <i class="{{UrlHelper::getSortIcon('status')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('created_at')}}">{{Lang::get('content.created_at')}} <i class="{{UrlHelper::getSortIcon('created_at')}}"></i></a></th>
            <th class="options">-</th>
        </tr>
        </thead>
        <tbody>
        @if (count($users) > 0)
        @foreach ($users as $key => $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->full_name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ Lang::get('content.user_role_'. $user->role) }}</td>
            <td>{{ Lang::get('content.status_'. $user->status) }}</td>
            <td>{{ $user->created_at }}</td>
            <td>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn" href="{{URL::to('usuario/editar/' . $user->id)}}">
                    <i class="icon-pencil"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn" nohref onclick="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_user_delete_title') }}', '{{ Lang::get('content.modal_user_delete_content') }}', '{{URL::to('usuario/eliminar/' . $user->id)}}')">
                    <i class="icon-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7"><div class="text-center">{{Lang::get('content.no_elements_to_list')}}</div></td>
        </tr>
        @endif
        </tbody>
    </table>
    {{ $users->appends(Input::only('sort','order'))->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('#user_list_form').submit();
        });
    });
</script>
@stop