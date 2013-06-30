<ul class="nav nav-list">

    <li class="nav-header">Categories</li>

    @foreach ($categories as $cat)
    <li><a href="#">{{ $cat->name }}</a></li>
    @endforeach

<!--    <li class="active"><a href="#">Link</a></li>-->
</ul>