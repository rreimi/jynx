<ul class="nav nav-list">
    <li class="nav-header">{{ Lang::get('content.categories_title') }}</li>
    @foreach ($categories as $cat)
    <li><a href="{{ URL::to('category/' . $cat->slug)}}">{{ $cat->name }}</a>
        @foreach ($cat->subcategories as $subcat)
        <a href="{{ URL::to('category/' . $subcat->slug)}}">&raquo; {{ $subcat->name }}</a>
        @endforeach
    </li>
    @endforeach
<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>