@extends('layout_backend')

@section('sidebar')
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