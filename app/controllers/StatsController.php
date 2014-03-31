<?php

class StatsController extends BaseController {

    public function __construct(){
        $this->beforeFilter('auth');
        $this->beforeFilter('admin');
    }

    public function getIndex(){
        // Cantidad total del usuarios
        $data['users'] = User::count();

        // Cantidad de usuarios con rol basico y que no estan optando por ser anunciantes (is_publisher = 0)
        $data['users_basic'] = User::roleBasic()->count();

        // Cantidad total de publishers
//        $data['users_publisher'] = User::rolePublisher()->count();

        // Cantidad total de publishes
        $data['publishers'] = Publisher::count();

        // Publishers con permiso para publicar (status approved)
        $data['publishers_approved'] = Publisher::statusApproved()->count();

        // Usuarios aspirando a ser anunciantes
        $data['users_to_approve'] = User::toApprove()->count();

        // Anunciantes que tienen al menos una publicacion
        // Nota: si se quita el get no trae el nro correcto.
        $data['publishers_with_publications'] = Publisher::withPublications()->get()->count();

        // Cantidad total de publicaciones
        $data['publications'] =  PublicationView::count();

        // Publicaciones Activas
        $data['publications_published'] = PublicationView::published()->count();

        // Promedio de publicaciones por anunciante
        $average = PublicationView::averagePublicationsByPublisher()->get();
        $average = $average[0]->average;
        $data['avg_publications_by_publisher'] = number_format((float)$average, 2, '.', '');

        // Publicaciones Suspendidas
        $data['publications_suspended'] = PublicationView::suspended()->count();

        // Cantidad total de reportes
        $data['reports'] = PublicationReport::count();

        // Denuncias totales que son válidas o se ha tomado una acción
        $data['reports_valid_or_action'] = PublicationReport::validOrActionReports()->count();

        $elements = DB::table('categories')
            ->join('publications_categories','categories.id','=','publications_categories.category_id')
            ->join('publications','publications_categories.publication_id','=','publications.id')
            ->select('categories.id','categories.name','categories.type',DB::raw('count(publications.id) as publications'))
            ->whereNull('categories.category_id')
            ->where('publications.status','Published')
            ->groupBy('categories.id')
            ->orderBy('categories.type')
            ->get();


        $data['category_products'][]=array(Lang::get('content.categories_title'),Lang::get('content.products'));
        $data['category_services'][]=array(Lang::get('content.categories_title'),Lang::get('content.services'));

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
            ->select('states.code','states.name',DB::raw('count(publishers.id) as publishers'))
            ->groupBy('states.id')
            ->get();

        foreach($states as $state){
            $stateValues=new stdClass();
            $stateValues->v=$state->code;
            $stateValues->f=$state->name;
            $data['states_publishers'][]=array($stateValues,intval($state->publishers));
        }

        $data['states_publishers']=json_encode($data['states_publishers']);

        return View::make('stats',$data);

    }

}