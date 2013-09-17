<div class="container">
    <div class="dynamic-footer-blocks">
        <div class="row-fluid products-footer footer-block">
            <a href="#">
                <div class="footer-title">
                    {{Lang::get('content.product_title')}}
                </div>
            </a>
            <ul>
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
        </div>
        <div class="row-fluid services-footer footer-block">
            <a href="#">
                <div class="footer-title">
                    {{Lang::get('content.services_title')}}
                </div>
            </a>
            <ul>
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

        <div class="row-fluid contact-footer footer-block">
            <a href="/contactanos">
                <div class="footer-title">
                    {{Lang::get('content.contact')}}
                </div>
            </a>
            <div>
                <div class="contact-row address contact-text">
                    {{Lang::get('content.address_line1')}}<br/>
                    {{Lang::get('content.address_line2')}}
                </div>
                <br/>
                <div class="contact-row phone contact-text">
                    {{Lang::get('content.phones_label')}} {{Lang::get('content.phones_line1')}}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{Lang::get('content.phones_line2')}}
                </div>
                <br/>
                <div class="contact-row fax contact-text">
                    {{Lang::get('content.fax_label')}} {{Lang::get('content.fax_line1')}}
                </div>
                <br/>
                <a href="mailto:{{Lang::get('content.cavenit_email')}}">
                    <div class="contact-row mail contact-text">
                        {{Lang::get('content.cavenit_email')}}
                    </div>
                </a>
                <br/>
                <a href="https://www.facebook.com/cavenit" target="_blank">
                    <div class="contact-row facebook contact-text">
                        {{Lang::get('content.facebook')}}
                    </div>
                </a>
                <br/>
                <a href="https://twitter.com/cavenit" target="_blank">
                    <div class="contact-row twitter contact-text">
                        {{Lang::get('content.twitter')}}
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="footer-block footer-static-blocks">
        <a href="#">
            <div class="footer-title footer-block us-footer">
                {{Lang::get('content.about_us')}}
            </div>
        </a>
        <a href="#">
            <div class="footer-title footer-block help-footer">
                {{Lang::get('content.help')}}
            </div>
        </a>
        <a href="#">
            <div class="footer-title footer-block jobs-footer">
                {{Lang::get('content.jobs')}}
            </div>
        </a>
    </div>
    <div class="footer-block logos-footer">
        <img src="/img/logos.png">
    </div>
</div>
<div class="clear-both copyright-footer">
    <div class="androb">
        {{Lang::get('content.copyright')}} <a href="http://www.androb.com" target="_blank">{{Lang::get('content.androb')}}</a>
    </div>
</div>

