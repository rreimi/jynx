@section('content')
<div class="row-fluid">
    @if ($user->isPublisher())
    <h1>{{Lang::get('content.my_publications')}} <a href="{{URL::to('publicacion/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_publication')}}</a></h1>
    @else
    <h1>{{Lang::choice('content.publication',2)}}</h1>
    @endif
    {{ Form::open(array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'pub_list_form')) }}
    <div class="row-fluid pub-list-btn-group">
        {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
        {{ Form::select('filter_status', $pub_statuses, $state['filter_status'], array('class' => 'input-medium filter-field')) }}
        <button class="btn btn-warning" type="submit">{{Lang::get('content.search')}}</button>
    </div>

    <div class="span11 pub-list-filters">
        Filtro de Categoria <br/>
        Filtro de Anunciante <br/>
        Filtro de fecha de inicio <br/>
        Filtro de fecha fin <br/>
    </div>
    {{ Form::close() }}
    <table class="pub-table @if ($user->isAdmin()) admin @endif table table-bordered table-condensed">
        <thead>
        <tr>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('title')}}">{{Lang::get('content.title')}} <i class="{{UrlHelper::getSortIcon('title')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('created_at')}}">{{Lang::get('content.created_at')}} <i class="{{UrlHelper::getSortIcon('created_at')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('from_date')}}">{{Lang::get('content.from_date')}} <i class="{{UrlHelper::getSortIcon('from_date')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('to_date')}}">{{Lang::get('content.to_date')}} <i class="{{UrlHelper::getSortIcon('to_date')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.status')}} <i class="{{UrlHelper::getSortIcon('status')}}"></i></a></th>
            @if ($user->isAdmin())
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('seller_name')}}">{{Lang::get('content.seller_name')}} <i class="{{UrlHelper::getSortIcon('seller_name')}}"></i></a></th>
            @endif
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('visits_number')}}">{{Lang::get('content.visits_number')}} <i class="{{UrlHelper::getSortIcon('visits_number')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('rating_avg')}}">{{Lang::get('content.rating_avg')}} <i class="{{UrlHelper::getSortIcon('rating_avg')}}"></i></a></th>
            <!--                        <th>{{Lang::get('content.category_name')}}</th>-->
            <th class="options"></th>
        </tr>
        </thead>
        <tbody>
        @if (count($publications) > 0)
        @foreach ($publications as $key => $pub)
        <tr>
            <td class="row-title"><b>{{ $pub->title }}</b>
                <span class="title-with-categories">{{ $pub->categories }}</span>
            </td>
            <td class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($pub->created_at)) }}</td>
            <td class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($pub->from_date)) }}</td>
            <td class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($pub->to_date)) }}</td>
            <td class="row-status">{{ Lang::get('content.status_publication_' . $pub->status) }}</td>
            @if ($user->isAdmin())
            <td class="row-seller_name">{{ $pub->seller_name }}</td>
            @endif
            <td class="row-visits_number">{{ $pub->visits_number }}</td>
            <td class="row-rating_avg">{{ $pub->rating_avg }}</td>
            <!--                        <td>{{ $pub->categories_name }}</td>-->
            <td class="row-options">
                <a rel="tooltip" target="_blank"  title="{{Lang::get('content.view')}}" class="btn" href="{{URL::to('publicacion/detalle/' . $pub->id)}}">
                    <i class="icon-search"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn" href="{{URL::to('publicacion/editar/' . $pub->id)}}">
                    <i class="icon-pencil"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn" href="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_publication_delete_title') }}', '{{ Lang::get('content.modal_publication_delete_content') }}', '{{URL::to('publicacion/eliminar/' . $pub->id)}}')">
                    <i class="icon-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="8"><div class="text-center">{{Lang::get('content.no_elements_to_list')}}</div></td>
        </tr>
        @endif
        </tbody>
    </table>
    {{ $publications->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('select.filter-field').bind('change', function(){
//            jQuery(this).bind('change', function(){
//                alert('hola');
            jQuery('#pub_list_form').submit();
//            });
        });

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
        });
    });
</script>
@stop