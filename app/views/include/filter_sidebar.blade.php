@if (isset($activeFilters) && (count($activeFilters) > 0))
<span class="nav-header">{{ Lang::get('content.filter_active') }}</span>
<ul class="nav-filter-active">
    @foreach ($activeFilters as $type => $filter)
    <li>
        <div class="label"><a class="close" href="{{ UrlHelper::fullExcept(array_merge(array($filter->type, 'page'), $sidebarExcludedParams)) }}">&times;</a>{{ Lang::get('content.filter_' . $filter->type . '_title') }}: {{$filter->label}}</div>
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
        <a href="{{ URLHelper::toWith(URLHelper::fullExcept(array_merge(array('page'), $sidebarExcludedParams)), array($filter->type => $filter->item_id)) }}">{{$filter->label}} @if (!empty($filter->total)) ({{$filter->total}}) @endif</a>
    </li>
    @endforeach
</ul>
@endforeach
@endif