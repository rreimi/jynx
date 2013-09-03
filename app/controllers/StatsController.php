<?php

class StatsController extends BaseController {

    public function getIndex(){
        $data['users']=User::count();

        $data['users_basic']=User::roleBasic()->count();

        $data['users_publisher']=User::rolePublisher()->count();

        $data['users_to_approve']=User::toApprove()->count();


        $elements=DB::table('categories')
            ->join('publications_categories','categories.id','=','publications_categories.category_id')
            ->join('publications','publications_categories.publication_id','=','publications.id')
            ->select('categories.id','categories.name','categories.type',DB::raw('count(publications.id) as publications'))
            ->whereNull('categories.category_id')
            ->where('publications.status','Published')
            ->groupBy('categories.id')
            ->orderBy('categories.type')
            ->get();


        $data['category_products'][]=array('Categorias','Productos');
        $data['category_services'][]=array('Categorias','Servicios');

        foreach($elements as $element){
            if($element->type==Category::TYPE_PRODUCT){
                $data['category_products'][]=array($element->name,intval($element->publications));
            }else{
                $data['category_services'][]=array($element->name,intval($element->publications));
            }
        }

        $data['category_products']=json_encode($data['category_products']);
        $data['category_services']=json_encode($data['category_services']);

        $states=DB::table('publishers')
            ->join('states','states.id','=','publishers.state_id')
            ->select('states.name',DB::raw('count(publishers.id) as publishers'))
            ->groupBy('states.id')
            ->get();

        $data['states_publishers'][]=array('Estados','Publicadores');

        foreach($states as $state){
            $data['states_publishers'][]=array($state->name,$state->publishers);
        }

        $data['states_publishers']=json_encode($data['states_publishers']);

        //cantidad de denuncias pendietes por revisar

        /*

        echo "<br>Denuncias Pendientes ";
        echo PublicationReport::pendingReports()->count();

        //Cantidad total de publicaciones

        echo "<br>Publicaciones ";
        echo Publication::count(); //Preguntar si son todas las publicaciones


        //Cantidad de publicadores por estado (Mapa)

        echo "Publicadores por estado <br>";



        foreach(State::with('publishers')->get() as $state){
            echo $state->name.': '.count($state->publishers)."<br>";
        }

        echo "<br>";
        */

        /*var_dump();
        */
        return View::make('stats',$data);

    }

}