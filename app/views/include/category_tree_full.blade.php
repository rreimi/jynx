<div class="main-menu">
<ul class="nav-category">
    @foreach ($categories as $cat)
    <li class="@if (isset($category) && ($cat->id == $category->id)) active @endif">
        @if (isset($category) && ($cat->id == $category->id))
        <a class="active" nohref><b>{{ $cat->name }}</b></a>
        @else
        <a href="{{ URL::to('cat/' . $cat->slug)}}">{{ $cat->name }}</a>
        @endif

        @if (count($cat->subcategories) > 0)
        <i data-toggle="collapse" data-target="#cats_for_{{$cat->id}}" class="icon-chevron {{ (isset($category) && ($cat->id == $category->id))? 'icon-chevron-right':'icon-chevron-down' }}"></i>
        <ul id="cats_for_{{$cat->id}}" class="level2 {{ (isset($category_tree) && (in_array($cat->id, $category_tree)))? 'collapse in':'collapse' }}">
            @foreach ($cat->subcategories as $subcat)
            <li>
                @if (isset($category) && ($subcat->id == $category->id))
                <a class="active" nohref><b>{{ $subcat->name }}</b></a>
                @else
                <a href="{{ URL::to('cat/' . $subcat->slug)}}">{{ $subcat->name }}</a>
                @endif
                @if (count($subcat->subcategories) > 0)
                <i data-toggle="collapse" data-target="#cats_for_{{$subcat->id}}" class="icon-chevron {{ (isset($category) && ($subcat->id == $category->id))? 'icon-chevron-right':'icon-chevron-down' }}"></i>
                <ul id="cats_for_{{$subcat->id}}" class="level3 {{ (isset($category_tree) && (in_array($subcat->id, $category_tree)))? 'collapse in':'collapse' }}">
                    @foreach ($subcat->subcategories as $thirdcat)
                    <li>
                        @if (isset($category) && ($thirdcat->id == $category->id))
                        <a class="active" nohref><b>{{ $thirdcat->name }}</b></a>
                        @else
                        <a href="{{ URL::to('cat/' . $thirdcat->slug)}}">{{ $thirdcat->name }}</a>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>
<ul class="nav-category nav-service">
    @foreach ($services as $cat)
    <li class="@if (isset($category) && ($cat->id == $category->id)) active @endif">
        @if (isset($category) && ($cat->id == $category->id))
        <a class="active" nohref><b>{{ $cat->name }}</b></a>
        @else
        <a href="{{ URL::to('cat/' . $cat->slug)}}">{{ $cat->name }}</a>
        @endif

        @if (count($cat->subcategories) > 0)
        <i data-toggle="collapse" data-target="#cats_for_{{$cat->id}}" class="icon-chevron {{ (isset($category) && ($cat->id == $category->id))? 'icon-chevron-right':'icon-chevron-down' }}"></i>
        <ul id="cats_for_{{$cat->id}}" class="level2 {{ (isset($category_tree) && (in_array($cat->id, $category_tree)))? 'collapse in':'collapse' }}">
            @foreach ($cat->subcategories as $subcat)
            <li>
                @if (isset($category) && ($subcat->id == $category->id))
                <a class="active" nohref><b>{{ $subcat->name }}</b></a>
                @else
                <a href="{{ URL::to('cat/' . $subcat->slug)}}">{{ $subcat->name }}</a>
                @endif
                @if (count($subcat->subcategories) > 0)
                <i data-toggle="collapse" data-target="#cats_for_{{$subcat->id}}" class="icon-chevron {{ (isset($category) && ($subcat->id == $category->id))? 'icon-chevron-right':'icon-chevron-down' }}"></i>
                <ul id="cats_for_{{$subcat->id}}" class="level3 {{ (isset($category_tree) && (in_array($subcat->id, $category_tree)))? 'collapse in':'collapse' }}">
                    @foreach ($subcat->subcategories as $thirdcat)
                    <li>
                        @if (isset($category) && ($thirdcat->id == $category->id))
                        <a class="active" nohref><b>{{ $thirdcat->name }}</b></a>
                        @else
                        <a href="{{ URL::to('cat/' . $thirdcat->slug)}}">{{ $thirdcat->name }}</a>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>
</div>