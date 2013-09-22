@section('sidebar')
@parent
@endsection

@section('content')
<div class="row-fluid">

    <!--
    campos de tabla administrativa: Nombre del cargo
Ubicación
Área
Creado
Desde
Hasta
Status
Estatus
-->

    @if (Auth::user()->isPublisher())
    <h1>{{Lang::get('content.my_jobs')}} <a href="{{URL::to('bolsa-trabajo/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_job')}}</a></h1>
    @else
    <h1>{{Lang::choice('content.job',2)}}</h1>
    @endif

    <table class="job-table @if (Auth::user()->isAdmin()) admin @endif table table-bordered table-condensed">
        <thead>
        <tr>
            <th nowrap><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('job_title')}}">{{Lang::get('content.job_title')}} <i class="{{UrlHelper::getSortIcon('job_title')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('location')}}">{{Lang::get('content.location')}} <i class="{{UrlHelper::getSortIcon('location')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('areas')}}">{{Lang::get('content.areas')}} <i class="{{UrlHelper::getSortIcon('areas')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('start_date')}}">{{Lang::get('content.start_date')}} <i class="{{UrlHelper::getSortIcon('start_date')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('close_date')}}">{{Lang::get('content.close_date')}} <i class="{{UrlHelper::getSortIcon('close_date')}}"></i></a></th>
            <th><a class="rowhead" href="{{UrlHelper::fullUrltoogleSort('status')}}">{{Lang::get('content.status')}} <i class="{{UrlHelper::getSortIcon('status')}}"></i></a></th>
            <th class="options"></th>
        </tr>
        </thead>
        <tbody>

        @if (count($jobs) > 0)
        @foreach ($jobs as $key => $job)
        <tr>
            <td class="row-title">{{ $job->job_title }}</td>
            <td class="row-location">{{ $job->location }})</td>
            <td class="row-created">{{ $job->areas }}</td>
            <td nowrap class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($job->start_date)) }}</td>
            <td nowrap class="row-date">{{ date(Lang::get('content.date_format_php'),strtotime($job->close_date)) }}</td>
            <td class="row-status">{{ Lang::get('content.status_publication_' . $job->status) }}</td>

            <td nowrap class="row-options">
                <a rel="tooltip" target="_blank"  title="{{Lang::get('content.view')}}" class="btn btn-mini" href="{{URL::to('bolsa-trabajo/detalle/' . $job->id)}}">
                    <i class="icon-search"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" class="btn btn-mini" href="{{URL::to('bolsa-trabajo/editar/' . $job->id)}}">
                    <i class="icon-pencil"></i>
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" class="btn btn-mini" href="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_job_delete_title') }}', '{{ Lang::get('content.modal_job_delete_content') }}', '{{URL::to('bolsa-trabajo/eliminar/' . $job->id)}}')">
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

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
<script type="text/javascript">

</script>
@stop