{{ Form::open(array('method' => 'get', 'action' => 'HomeController@getSearch', 'class' => 'form-inline')) }}
<div class="input-block-level">
    {{ Form::text('q', '', array('placeholder' => Lang::get('content.publications_search_placeholder'), 'class' => '')) }}
    <button class="btn btn-small" type="submit">Search</button>
</div>
{{ Form::close() }}
<ul class="nav nav-list">
    <li class="nav-header">OPTIONS</li>
    <li><a href="#">NAME</a>

        <a href="#">SUB</a>

    </li>
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>
