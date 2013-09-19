<?php

class StatsController extends BaseController {

    public function __construct(){
        $this->beforeFilter('admin');
    }

    public function getIndex(){
        $data['users']=User::count();

        $data['users_basic']=User::roleBasic()->count();

        $data['users_publisher']=User::rolePublisher()->count();

        $data['users_to_approve']=User::toApprove()->count();

        $data['reports']=PublicationReport::pendingReports()->count();

        $data['reports_pending']=count(PublicationReport::select(DB::raw('distinct(publication_id)'))->pendingReports()->distinct()->get());

        $data['publications']=Publication::count();

        $elements=DB::table('categories')
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