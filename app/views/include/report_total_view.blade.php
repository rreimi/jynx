<h2>{{ Lang::get('content.backend_report_total_view_title') }}</h2>
<p>
    <b>{{ Lang::get('content.backend_report_id') }}:</b> {{ $report->id }} <br/>
    <b>{{ Lang::get('content.backend_report_comment') }}:</b><br/> {{ $report->comment }}
</p>

<h2>{{ Lang::get('content.backend_report_user') }}</h2>
<p>
    <b>{{ Lang::get('content.backend_report_id') }}:</b> {{ $report->user->id }}<br/>
    <b>{{ Lang::get('content.backend_report_user') }}:</b> {{ $report->user->full_name }}
</p>

<h2>{{ Lang::get('content.backend_report_publication') }}</h2>
<p>
    <b>{{ Lang::get('content.backend_report_id') }}:</b> {{ $report->publication->id }}<br/>
    <b>{{ Lang::get('content.title') }}:</b> {{ $report->publication->title }}
</p>