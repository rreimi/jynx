@extends('layout_backend')

@section('content')
    <div class="row-fluid stats">
        <div class="display-stats hide">
            <div class="page-header">
                <h2><small>Cántidad total de usuarios: </small>{{ $users }}</h2>
            </div>

            <ul class="thumbnails stats-row">
                <li class="span4">
                    <div class="thumbnail stats-column">
                        <input type="text" value="{{ $users_basic }}" class="dial users" data-max="{{ $users }}"/>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_users_basic') }}</h4>
                            <p>{{ Lang::get('content.stats_description_users_basic') }}</p>
                        </div>
                    </div>
                </li>
                <li class="span4">
                    <div class="thumbnail stats-column">
                        <input type="text" value="{{ $users_publisher }}" class="dial users" data-max="{{ $users }}"/>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_users_publisher') }}</h4>
                            <p>{{ Lang::get('content.stats_description_users_publisher') }}</p>
                        </div>
                    </div>
                </li>
                <li class="span4">
                    <div class="thumbnail stats-column">
                        <input type="text" value="{{ $users_to_approve }}" class="dial users" data-max="{{ $users }}"/>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_users_to_approve') }}</h4>
                            <p>{{ Lang::get('content.stats_description_users_to_approve') }}</p>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="thumbnails stats-row">
                <li class="span4">
                    <div class="thumbnail stats-column">
                        <input type="text" value="{{ $publications }}" class="dial publications" data-max="{{ $publications }}" data-fgColor="#0AA25A" data-inputColor="#0AA25A"/>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_publications') }}</h4>
                            <p>{{ Lang::get('content.stats_description_publications') }}</p>
                        </div>
                    </div>
                </li>
                <li class="span4">
                    <div class="thumbnail stats-column">
                        <input type="text" value="{{ $reports_pending }}" class="dial publications" data-max="{{ $publications }}" data-fgColor="#0AA25A" data-inputColor="#0AA25A"/>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_publications_reports') }}</h4>
                            <p>{{ Lang::get('content.stats_description_publications_reports') }}</p>
                        </div>
                    </div>
                </li>
                <li class="span4">
                    <div class="thumbnail stats-column">
                        <input type="text" value="{{ $reports }}" class="dial publications" data-max="{{ $reports }}" data-fgColor="#FAC741" data-inputColor="#FAC741"/>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_reports') }}</h4>
                            <p>{{ Lang::get('content.stats_description_reports') }}</p>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="thumbnails text-center">
                <li class="span6">
                    <div class="thumbnail ">
                        <div id="products" style="width: auto;"></div>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_products') }}</h4>
                            <p>{{ Lang::get('content.stats_description_products') }}</p>
                        </div>
                    </div>
                </li>
                <li class="span6">
                    <div class="thumbnail">
                        <div id="services" style="width: auto;"></div>
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_services') }}</h4>
                            <p>{{ Lang::get('content.stats_description_services') }}</p>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="thumbnails text-center">
                <li class="span12">
                    <div class="thumbnail ">
                        <div class="caption">
                            <h4>{{ Lang::get('content.stats_publishers') }}</h4>
                            <p>{{ Lang::get('content.stats_description_publishers') }}</p>
                        </div>
                        <div id="states" style="width: auto;"></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

@stop

@section('scripts')
@parent
    {{ HTML::script('js/jquery.knob.js') }}
    {{ HTML::script('https://www.google.com/jsapi') }}
    <script type="text/javascript">

        google.load("visualization", "1", {packages:['corechart','geochart']});

        jQuery(function(){

            jQuery('.users').knob({
                skin:'tron',
                readOnly:true,
                thickness:'0.3',
                width:156,
                height:156,
                inline:false,
                draw : Mercatino.tronSkin
            });


            jQuery('.publications').knob({
                skin:'tron',
                readOnly:true,
                thickness:'0.3',
                width:156,
                height:156,
                inline:false,
                draw :Mercatino.tronSkin
            });


            google.setOnLoadCallback(function(){
                var data = google.visualization.arrayToDataTable({{ $category_products }});

                var options={
                    legend:{position:'none'}
                }
                var chart = new google.visualization.BarChart(document.getElementById('products'));
                chart.draw(data,options);

            });

            google.setOnLoadCallback(function(){
                var data = google.visualization.arrayToDataTable({{ $category_services }});

                var options={
                    legend:{position:'none'}
                }
                var chart = new google.visualization.BarChart(document.getElementById('services'));
                chart.draw(data,options);

            });

            google.setOnLoadCallback(function(){
                var data = new google.visualization.DataTable();

                data.addColumn('string', '{{ Lang::get("content.states") }}');
                data.addColumn('number', '{{ Lang::get("content.stats_users_publisher") }}');

                data.addRows({{ $states_publishers }});

                var options = {
                    region: 'VE',
                    resolution:'provinces',
                    displayMode: 'region',
                    colorAxis: {colors: ['#3F4EFF','#2EAFFF']}
                };

                var chart = new google.visualization.GeoChart(document.getElementById('states'));
                chart.draw(data, options);
            });

            jQuery('.display-stats').removeClass('hide');


        });
    </script>
@stop