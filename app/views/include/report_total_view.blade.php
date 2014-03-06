<br/>
<p>
    <b>{{ Lang::get('content.backend_report_user') }}:</b> {{ $report->user->full_name }}
</p>
<p>
    <b>{{ Lang::get('content.backend_report_date_created') }}:</b> {{ $report->date }}
</p>
<p>
    <b>{{ Lang::get('content.backend_report_date_resolved') }}:</b> {{ $report->final_status }}
</p>
<p>
    <b>{{ Lang::get('content.backend_report_publication') }}:</b> {{ $report->publication->title }}
</p>
<p>
    <b>{{ Lang::get('content.backend_report_publisher') }}:</b> {{ $report->publication->publisher->seller_name }}
</p>
<p>
    <b>{{ Lang::get('content.backend_report_comments_in_publication') }}:</b> {{ $report->reports_in_publication }}
</p>
<p>
    <b>{{ Lang::get('content.backend_report_status') }}:</b> {{ Lang::get('content.status_report_'. $report->status) }}
</p>