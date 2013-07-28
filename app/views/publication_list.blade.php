@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid">
        <h1>{{Lang::get('content.my_publications')}} <a href="{{URL::to('publicacion/crear')}}" class="btn btn-info btn-small ">{{Lang::get('content.new_publication')}}</a></h1>

        {{ Form::open(array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'pub_list_form')) }}
        <div class="row-fluid pub-list-btn-group">
            {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
            {{ Form::select('filter_status', $pub_statuses, $state['filter_status'], array('class' => 'input-medium filter-field')) }}
            <button class="btn btn-warning" type="submit">{{Lang::get('content.search')}}</button>
        </div>

        <div class="span11 pub-list-filters">

        </div>
        {{ Form::close() }}
            <table class="pub-table table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th class="small"><a href="{{UrlHelper::fullUrltoogleSort('id')}}">{{Lang::get('content.id')}}</a></th>
                        <th class="title"><a href="{{UrlHelper::fullUrltoogleSort('title')}}">{{Lang::get('content.title')}}</a></th>
                        <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('from_date')}}">{{Lang::get('content.from_date')}}</a></th>
                        <th class="date"><a href="{{UrlHelper::fullUrltoogleSort('to_date')}}">{{Lang::get('content.to_date')}}</a></th>
                        <th><a href="{{UrlHelper::fullUrltoogleSort('visits_number')}}">{{Lang::get('content.visits_number')}}</a></th>
                        <th><a href="{{UrlHelper::fullUrltoogleSort('created_at')}}">{{Lang::get('content.created_at')}}</a></th>
<!--                        <th>{{Lang::get('content.category_name')}}</th>-->
                        <th class="options">-</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publications as $key => $pub)
                    <tr>
                        <td>{{ $pub->id }}</td>
                        <td>{{ $pub->title }}</td>
                        <td>{{ $pub->from_date }}</td>
                        <td>{{ $pub->to_date }}</td>
                        <td>{{ $pub->visits_number }}</td>
                        <td>{{ $pub->created_at }}</td>
<!--                        <td>{{ $pub->categories_name }}</td>-->
                        <td>
                            <a class="btn" href="{{URL::to('publicacion/detalle/' . $pub->id)}}"><i rel="tooltip" title="{{Lang::get('content.view')}}" class="icon-search"></i></a>
                            <a class="btn" href="{{URL::to('publicacion/editar/' . $pub->id)}}"><i rel="tooltip" title="{{Lang::get('content.edit')}}" class="icon-pencil"></i></a>
                            <a class="btn" href="javascript:Mercatino.modalConfirm.show('{{ Lang::get('content.modal_publication_delete_title') }}', '{{ Lang::get('content.modal_publication_delete_content') }}', '{{URL::to('publicacion/eliminar/' . $pub->id)}}')"><i rel="tooltip" title="{{Lang::get('content.delete')}}" class="icon-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
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
        })

        jQuery('.reset-fields').bind('click', function(){
            jQuery('.filter-field').val('');
        })
    });
</script>
@stop