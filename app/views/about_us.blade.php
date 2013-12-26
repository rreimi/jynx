@extends('layout_home_no_sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    <h1 class="about-us-title">{{Lang::get('content.about_us')}}</h1>

    <div class="about-us">
        <h2>Acerca de TuMercato.com</h2>
        <p>TuMercato.com es un directorio comercial virtual en el cuál se dan a conocer los productos y servicios que ofrecen los miembros de la Cámara de Comercio Venezolana Italiana a lo largo del país.</p>
        <p>Progresivamente, se irán incorporando a esta iniciativa el resto de la comunidad italiana en Venezuela, organizados en Asociaciones  Regionales, Casas de Italia y Clubs ítalo-venezolanos.</p>

        <h2>¿Quiénes somos?</h2>
        <p>Esta iniciativa de la Cámara de Comercio Venezolana Italiana (CAVENIT) e italicos.com, tiene la intención de estimular las relaciones comerciales entre los empresarios, comerciantes y libres profesionales de la comunidad italiana comercialmente activa, con el resto de la comunidad italiana en Venezuela.</p>

        <h2>Cámara de Comercio Venezolana Italiana (CAVENIT)</h2>
        <div class="about-us-image">
            <img src="/img/cavenit.png"/>
            <div class="rif">CAVENIT, J-00066510-9</div>
        </div>
        <div>
            <p>Cavenit es una organización empresarial capaz de liderar e impulsar oportunidades de negocio, en las diferentes ramas económicas, entre empresarios de Venezuela y del resto del mundo, divulgando y apuntalando, fundamentalmente, los convenios binacionales suscritos entre Italia y Venezuela, potenciando así sus agremiados como entes bien informados y productores de bienes y servicios de calidad mundial.</p>
            <p>El objetivo principal de Cavenit es favorecer el intercambio económico entre los dos países apoyando los contactos de negocios y estimulando las actividades de formación y promoción a favor de los empresarios de ambos países.</p>
            <p>Si quiere conocer más de CAVENIT, haga click <a href="http://www.cavenit.com" target="_blank">aquí</a>.
        </div>
        <div class="clear-both"></div>
        <h2>Italicos.com</h2>
        <div class="about-us-image">
            <img src="/img/italicos.png"/>
            <div class="rif">E-Italiani Producciones, C.A, J-40083848-7</div>
        </div>
        <div>
            <p>Italicos.com, es la empresa que provee la plataforma tecnológica para TuMercato.com.</p>
            <p>Es una red social en línea gratuita en la cual individuos, grupos, organizaciones sociales y empresas privadas promueven actividades, eventos, iniciativas y concursos, relativos a la comunidad italiana esparcida por el globo. Comunidad ésta que se estima en más de 300 millones de personas.</p>
            <p>Con el fin de crear un lugar de encuentro común, el sitio web ofrece a organizaciones y personas de la comunidad italiana e itálica un mayor alcance fomentando la participación de los usuarios a nivel mundial, para el intercambio y creación de información asociada a temas de interés  actuales, información sobre las raíces, cultura y tradiciones de la <i>italianidad</i>.</p>
            <p>Si quieres conocer más de Italicos.com, haga click <a href="http://www.italicos.com" target="_blank">aquí</a>.</p>
        </div>
    </div>

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
@stop