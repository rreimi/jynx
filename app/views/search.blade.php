@extends('layout_home')

@section('sidebar')
@include('include.filter_sidebar')
@parent
@stop

@section('content')
    <h1>{{Lang::get('content.search_results')}}:
    @if (isset($q))
        {{ $q }}
    @elseif (isset($activeFilters['seller']))
        {{-- When comming from directory to show publications of the seller, show the seler name --}}
        {{ $activeFilters['seller']->label }}
    @endif
    </h1>

    @if (count($publications) === 0)
    <h5 class="alert alert-warning">
        @if (isset($q))
            {{Lang::get('content.search_no_results', array('item' => Lang::choice('content.publication',2), 'criteria' => $q))}}
        @else
            {{-- When comming from directory to show publications of the seller, show the seler name --}}
            {{Lang::get('content.search_no_publications')}}
        @endif
    </h5>
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