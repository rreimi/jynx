@include('include.modal_terms_and_conditions')

<!-- New footer -->
<div class="container footer-links-container">
    <div class="row">
        <div class="span4">
            <ul>
                <li class="footer-list-title">
                    <a href="{{URL::to('acerca-de')}}">
                        {{Lang::get('content.about_us')}}
                    </a>
                </li>
                <li class="footer-list-title">
                    <a href="{{URL::to('directorio')}}">
                        {{Lang::get('content.directory')}}
                    </a>
                </li>
                <li class="footer-list-title">
                    <a href="{{URL::to('bolsa-trabajo')}}">
                        {{Lang::get('content.jobs')}}
                    </a>
                </li>
                <li class="footer-list-title">
                    <a href="{{URL::to('ayuda')}}">
                        {{Lang::get('content.help')}}
                    </a>
                </li>
                <li class="footer-list-title">
                    <a href="{{URL::to('contactanos')}}">
                        {{Lang::get('content.contact')}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="span4">
            <!-- Productos -->
            <ul>
                <li class="footer-list-title">
                    <a href="#">
                        {{Lang::get('content.product_title')}}
                    </a>
                </li>
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
        <div class="span4">

            <ul>
                <li class="footer-list-title">
                    <a href="#">
                        {{Lang::get('content.services_title')}}
                    </a>
                </li>
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
    </div>
    <div class="row address-row">
        <div class="span12 address">
            {{Lang::get('content.address_line1')}} {{Lang::get('content.address_line2')}}<br/>
            <b>{{Lang::get('content.phones_label')}}</b> {{Lang::get('content.phones_line1')}}/{{Lang::get('content.phones_line2')}}
            <b>{{Lang::get('content.fax_label')}}</b> {{Lang::get('content.fax_line1')}}
            <b>{{Lang::get('content.contactus_email')}}</b>: {{Lang::get('content.tumercato_email')}}
        </div>
    </div>
</div>

<div class="copyright-footer-container">
    <div class="container">
        <div class="row">
            <div class="span4 copyright-text">
                {{Lang::get('content.copyright')}} <a class="manito" nohref onclick='javascript:Mercatino.termsForm.show();'>{{Lang::get('content.terminos')}}</a>
            </div>
            <div class="span4 developed-by">&boxh;&boxh;&boxh;&nbsp;&nbsp; {{Lang::get('content.desarrollado_por')}} {{Lang::get('content.androb')}} &nbsp;&nbsp;&boxh;&boxh;&boxh;</div>
            <div class="span4"><img class="logos-footer-img" src="/img/logos.png" alt="{{Lang::get('content.cavenit')}}"></div>
        </div>
    </div>
</div>

