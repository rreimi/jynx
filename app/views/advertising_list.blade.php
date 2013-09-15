@extends('layout_backend')

@section('sidebar')
<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'pub_list_form')) }}
    <div class="span11 pub-list-filters">
        <span class="nav-header">{{ Lang::get('content.backend_search_publication_title') }}</span>
        <div class="control-group">
            <div class="controls">
                {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
                <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
            </div>

        </div>

        <div class="control-group">
            <div class="controls">
                <a class="cursor-pointer" data-toggle="collapse" data-target="#search-options-box">{{Lang::get('content.advanced_search')}}</a>
            </div>
        </div>

        <div id="search-options-box" class="more-search-options collapse in">
            <div class="control-group">
                <label class="control-label text-left" for="filter_status">{{ Lang::get('content.filter_publication_status') }}</label>
                <div class="controls">
                    {{ Form::select('filter_status', $adv_statuses, $state['filter_status'], array('class' => 'input filter-field')) }}
                </div>
            </div>

<!--            if ($state['active_custom_filters'] > 0)-->
<!--            <button class="btn btn-small reset-fields" type="button">{{Lang::get('content.reset_search')}} <i class="icon-remove"></i></button>-->
<!--            endif-->
        </div>
        <hr/>
    </div>
    {{ Form::close() }}
</div>
@parent
@stop

@section('content')

<div class="row-fluid">
    <h1>{{Lang::get('content.advertisings')}} <a href="{{URL::to('publicidad/crear')}}" class="btn btn-small btn-info">{{Lang::get('content.new_advertising')}}</a></h1>
    {{ Form::open(array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'adv_list_form')) }}
    <div class="row-fluid pub-list-btn-group">
        {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
        {{ Form::select('filter_status', $adv_statuses, $state['filter_status'], array('class' => 'input-medium filter-field')) }}
        <button class="btn btn-warning" type="submit">{{Lang::get('content.search')}}</button>
    </div>

    <div class="span11 pub-list-filters">

    </div>

    {{ Form::close() }}

    <table class="adv-table table table-bordered table-condensed">
        <thead>
        <tr>
            <th>{{Lang::get('content.name')}}</th>
            <th>{{Lang::get('content.status')}}</th>
            <th>{{Lang::get('content.order')}}</th>
            <th>{{Lang::get('content.image_url')}}</th>
            <th>{{Lang::get('content.external_url')}}</th>
            <th>{{Lang::get('content.full_name')}}</th>
            <th>-</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($advertisings as $key => $adv)
        <tr>
            <td>{{ $adv->name }}</td>
            <td>{{ Lang::get('content.status_'. $adv->status) }}</td>
            <td>{{ $adv->order }}</td>
            <td>{{ $adv->image_url }}</td>
            <td>{{ $adv->external_url }}</td>
            <td>{{ $adv->full_name }}</td>
            <td>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn" href="{{URL::to('publicidad/editar/' . $adv->id)}}"><i class="icon-pencil"></i></a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn" href="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_advertising_delete_title') }}', '{{ Lang::get('content.modal_advertising_delete_content') }}', '{{URL::to('publicidad/eliminar/' . $adv->id)}}');">
                    <i class="icon-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $advertisings->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('select.filter-field').bind('change', function(){
            jQuery('#adv_list_form').submit();
        })

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
        })
    });
</script>
@stop