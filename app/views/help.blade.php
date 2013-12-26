@extends('layout_home_no_sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    <h1 class="about-us-title">{{Lang::get('content.help')}}</h1>

    <div class="help">
        <p>Próximamente...</p>
    </div>

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
@stop