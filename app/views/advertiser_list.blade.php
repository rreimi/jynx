@extends('layout_backend')

@section('sidebar')
@parent
@stop

@section('content')
<div class="row-fluid">
    <h1>{{Lang::get('content.advertisers')}} <a href="{{URL::to('anunciante/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_advertiser')}}</a></h1>

    {{ Form::open(array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'advertiser_list_form')) }}

        <div class="row-fluid advertiser-list-btn-group">
            {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.advertiser_search_placeholder')))}}
            {{ Form::select('filter_status', $advertiser_statuses, $state['filter_status'], array('class' => 'input-medium filter-field')) }}
            <button class="btn btn-warning" type="submit">{{Lang::get('content.search')}}</button>
        </div>

        <div class="span11 user-list-filters">

        </div>

    {{ Form::close() }}

    <table class="advertiser-table table table-bordered table-condensed">
        <thead>
        <tr>
            <th class="title"><a href="{{UrlHelper::fullUrltoogleSort('full_name')}}">{{Lang::get('content.advertiser_name')}}</a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('email')}}">{{Lang::get('content.advertiser_email')}}</a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('seller_name')}}">{{Lang::get('content.publisher_seller')}}</a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('rif_ci')}}">{{Lang::get('content.publisher_id')}}</a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.user_status')}}</a></th>
<!--            <th><a href="UrlHelper::fullUrltoogleSort('created_at')">Lang::get('content.created_at')</a></th>-->
            <th class="options">-</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($advertisers as $key => $advertiser)
        <tr>
            <td>{{ $advertiser->full_name }}</td>
            <td>{{ $advertiser->email }}</td>
            <td>{{ $advertiser->publisher->seller_name }}</td>
            <td>{{ $advertiser->publisher->letter_rif_ci }}-{{ $advertiser->publisher->rif_ci }}</td>
            <td>{{ Lang::get('content.status_'. $advertiser->status) }}</td>
<!--            <td> $advertiser->created_at </td>-->
            <td>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn" href="{{URL::to('anunciante/editar/' . $advertiser->id)}}">
                    <i class="icon-pencil"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn" href="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_advertiser_delete_title') }}', '{{ Lang::get('content.modal_advertiser_delete_content') }}', '{{URL::to('anunciante/eliminar/' . $advertiser->id)}}')">
                    <i class="icon-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $advertisers->links() }}

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('select.filter-field').bind('change', function(){
            jQuery('#advertiser_list_form').submit();
        })

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
        })
    });
</script>
@stop