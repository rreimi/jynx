@extends('layout_backend')

@section('content')
    <div class="row-fluid stats">
        <div class="page-header">
            <h1><small>Cántidad total de usuarios: </small>{{ $users }}</h1>
        </div>

        <ul class="thumbnails text-center">
            <li class="span4">
                <div class="thumbnail ">
                    <input type="text" value="{{ $users_basic }}" class="dial users" data-max="{{ $users }}"/>
                    <div class="caption">
                        <h3>Básicos</h3>
                        <p>Cántidad de usuarios Básicos</p>
                    </div>
                </div>
            </li>
            <li class="span4">
                <div class="thumbnail">
                    <input type="text" value="{{ $users_publisher }}" class="dial users" data-max="{{ $users }}"/>
                    <div class="caption">
                        <h3>Publicadores</h3>
                        <p>Cantidad de usuarios Publicadores</p>
                    </div>
                </div>
            </li>
            <li class="span4">
                <div class="thumbnail">
                    <input type="text" value="{{ $users_to_approve }}" class="dial users" data-max="{{ $users }}"/>
                    <div class="caption">
                        <h3>Aspirando</h3>
                        <p>Cantidad de usuarios aspirando a ser Publicadores</p>
                    </div>
                </div>
            </li>
        </ul>

        <div class="page-header">
            <h1><small>Cántidad total de Publicaciones: </small>{{ $users }}</h1>
        </div>

        <ul class="thumbnails text-center">
            <li class="span6">
                <div class="thumbnail ">
                    <div id="products" style="width: 400px;"></div>
                    <div class="caption">
                        <h3>Productos</h3>
                        <p>Cantidad de productos por categoría</p>
                    </div>
                </div>
            </li>
            <li class="span6">
                <div class="thumbnail">
                    <div id="services" style="width: 400px;"></div>
                    <div class="caption">
                        <h3>Servicios</h3>
                        <p>Cantidad de servicios por categoría</p>
                    </div>
                </div>
            </li>
        </ul>

        <ul class="thumbnails text-center">
            <li class="span12">
                <div class="thumbnail ">
                    <div id="states" style="width: 900px;"></div>
                    <div class="caption">
                        <h3>Publicadores</h3>
                        <p>Cantidad de publicadores por estado</p>
                    </div>
                </div>
            </li>
        </ul>

    </div>


@stop

@section('scripts')
@parent
    {{ HTML::script('js/jquery.knob.js') }}
    {{ HTML::script('https://www.google.com/jsapi') }}
    <script type="text/javascript">

        google.load("visualization", "1", {packages:['corechart','geochart']});
        google.setOnLoadCallback(function(){
            var data = google.visualization.arrayToDataTable({{ $category_products }});

            var chart = new google.visualization.BarChart(document.getElementById('products'));
            chart.draw(data);

        });

        google.setOnLoadCallback(function(){
            var data = google.visualization.arrayToDataTable({{ $category_services }});

        var chart = new google.visualization.BarChart(document.getElementById('services'));
        chart.draw(data);

        });

        google.setOnLoadCallback(function(){
            var data = google.visualization.arrayToDataTable({{ $states_publishers }});

            var options = {
                region: 'VE',
                resolution:'provinces'
            };

            var chart = new google.visualization.GeoChart(document.getElementById('states'));
            chart.draw(data, options);
        });


        jQuery(function(){
            jQuery('.users').knob({
                skin:'tron',
                readOnly:true,
                thickness:'0.3',
                width:(verge.viewportW()>=768 && verge.viewportW()<=956)?156:(verge.viewportW()>1024?220:200),
                height:(verge.viewportW()>=768 && verge.viewportW()<=956)?156:(verge.viewportW()>1024?220:200),
                inline:false,

                draw : function () {

                    var a = this.angle(this.cv)  // Angle
                        , sa = this.startAngle          // Previous start angle
                        , sat = this.startAngle         // Start angle
                        , ea                            // Previous end angle
                        , eat = sat + a                 // End angle
                        , r = true;

                    this.g.lineWidth = this.lineWidth;

                    this.o.cursor
                        && (sat = eat - 0.3)
                    && (eat = eat + 0.3);

                    if (this.o.displayPrevious) {
                        ea = this.startAngle + this.angle(this.value);
                        this.o.cursor
                            && (sa = ea - 0.3)
                        && (ea = ea + 0.3);
                        this.g.beginPath();
                        this.g.strokeStyle = this.previousColor;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                        this.g.stroke();
                    }

                    this.g.beginPath();
                    this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                    this.g.stroke();

                    this.g.lineWidth = 2;
                    this.g.beginPath();
                    this.g.strokeStyle = this.o.fgColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                    this.g.stroke();

                    return false;
                }
            });
        });
    </script>
@stop