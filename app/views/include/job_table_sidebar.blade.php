<div class="row-fluid search-sidebar-box">
    {{ Form::open(array('method' => 'post', 'class' => 'form-inline sidebar-search-form', 'id' => 'job_list_form')) }}
    <div class="span11 pub-list-filters">
        <span class="nav-header">{{ Lang::get('content.search_job_title') }}</span>

        <hr/>
    </div>
    {{ Form::close() }}
</div>