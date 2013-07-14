@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <div class="row-fluid">
        <h1>{{Lang::get('content.my_publications')}} </h1>

        {{ Form::open(array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'pub_list_form')) }}
        <div class="span11 pub-list-btn-group">

            {{Form::text('q', $state['q'], array('class' => 'input-medium filter-field', 'placeholder' => Lang::get('content.publications_search_placeholder')))}}
            <button class="btn btn-warning" type="submit">{{Lang::get('content.search')}}</button>

            <a href="{{URL::to('publicacion/crear')}}" class="btn btn-info  pull-right">{{Lang::get('content.new_publication')}}</a>

        </div>

        <div class="span11 pub-list-filters">
            {{ Form::select('filter_status', $pub_statuses, $state['filter_status'], array('class' => 'input-medium filter-field')) }}
        </div>
        {{ Form::close() }}
            <table class="pub-table table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th><a href="?sort=id&order=asc">{{Lang::get('content.id')}}</a></th>
                        <th>{{Lang::get('content.title')}}</th>
                        <th>{{Lang::get('content.from_date')}}</th>
                        <th>{{Lang::get('content.to_date')}}</th>
                        <th>{{Lang::get('content.visits_number')}}</th>
                        <th>{{Lang::get('content.created_at')}}</th>
                        <th>{{Lang::get('content.categories_name')}}</th>
                        <th>-</th>
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
                        <td>{{ $pub->categories_name }}</td>
                        <td>
                            <a href="{{URL::to('publicacion/editar/' . $pub->id)}}">{{Lang::get('content.edit')}}</a> |
                            <a href="{{URL::to('publicacion/imagenes/' . $pub->id)}}">{{Lang::get('content.edit_images')}}</a>

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