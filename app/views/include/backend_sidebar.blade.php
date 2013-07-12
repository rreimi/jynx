{{ Form::open(array('method' => 'get', 'action' => 'BackendController@getSearch', 'class' => '')) }}
<div class="input-append">
    {{ Form::text('q', null, array('placeholder' => 'Busca algo ', 'class' => 'span12')) }}
    <button class="btn"><i class="icon-search"></i></button>
</div>

{{ Form::close() }}
<ul class="nav nav-list">
    <li class="nav-header">OPTIONS</li>
    <li><a href="#">NAME</a>

        <a href="#">SUB</a>

    </li>
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
