@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')
    <h1>{{Lang::get('content.search_results')}}: {{ $q }}</h1>

    @if (count($publications) === 0)
    <p>{{Lang::get('content.search_no_results', array('item' => Lang::choice('content.publication',2), 'criteria' => $q))}}</p>
    @endif

    @foreach ($publications as $key => $pub)
        @if ($key % 3 == 0)
        <div class="row-fluid"><!-- /begin row-fluid -->
        @endif
            <div class="span4 pub-thumb"><!--/begin pub-thumb-->
                A
            </div><!--/end pub-thumb-->
        @if (((($key+1)%3) == 0) || ($key+1 == count($publications)))
        </div><!-- /end row-fluid -->
        @endif
    @endforeach
    {{ $publications->appends(Input::except('page'))->links() }}
</div><!--/row-->
@stop