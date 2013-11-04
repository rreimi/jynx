@extends('layout_home')

@section('sidebar')
@include('include.filter_sidebar')
@parent
@stop

@section('content')
    <h1>{{Lang::get('content.search_results')}}: {{ $q }}</h1>

    @if (count($publications) === 0)
    <h5 class="alert alert-warning">{{Lang::get('content.search_no_results', array('item' => Lang::choice('content.publication',2), 'criteria' => $q))}}</h5>
    @endif

    @foreach ($publications as $key => $pub)
        @if ($key % 3 == 0)
        <div class="row-fluid">
        @endif
            <div class="span4 pub-thumb">
                @include('include.publication_box')
            </div>
        @if (((($key+1)%3) == 0) || ($key+1 == count($publications)))
        </div>
        @endif
    @endforeach
    {{ $publications->appends(Input::except('page'))->links() }}
</div><!--/row-->
@stop