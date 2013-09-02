<?php

class StatsController extends BaseController {

    public function getIndex(){
        $data['users']=User::count();


        //Cantidad total de usuarios basicos y publicadores

        $data['users_basic']=User::roleBasic()->count();

        $data['users_publisher']=User::rolePublisher()->count();

        $data['users_to_approve']=User::toApprove()->count();

        //cantidad de denuncias pendietes por revisar

        /*

        echo "<br>Denuncias Pendientes ";
        echo PublicationReport::pendingReports()->count();

        //Cantidad total de publicaciones

        echo "<br>Publicaciones ";
        echo Publication::count(); //Preguntar si son todas las publicaciones

        //Cantidad de productos por categoria base (Barras)

        echo "<br><br> Productos por categor&iacute;a base <br>";

        var_dump(DB::table('categories')
            ->join('publications_categories','categories.id','=','publications_categories.category_id')
            ->join('publications','publications_categories.publication_id','=','publications.id')
            ->select('categories.id','categories.name','categories.type',DB::raw('count(publications.id) as publications'))
            ->whereNull('categories.category_id')
            ->where('publications.status','Published')
            ->groupBy('categories.id')
            ->orderBy('categories.type')
            ->get());


        foreach(Category::parents()->onlyProducts()->with('publications')->get() as $category){
            echo $category->name;
            foreach($category->publications as $publication){
                echo $publication->id. "<br>";
            }
        }

        echo "<br>";

        //Cantidad de servicios por categoria base (Barras)

        echo "Servicios por categor&iacute;a base <br>";

        foreach(Category::parents()->onlyServices()->with('publications')->get() as $category){
            echo $category->name.': '.count($category->publications)."<br>";
        }

        echo "<br>";

        //Cantidad de publicadores por estado (Mapa)

        echo "Publicadores por estado <br>";



        foreach(State::with('publishers')->get() as $state){
            echo $state->name.': '.count($state->publishers)."<br>";
        }

        echo "<br>";
        */

        /*var_dump(DB::table('publishers')
            ->join('states','states.id','=','publishers.state_id')
            ->select('states.name',DB::raw('count(publishers.id) as publishers'))
            ->groupBy('states.id')
            ->get());
        */
        return View::make('stats',$data);

    }

}