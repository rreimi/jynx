@extends('layout_home_no_sidebar')
@parent
@stop

@section('content')

<div class="row-fluid">

    <h1 class="about-us-title">{{Lang::get('content.about_us')}}</h1>

    <div class="about-us">
        <h2>Acerca de TuMercato.com</h2>
        <p>TuMercato.com es un directorio comercial virtual confiable dónde los miembros de la Cámara de Comercio Venezolana Italiana dan a conocer los productos y qué ofrecen a lo largo del país.</p>
        <p>El directorio estará compuesto por los socios inscritos en la Cámara, y progresivamente, se irán uniendo a esta iniciativa el resto de la comunidad italiana en Venezuela, organizados en Asociaciones  Regionales, Casas de Italia y Clubs Ítalos.</p>

        <h2>¿Quiénes somos?</h2>
        Esta iniciativa de la Cámara de Comercio Venezolana Italiana (CAVENIT) e italicos.com, tiene la intención de estimular las relaciones comerciales entre los empresarios, comerciantes y libres profesionales de la comunidad italiana comercialmente activa, con el resto de la comunidad italiana en Venezuela.</p>

        <h2>Cámara de Comercio Venezolana Italiana (CAVENIT)</h2>
        <div class="about-us-image">
            <img src="/img/cavenit.png"/>
        </div>
        <div>
            <p>Cavenit es una organización empresarial capaz de liderizar e impulsar oportunidades de negocio, en las diferentes ramas económicas, entre empresarios de Venezuela y del resto del mundo, divulgando y apuntalando, fundamentalmente los convenios binacionales suscritos entre Italia y Venezuela, potenciando así sus agremiados como entes bien informados y productores de bienes y servicios de calidad mundial.</p>
            <p>El objetivo principal de dicha institución es favorecer el intercambio económico entre los dos países apoyando los contactos de negocios y estimulando las actividades de formación y promoción a favor de los empresarios de ambos países.</p>
            <p>Si quiere conocer más de CAVENIT, haga click <a href="http://www.cavenit.com">aquí</a>.
        </div>
        <div class="clear-both"></div>
        <h2>Italicos.com</h2>
        <div class="about-us-image">
            <img src="/img/italicos.png"/>
        </div>
        <div>
            <p>Italicos.com es una red en línea gratuita y amigable donde individuos, grupos, organizaciones sociales y empresas privadas promueven actividades, eventos, iniciativas y concursos, relativos a una comunidad global de más de 300 millones de personas itálicas e italianas esparcidas por el globo que se identifica con unas tradiciones, cultura y valores comunes.</p>
            <p>Con el fin de crear un lugar de encuentro común, el sitio web ofrece a organizaciones y personas de la comunidad italiana e itálica un mayor alcance fomentando la participación de los usuarios a nivel mundial, para el intercambio y creación de información asociada a temas de interés  actuales, información sobre las raíces, cultura y tradiciones de la italianidad e italicidad.</p>
            <p>Si quieres conocer más de Italicos.com, haga click <a href="http://www.italicos.com">aquí</a>.</p>
        </div>
    </div>

</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
@stop