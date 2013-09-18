@extends('layout_backend')

@section('sidebar')
<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'adv_list_form')) }}
    <div class="span11 pub-list-filters">
        <span class="nav-header">{{ Lang::get('content.backend_search_publication_title') }}</span>
        <div class="control-group">
            <div class="controls">
                {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
                <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
            </div>

        </div>

        <div id="search-options-box" class="more-search-options collapse in">
            <div class="control-group">
                <label class="control-label text-left" for="filter_status">{{ Lang::get('content.filter_publication_status') }}</label>
                <div class="controls">
                    {{ Form::select('filter_status', $adv_statuses, $state['filter_status'], array('class' => 'input filter-field')) }}
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
    <h1>{{Lang::get('content.advertisings')}} <a href="{{URL::to('publicidad/crear')}}" class="btn btn-small btn-info">{{Lang::get('content.new_advertising')}}</a></h1>

    <table class="adv-table table table-bordered table-condensed">
        <thead>
        <tr>
            <th><a href="{{UrlHelper::fullUrltoogleSort('name')}}">{{Lang::get('content.name')}} <i class="{{UrlHelper::getSortIcon('name')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.status')}} <i class="{{UrlHelper::getSortIcon('status')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('order')}}">{{Lang::get('content.order')}} <i class="{{UrlHelper::getSortIcon('order')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('image_url')}}">{{Lang::get('content.image_url')}} <i class="{{UrlHelper::getSortIcon('image_url')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('external_url')}}">{{Lang::get('content.external_url')}} <i class="{{UrlHelper::getSortIcon('external_url')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('full_name')}}">{{Lang::get('content.full_name')}} <i class="{{UrlHelper::getSortIcon('full_name')}}"></i></a></th>
            <th>-</th>
        </tr>
        </thead>
        <tbody>
        @if (count($advertisings) > 0)
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
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn" nohref onclick="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_advertising_delete_title') }}', '{{ Lang::get('content.modal_advertising_delete_content') }}', '{{URL::to('publicidad/eliminar/' . $adv->id)}}');">
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
    {{ $advertisings->appends(Input::only('sort','order'))->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('#adv_list_form').submit();
        });
    });
</script>
@stop