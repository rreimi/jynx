@extends('layout_backend')

@section('sidebar')
<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'advertiser_list_form')) }}
    <div class="span11 user-list-filters">
        <span class="nav-header">{{ Lang::get('content.backend_search_advertiser_title') }}</span>
        <div class="control-group">
            <div class="controls">
                {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.advertiser_search_placeholder')))}}
                <button class="btn btn-warning btn-small" type="submit">{{Lang::get('content.search')}}</button>
            </div>
        </div>

        <div id="search-options-box" class="more-search-options collapse in">
            <div class="control-group">
                <label class="control-label text-left" for="filter_status">{{ Lang::get('content.filter_publication_status') }}</label>
                <div class="controls">
                    {{ Form::select('filter_status', $advertiser_statuses, $state['filter_status'], array('id' => 'filter_status', 'class' => 'filter-field')) }}
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
    <h1>{{Lang::get('content.advertisers')}} <a href="{{URL::to('anunciante/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_advertiser')}}</a></h1>

    <table class="advertiser-table table table-bordered table-condensed">
        <thead>
        <tr>
            <th class="title"><a href="{{UrlHelper::fullUrltoogleSort('full_name')}}">{{Lang::get('content.advertiser_name')}} <i class="{{UrlHelper::getSortIcon('full_name')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('email')}}">{{Lang::get('content.advertiser_email')}} <i class="{{UrlHelper::getSortIcon('email')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('seller_name')}}">{{Lang::get('content.publisher_seller')}} <i class="{{UrlHelper::getSortIcon('seller_name')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('rif_ci')}}">{{Lang::get('content.backend_id')}} <i class="{{UrlHelper::getSortIcon('rif_ci')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('state')}}">{{Lang::get('content.publisher_state')}} <i class="{{UrlHelper::getSortIcon('state')}}"></i></a></th>
            <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('reports')}}">{{Lang::get('content.reports')}} <i class="{{UrlHelper::getSortIcon('reports')}}"></i></a></th>
<!--            <th><a href="UrlHelper::fullUrltoogleSort('created_at')">Lang::get('content.created_at')</a></th>-->
            <th class="options">-</th>
        </tr>
        </thead>
        <tbody>
        @if (count($advertisers) > 0)
        @foreach ($advertisers as $key => $advertiser)
        <tr>
            <td>{{ $advertiser->full_name }}</td>
            <td>{{ $advertiser->email }}</td>
            <td>{{ $advertiser->seller_name }}</td>
            <td>{{ $advertiser->letter_rif_ci }}-{{ $advertiser->rif_ci }}</td>
            <td>{{ Lang::get('content.status_'. $advertiser->status) }}</td>
            <td>{{ $advertiser->publisher_reports }}</td>
<!--            <td> $advertiser->created_at </td>-->
            <td>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn" href="{{URL::to('anunciante/editar/' . $advertiser->publisher_id)}}">
                    <i class="icon-pencil"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn" nohref onclick="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_advertiser_delete_title') }}', '{{ Lang::get('content.modal_advertiser_delete_content') }}', '{{URL::to('anunciante/eliminar/' . $advertiser->publisher_id)}}')">
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
    {{ $advertisers->appends(Input::only('sort','order'))->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('#advertiser_list_form').submit();
        });
    });
</script>
@stop