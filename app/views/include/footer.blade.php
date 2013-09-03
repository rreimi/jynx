<div class="container">
    <div class="dynamic-footer-blocks">
        <div class="row-fluid products-footer footer-block">
            <div class="footer-title">
                {{Lang::get('content.product_title')}}
            </div>

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
            <div class="footer-title">
                {{Lang::get('content.services_title')}}
            </div>
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
            <div class="footer-title">
                CONTACTO
            </div>
            <div>
                <div class="contact-row address contact-text">
                    Av. San Juan Bosco, Edif. Centro Altamira,<br/>
                    Nivel Mezzanina Caracas 1060, Venezuela
                </div>
                <br/>
                <div class="contact-row phone contact-text">
                    Teléfonos: 58-212-2632427 / 2634614<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        58-212-2642845 / 2643742
                </div>
                <br/>
                <div class="contact-row fax contact-text">
                    Fax: 58-212-2647213
                </div>
                <br/>
                <a href="mailto:informatica@cavenit.com">
                    <div class="contact-row mail contact-text">
                    informatica@cavenit.com
                    </div>
                </a>
                <br/>
                <a href="https://www.facebook.com/cavenit" target="_blank">
                    <div class="contact-row facebook contact-text">
                        CAVENIT
                    </div>
                </a>
                <br/>
                <a href="https://twitter.com/cavenit" target="_blank">
                    <div class="contact-row twitter contact-text">
                    @cavenit
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="footer-block footer-static-blocks">
        <div class="footer-title footer-block us-footer">
            NOSOTROS
        </div>

        <div class="footer-title footer-block allies-footer">
            ALIADOS
        </div>

        <div class="footer-title footer-block help-footer">
            AYUDA
        </div>

        <div class="footer-title footer-block news-footer">
            NOTICIAS Y OFERTAS
        </div>
    </div>

    <div class="footer-block logos-footer">
        <img src="../img/logos.png">
    </div>
</div>
<div class="clear-both copyright-footer">
    <div class="androb">
        &copy; Copyright 2013 CAVENIT - TuMercato.com &nbsp; | &nbsp; Desarrollado por <a href="http://www.androb.com" target="_blank">ANDROB</a>
    </div>
</div>

