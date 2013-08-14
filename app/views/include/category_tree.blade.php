<div class="main-menu">
<ul class="nav-category-menu">
    @foreach ($categories as $cat)
    <li class="@if (isset($category) && ($cat->id == $category->id)) active @endif">
        @if (isset($category) && ($cat->id == $category->id))
        <a class="active" nohref><b>{{ $cat->name }}</b></a>
        @else
        <a href="{{ URL::to('cat/' . $cat->slug)}}"><i class="icon-chevron-right"></i>{{ $cat->name }}</a>
        @endif
    </li>
    @endforeach
</ul>
<ul class="nav-category-menu nav-service-menu">
    @foreach ($services as $cat)
    <li class="@if (isset($category) && ($cat->id == $category->id)) active @endif">
        @if (isset($category) && ($cat->id == $category->id))
        <a class="active" nohref><b>{{ $cat->name }}</b></a>
        @else
        <a href="{{ URL::to('cat/' . $cat->slug)}}"><i class="icon-chevron-right"></i>{{ $cat->name }}</a>
        @endif
    </li>
    @endforeach
</ul>
</div>