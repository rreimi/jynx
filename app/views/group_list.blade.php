@extends('layout_backend')

@section('sidebar')
<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'group_list_form')) }}
        <div class="span11 group-list-filters">
            <span class="nav-header">{{ Lang::get('content.backend_search_group_title') }}</span>
            <div class="control-group">
                <div class="controls">
                    {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.group_search_placeholder')))}}
                    <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
                </div>
            </div>

            <div id="search-options-box" class="more-search-options collapse in">
                <div class="control-group">
                    <label class="control-label text-left" for="filter_status">{{ Lang::get('content.filter_group_status') }}</label>
                    <div class="controls">
                        {{ Form::select('filter_status', $group_statuses, $state['filter_status'], array('id' => 'filter_status', 'class' => 'filter-field')) }}
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
    <h1>{{Lang::get('content.groups')}} <a href="{{URL::to('grupo/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_group')}}</a></h1>

    <table class="group-table table table-bordered table-condensed">
        <thead>
        <tr>
            <th class="title"><a href="{{UrlHelper::fullUrltoogleSort('group_name')}}">{{Lang::get('content.group_name')}} <i class="{{UrlHelper::getSortIcon('group_name')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.group_status')}} <i class="{{UrlHelper::getSortIcon('status')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('users_in_group')}}">{{Lang::get('content.group_users_in_group')}} <i class="{{UrlHelper::getSortIcon('users_in_group')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('created_at')}}">{{Lang::get('content.created_at')}} <i class="{{UrlHelper::getSortIcon('created_at')}}"></i></a></th>
            <th class="options">-</th>
        </tr>
        </thead>
        <tbody>
        @if (count($groups) > 0)
            @foreach ($groups as $key => $group)
            <tr>
                <td>{{ $group->group_name }}</td>
                <td>{{ Lang::get('content.status_'. $group->status) }}</td>
                <td>{{ $group->group_users_in_group }}</td>
                <td>{{ date(Lang::get('content.date_format_php'),strtotime($group->created_at)) }}</td>
                <td>
                    <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn" href="{{URL::to('grupo/editar/' . $group->id)}}">
                        <i class="icon-pencil"></i>
                    </a>
                    <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn" nohref onclick="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_group_delete_title') }}', '{{ Lang::get('content.modal_group_delete_content') }}', '{{URL::to('grupo/eliminar/' . $group->id)}}')">
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
    {{ $groups->appends(Input::only('sort','order'))->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('#group_list_form').submit();
        });
    });
</script>
@stop