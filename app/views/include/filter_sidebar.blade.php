@if (isset($activeFilters) && (count($activeFilters) > 0))
<span class="nav-header">{{ Lang::get('content.filter_active') }}</span>
<ul class="nav-filter-active">
    @foreach ($activeFilters as $type => $filter)
    <li>
        <div class="label">{{ Lang::get('content.filter_' . $filter->type . '_title') }}: {{$filter->label}}<a class="close" href="{{ UrlHelper::fullExcept(array($filter->type)) }}">&times;</a></div>
    </li>
    @endforeach
</ul>
@endif

@if (isset($availableFilters) && (count($availableFilters) > 0))
<span class="nav-header">{{ Lang::get('content.filter_available') }}</span>
@foreach ($availableFilters as $type => $filters)
<span class="nav-header nav-header-subtitle">{{ Lang::get('content.filter_' . $type . '_title') }}</span>
<ul class="nav-filter-available">
    @foreach ($filters as $filter)
    <li>
        <a href="{{ URLHelper::fullWith(array($filter->type => $filter->item_id)) }}">{{$filter->label}} ({{$filter->total}})</a>
    </li>
    @endforeach
</ul>
@endforeach
@endif