@extends('layout_home')

@section('sidebar')
@parent
@stop

@section('content')

    <div class="row-fluid">
        {{ Form::open(array('url' => 'bolsa-trabajo/guardar', 'method' => 'post', 'class' => 'form-horizontal')) }}
        @if (!isset($job->id))
        <h1>{{Lang::get('content.new_job')}}</h1>
        @else
        <h1>{{Lang::get('content.edit_job')}}: {{ $job->title }}</h1>
        @endif
        <div class="control-group {{ $errors->has('company_name') ? 'error':'' }}">
            <label class="control-label required-field" for="company_name">{{ Lang::get('content.company_name') }}</label>
            <div class="controls">
                {{ Form::text('company_name', $companyName, array('id'=>'company_name','class' => 'input-xlarge required ','placeholder'=> Lang::get('content.company_name'))) }}
                {{ $errors->first('company_name', '<div class="field-error alert alert-error">:message</div>') }}

            </div>
        </div>

        @if(!empty($avatar))
        <div class="control-group">
            <div class="controls logo-job img-polaroid">
                <img src="{{ URL::to($avatar) }}"/>
            </div>
        </div>
        @endif

        <div class="control-group {{ $errors->has('state_id') ? 'error':'' }} ">
            <label class="control-label required-field" for="state_id">{{ Lang::get('content.state') }}</label>
            <div class="controls">
                {{ Form::select('state_id', $states, $job->state_id, array('id'=>'state_id','class' => 'required')) }}
                {{ $errors->first('state_id', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('city') ? 'error':'' }}">
            <label class="control-label" for="city">{{ Lang::get('content.city') }}</label>
            <div class="controls">
                {{ Form::text('city', $job->city, array('id'=>'city','class' => 'input-xlarge','placeholder'=> Lang::get('content.city'))) }}
                {{ $errors->first('city', '<div class="field-error alert alert-error">:message</div>') }}

            </div>
        </div>


        <div class="control-group {{ $errors->has('job_title') ? 'error':'' }}">
            <label class="control-label required-field" for="job_title">{{ Lang::get('content.job_title') }}</label>
            <div class="controls">
                {{ Form::text('job_title', $job->job_title, array('id'=>'job_title','class' => 'input-xlarge required ','placeholder'=> Lang::get('content.job_title'))) }}
                {{ $errors->first('job_title', '<div class="field-error alert alert-error">:message</div>') }}

            </div>
        </div>

        <div class="control-group {{ $errors->has('vacancy') ? 'error':'' }}">
            <label class="control-label" for="vacancy">{{ Lang::get('content.vacancy') }}</label>
            <div class="controls">
                {{ Form::select('vacancy', $vacancies, $job->vacancy, array('id'=>'vacancy','class' => '')) }}
                {{ $errors->first('vacancy', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('job_type') ? 'error':'' }}">
            <label class="control-label" for="job_type">{{ Lang::get('content.job_type') }}</label>
            <div class="controls">
                {{ Form::select('job_type', $jobTypes, $job->job_type, array('id'=>'job_type','class' => '')) }}
                {{ $errors->first('job_type', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('temporary_months') ? 'error':'' }}">
            <label class="control-label" for="temporary_months">{{ Lang::get('content.temporary_months') }}</label>
            <div class="controls">
                {{ Form::text('temporary_months', $job->temporary_months, array('id'=>'temporary_months','class' => 'input-small ','placeholder'=> Lang::get('content.temporary_months'))) }}
                {{ $errors->first('temporary_months', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('area_ids') ? 'error':'' }}">
            <label class="control-label required-field" for="area_ids">{{ Lang::get('content.areas') }}</label>
            <div class="controls">
                {{ Form::select('area_ids[]', $areas, $job->area_ids, array('id'=>'area_ids','multiple' => '', 'class' => 'chosen-select input filter-field required', 'data-placeholder' => Lang::get('content.areas'))) }}
                {{ $errors->first('area_ids', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('description') ? 'error':'' }}">
            <label class="control-label required-field" for="description">{{ Lang::get('content.description') }}</label>
            <div class="controls">
                {{ Form::textarea('description', $job->description, array('id'=>'description','class' => 'input-xxlarge required', 'placeholder'=> Lang::get('content.description'))) }}
                {{ $errors->first('description', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('requirements') ? 'error':'' }}">
            <label class="control-label" for="requirements">{{ Lang::get('content.requirements') }}</label>
            <div class="controls">
                {{ Form::textarea('requirements', $job->requirements, array('id'=>'requirements','class' => 'input-xxlarge', 'placeholder'=> Lang::get('content.requirements'))) }}
                {{ $errors->first('requirements', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('academic_level') ? 'error':'' }}">
            <label class="control-label" for="academic_level">{{ Lang::get('content.academic_level') }}</label>
            <div class="controls">
                {{ Form::select('academic_level', $academicLevels, $job->academic_level, array('id'=>'academic_level','class' => 'input-xlarge')) }}
                {{ $errors->first('academic_level', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('career_ids') ? 'error':'' }}">
            <label class="control-label" for="career_ids">{{ Lang::get('content.careers') }}</label>
            <div class="controls">
                {{ Form::select('career_ids[]', $careers, $job->career_ids, array('id'=>'career_ids','multiple' => '', 'class' => 'chosen-select input filter-field', 'data-placeholder' => Lang::get('content.careers'))) }}
                {{ $errors->first('career_ids', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('experience_years') ? 'error':'' }}">
            <label class="control-label" for="experience_years">{{ Lang::get('content.experience_years') }}</label>
            <div class="controls">
                {{ Form::text('experience_years', $job->experience_years, array('id'=>'experience_years','class' => 'input-xlarge ','placeholder'=> Lang::get('content.experience_years'))) }}
                {{ $errors->first('experience_years', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('age') ? 'error':'' }}">
            <label class="control-label" for="age">{{ Lang::get('content.age') }}</label>
            <div class="controls">
                {{ Form::text('age', $job->age, array('id'=>'age','class' => 'input-small ','placeholder'=> Lang::get('content.age'))) }}
                {{ $errors->first('age', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('sex') ? 'error':'' }}">
            <label class="control-label" for="sex">{{ Lang::get('content.sex') }}</label>
            <div class="controls">
                {{ Form::select('sex', $sexes, $job->sex, array('id'=>'sex','class' => '')) }}
                {{ $errors->first('sex', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('languages') ? 'error':'' }}">
            <label class="control-label" for="languages">{{ Lang::get('content.languages') }}</label>
            <div class="controls">
                {{ Form::text('languages', $job->languages, array('id'=>'languages','class' => ' ','placeholder'=> Lang::get('content.languages'))) }}
                {{ $errors->first('languages', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('salary') ? 'error':'' }}">
            <label class="control-label" for="salary">{{ Lang::get('content.salary') }}</label>
            <div class="controls">
                {{ Form::text('salary', $job->salary, array('id'=>'salary','class' => ' ','placeholder'=> Lang::get('content.salary'))) }}
                {{ $errors->first('salary', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('benefits') ? 'error':'' }}">
            <label class="control-label" for="benefits">{{ Lang::get('content.benefits') }}</label>
            <div class="controls">
                {{ Form::text('benefits', $job->benefits, array('id'=>'benefits','class' => 'input-xlarge ','placeholder'=> Lang::get('content.benefits'))) }}
                {{ $errors->first('benefits', '<div class="field-error alert alert-error">:message</div>') }}

            </div>
        </div>

        <div class="control-group {{ $errors->has('contact_email') ? 'error':'' }}">
            <label class="control-label required-field" for="contact_email">{{ Lang::get('content.contact_email') }}</label>
            <div class="controls">
                {{ Form::email('contact_email', $job->contact_email, array('id'=>'contact_email','class' => 'input-xlarge required ','placeholder'=> Lang::get('content.contact_email'))) }}
                {{ $errors->first('contact_email', '<div class="field-error alert alert-error">:message</div>') }}

            </div>
        </div>


        <div class="control-group {{ $errors->has('start_date') ? 'error':'' }}">
            <label class="control-label" for="title">{{ Lang::get('content.start_date') }}</label>
            <div class="controls">
                {{ Form::text('start_date', $job->start_date!=null?date(Lang::get('content.date_format_php'),strtotime($job->start_date)):'', array('class' => 'datepicker from-date ','placeholder'=> Lang::get('content.start_date'))) }}
                {{ $errors->first('start_date', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>


        <div class="control-group {{ $errors->has('close_date') ? 'error':'' }}">
            <label class="control-label" for="title">{{ Lang::get('content.close_date') }}</label>
            <div class="controls">
                {{ Form::text('close_date', $job->close_date!=null?date(Lang::get('content.date_format_php'),strtotime($job->close_date)):'', array('class' => 'datepicker to-date ','placeholder'=> Lang::get('content.close_date'))) }}
                {{ $errors->first('close_date', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group {{ $errors->has('status') ? 'error':'' }}">
            <label class="control-label required-field" for="status">{{ Lang::get('content.status') }}</label>
            <div class="controls">
                {{ Form::select('status', $statuses, $job->status, array('id'=>'status','class' => 'required')) }}
                {{ $errors->first('status', '<div class="field-error alert alert-error">:message</div>') }}
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <a href="{{ $referer }}" class="btn btn-medium">{{Lang::get('content.cancel')}}</a>
                <button class="btn btn-medium btn-success" type="submit">{{Lang::get('content.save')}}</button>

            </div>
        </div>
        {{ Form::hidden('id', $job->id) }}
        {{ Form::close() }}
    </div>
@stop

@section('scripts')
@parent
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.chosen-select').chosen({
            width: "100%"
        });

        jQuery('.datepicker').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true
        });

        var fromDate=jQuery('.datepicker.from-date');
        var toDate=jQuery('.datepicker.to-date');

        fromDate.on("change", function(){
            toDate.datepicker('option','minDate',jQuery(this).val());
        });
        toDate.bind("change", function(){
            fromDate.datepicker('option','maxDate',jQuery(this).val());
        });
    });
</script>
@stop