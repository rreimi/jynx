@section('sidebar')
@parent
@endsection

@section('content')
<div class="row-fluid">
    @if ($user->isPublisher())
    <h1>{{Lang::get('content.my_publications')}} <a href="{{URL::to('publicacion/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_publication')}}</a></h1>
    @else
    <h1>{{Lang::choice('content.publication',2)}}</h1>
    @endif

    <table class="pub-table @if ($user->isAdmin()) admin @endif table table-bordered table-condensed">
        <thead>
        <tr>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('title')}}">{{Lang::get('content.title')}} <i class="{{UrlHelper::getSortIcon('title')}}"></i></a></th>
<!--            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('created_at')}}">{{Lang::get('content.created_at')}} <i class="{{UrlHelper::getSortIcon('created_at')}}"></i></a></th>-->
<!--            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('from_date')}}">{{Lang::get('content.from_date')}} <i class="{{UrlHelper::getSortIcon('from_date')}}"></i></a></th>-->
<!--            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('to_date')}}">{{Lang::get('content.to_date')}} <i class="{{UrlHelper::getSortIcon('to_date')}}"></i></a></th>-->
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.status')}} <i class="{{UrlHelper::getSortIcon('status')}}"></i></a></th>
            @if ($user->isAdmin())
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('seller_name')}}">{{Lang::get('content.seller_name')}} <i class="{{UrlHelper::getSortIcon('seller_name')}}"></i></a></th>
            @endif
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('visits_number')}}">{{Lang::get('content.visits_number')}} <i class="{{UrlHelper::getSortIcon('visits_number')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('rating_avg')}}">{{Lang::get('content.rating_avg')}} <i class="{{UrlHelper::getSortIcon('rating_avg')}}"></i></a></th>
            @if ($user->isAdmin())
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('reports')}}">{{Lang::get('content.reports')}} <i class="{{UrlHelper::getSortIcon('reports')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('ratings')}}">{{Lang::get('content.comments')}} <i class="{{UrlHelper::getSortIcon('ratings')}}"></i></a></th>
            @endif
            <!--                        <th>{{Lang::get('content.category_name')}}</th>-->
            <th class="options"></th>
        </tr>
        </thead>
        <tbody>
        @if (count($publications) > 0)
        @foreach ($publications as $key => $pub)
        <tr>
            <td class="row-title"><b>{{ e($pub->title) }}</b>
            </td>
<!--            <td nowrap class="row-created">{{ date(Lang::get('content.date_format_php'),strtotime($pub->created_at)) }}</td>-->
<!--            <td nowrap class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($pub->from_date)) }}</td>-->
<!--            <td nowrap class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($pub->to_date)) }}</td>-->
            <td class="row-status">{{ Lang::get('content.status_publication_' . $pub->status) }}</td>
            @if ($user->isAdmin())
            <td class="row-seller_name">{{ $pub->seller_name }}</td>
            @endif
            <td class="row-visits_number">{{ $pub->visits_number }}</td>
            <td class="row-rating_avg">
                @if($pub->rating_avg == null)
                    {{ Lang::get('content.no_rating_avg') }}
                @else
                    {{ $pub->rating_avg }}
                @endif
            </td>
            @if ($user->isAdmin())
            <td class="row-reports">
                @if ($pub->reports > 0)
                    <a href="{{ URL::to('denuncia/lista/publicacion/' . $pub->id) }}">{{ $pub->reports }}</a>
                @else
                    {{ $pub->reports }}
                @endif
            </td>
            <td class="row-ratings">
                {{ $pub->ratings }}
            </td>
            @endif
            <!--                        <td>{{ $pub->categories_name }}</td>-->
            <td nowrap class="row-options">
                <a rel="tooltip" title="{{Lang::get('content.view')}}" class="btn btn-mini" href="{{URL::to('publicacion/detalle/' . $pub->id)}}">
                    <i class="icon-search"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn btn-mini" href="{{URL::to('publicacion/editar/' . $pub->id)}}">
                    <i class="icon-pencil"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn btn-mini" nohref onclick="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_publication_delete_title') }}', '{{ Lang::get('content.modal_publication_delete_content') }}', '{{URL::to('publicacion/eliminar/' . $pub->id)}}')">
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
    {{ $publications->appends(Input::only('sort','order'))->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.chosen-select').chosen({
            width: "100%"
        });

        jQuery('.search-sidebar-box').fadeIn();

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
            jQuery('.chosen-select').val('').trigger("chosen:updated");
            jQuery('#pub_list_form').submit();
        });

        /* Filter fields */

        /* All date filters */
        jQuery('.datepicker').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true
        });

        /* Set dynamic date range */
        jQuery('.datepicker.from-start-date').bind("change", function(){
            jQuery('.datepicker.to-start-date').datepicker( "option", "minDate", jQuery(this).val());
        });
        jQuery('.datepicker.to-start-date').bind("change", function(){
            jQuery('.datepicker.from-start-date').datepicker( "option", "maxDate", jQuery(this).val());
        });

        /* Set dynamic date range */
        jQuery('.datepicker.from-end-date').bind("change", function(){
            jQuery('.datepicker.to-end-date').datepicker( "option", "minDate", jQuery(this).val());
        });
        jQuery('.datepicker.to-end-date').bind("change", function(){
            jQuery('.datepicker.from-end-date').datepicker( "option", "maxDate", jQuery(this).val());
        });

    });
</script>
@stop