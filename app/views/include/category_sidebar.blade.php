@if(!empty($custom_title))
    <span class="nav-header">{{ $custom_title }}</span>
@endif
@if(!empty($custom_options) && count($custom_options))
    <ul class="nav nav-tabs nav-stacked side-nav">

        @foreach ($custom_options as $custom_name => $custom_anchor)
        <li>
            <a href="{{ $custom_anchor }}">{{ $custom_name }}</a>
        </li>
        @endforeach
    </ul>
@endif

