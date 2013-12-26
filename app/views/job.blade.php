@extends('layout_home')

@section('sidebar')
@parent
<div class="row-fluid">
    <div class="logo-job img-polaroid pull-right">
        @if(!Auth::user())
        {{ HTML::image('img/logo_bolsa_trabajo.png')}}
        @else
            @if(!is_null($companyPicture))
                {{ HTML::image(UrlHelper::imageUrl($companyPicture, '_' . $thumbSize['width']))}}
            @else
                {{ HTML::image('img/logo_bolsa_trabajo.png')}}
            @endif
        @endif
    </div>
</div>
@stop

@section('sub-title')
<div class="job-media">
    @include('include.add_this')
</div>
<h1>{{ Lang::get('content.jobs') }} <a href="{{ $referer }}" class="btn btn-small">{{Lang::get('content.previous')}}</a></h1>
@stop

@section('content')
    <div class="row-fluid job-detail">
        @if(!Auth::user())
            <div class="job-guest">{{ Lang::get('content.job_guest', array('loginUrl' => URL::to('login'))) }}</div>
        @endif
        <div class="field">
            <span class="title-job">{{ Lang::get('content.job_title') }}: </span>
            <span class="description-job">{{ $job->job_title }}</span>
        </div>
        <div class="field">
            <span class="title-job">{{ Lang::get('content.description') }}: </span>
            <span class="description-job">{{ $job->description }}</span>
        </div>
        @if(Auth::user())
            <div class="field">
                <span class="title-job">{{ Lang::get('content.company_name') }}: </span>
                <span class="description-job">{{ $job->company_name }}</span>
            </div>
        @endif
        <div class="field">
            <span class="title-job">{{ Lang::get('content.job_location') }}: </span>
            <span class="description-job">{{ $job->location }}</span>
        </div>
        @if($job->vacancy)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.vacancy') }}: </span>
                <span class="description-job">{{ $job->vacancy }}</span>
            </div>
        @endif
        @if($job->job_type)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.job_type') }}: </span>
                <span class="description-job">{{ Lang::get('content.job_type_'.strtolower($job->job_type)) }}</span>
            </div>
        @endif
        @if($job->temporary_months)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.temporary_months') }}: </span>
                <span class="description-job">{{ DateHelper::getMonth($job->temporary_months) }}</span>
            </div>
        @endif
            <div class="field">
                <span class="title-job">{{ Lang::get('content.area_sector') }}: </span>
                <span class="description-job">{{ $job->areas }}</span>
            </div>
        @if($job->requirements)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.requirements') }}: </span>
                <span class="description-job">{{ $job->requirements }}</span>
            </div>
        @endif
        @if($job->academic_level)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.academic_level') }}: </span>
                <span class="description-job">{{ Lang::get('content.job_academic_level_'.strtolower($job->academic_level)) }}</span>
            </div>
        @endif
        @if($job->careers)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.careers') }}: </span>
                <span class="description-job">{{ $job->careers }}</span>
            </div>
        @endif
        @if($job->experience_years)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.experience_years') }}: </span>
                <span class="description-job">{{ DateHelper::getExperienceYear($job->experience_years) }}</span>
            </div>
        @endif
        @if($job->age)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.age') }}: </span>
                <span class="description-job">{{ DateHelper::getYear($job->age) }}</span>
            </div>
        @endif
        @if($job->sex)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.sex') }}: </span>
                <span class="description-job">{{ Lang::get('content.'.strtolower($job->sex)) }}</span>
            </div>
        @endif
        @if($job->languages)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.languages') }}: </span>
                <span class="description-job">{{ $job->languages }}</span>
            </div>
        @endif
        @if($job->salary)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.salary') }}: </span>
                <span class="description-job">{{ $job->salary }}</span>
            </div>
        @endif
        @if($job->benefits)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.benefits') }}: </span>
                <span class="description-job">{{ $job->benefits }}</span>
            </div>
        @endif
        @if(Auth::user())
            @if($job->contact_email)
                <div class="field">
                    <span class="title-job">{{ Lang::get('content.contact_email_detail') }}: </span>
                    <span class="description-job"><a href="mailto:{{ $job->contact_email }}"> {{ $job->contact_email }} </a></span>
                </div>
            @endif
        @endif
        @if($job->start_date)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.start_date') }}: </span>
                <span class="description-job">{{ date(Lang::get('content.date_format_php'),strtotime($job->start_date)) }}</span>
            </div>
        @endif
        @if($job->close_date)
            <div class="field">
                <span class="title-job">{{ Lang::get('content.close_date') }}: </span>
                <span class="description-job">{{ date(Lang::get('content.date_format_php'),strtotime($job->close_date)) }}</span>
            </div>
        @endif


    </div>
@stop

@section('scripts')
@parent
<script type="text/javascript">

</script>
@stop