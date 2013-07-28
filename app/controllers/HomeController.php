<?php

class HomeController extends BaseController {

    protected $data;
    private $page_size = '6';
    private $row_size = '3';
	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    public function __construct() {
        $this->beforeFilter('auth');
        View::share('categories', self::getCategories());
        View::share('thumbSize', self::$thumbSize);
        View::share('bannerTopHomeSize', self::$bannerTopHomeSize);

    }

	public function getIndex() {

        //Load category list -> see constructor

        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */
        $data['activeadvertisings'] = array();
        $data['mostvisited'] = array();
        $data['recent'] = array();


//        $data['activeadvertisings'] = Advertising::activehomeadvertisings()->get();
        $data['mostvisited'] = Publication::published()->mostvisited(12)->get();
        $data['recent'] = Publication::published()->recent(12)->get();

        //$data['mostvisited']->images();
        /* Cargar la lista de los últimos productos agregados */

        /* Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('home', $data);
	}

    public function getCat($slug = '') {

        /* Load category by slug */
        $data['category'] = Category::where('slug', '=', $slug)->with('publications','parent')->first();

        if ($data['category'] == null) {
            return Response::view('errors.missing', array(), 404);
        }

        /* Load paginated category publications */
        //$data['publications'] = $data['category']->publications()->with('publisher')->paginate($this->page_size);
        $data['publications'] = $data['category']->publications()->published()->with('publisher','images')->paginate($this->page_size);

//        foreach ($data['publications'] as $item) {
//            echo $item->publisher;
//        }

      //$queries = DB::getQueryLog();
      //var_dump($queries);
//      die();

        $data['category_tree'][] = $data['category']->id;
        $parent = $data['category']->parent;

        while ($parent != null ){
            $data['category_tree'][] = $parent->id;
            $parent = $parent->parent;
        }

        /* Cargar la lista de categorias */
        $data['categories'] = self::getCategories();

        /* TODO: Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('category', $data);
    }

    /**
     * Search publications
     */
    public function getSearch(){

        $q = Input::get('q');
        //echo Publication::getSearch($q)->with('categories')->get();

        /* Append search query */
        $data['q'] = $q;

        /* Load filters */
        $activeFilters = array();

        if (Input::get('state')) {
            $state = State::find(Input::get('state'));
            $activeFilters['state'] = new stdClass;
            $activeFilters['state']->id  = $state->id;
            $activeFilters['state']->label = $state->name;
            $activeFilters['state']->type = 'state';
        }

        if (Input::get('seller')) {
            $seller = Publisher::find(Input::get('seller'));
            $activeFilters['seller'] = new stdClass;
            $activeFilters['seller']->id  = $seller->id;
            $activeFilters['seller']->label = $seller->seller_name;
            $activeFilters['seller']->type = 'seller';
        }

        if (Input::get('category')) {
            $category = Category::find(Input::get('category'));
            $activeFilters['category'] = new stdClass;
            $activeFilters['category']->id  = $category->id;
            $activeFilters['category']->label = $category->name;
            $activeFilters['category']->type = 'category';
        }

        /* Find publications */
        //$data['publications'] = PublicationView::getSearch($q)->published()->with('images')->paginate($this->page_size);
        $data['publications'] = PublicationView::getSearch($q)->groupBy('id')->published()->filter($activeFilters)->with('images')->paginate($this->page_size);
        //echo $data['publications'];

        //dd(DB::getQueryLog());
        //die();
        $availableFilters = array();

        if (!isset($activeFilters['category'])){
            $result = PublicationView::getSearch($q, 'label', 'asc')->select(DB::raw('count(*) as total, category_id as item_id, category_name as label'))->groupBy('category_id')->orderBy('label', 'asc')->published()->filter($activeFilters)->get();
            foreach ($result as $filter) {
                $item = new stdClass;
                $item->total = $filter->total;
                $item->item_id = $filter->item_id;
                $item->label = $filter->label;
                $item->type = 'category';
                $availableFilters['category'][] = $item;
            }
        }

        if (!isset($activeFilters['state'])){
            $result = PublicationView::getSearch($q, 'label', 'asc')->select(DB::raw('count(*) as total, state_id as item_id, state as label'))->groupBy('state_id')->orderBy('label', 'asc')->published()->filter($activeFilters)->get();
            foreach ($result as $filter) {
                $item = new stdClass;
                $item->total = $filter->total;
                $item->item_id = $filter->item_id;
                $item->label = $filter->label;
                $item->type = 'state';
                $availableFilters['state'][] = $item;
            }
        }

        if (!isset($activeFilters['seller'])){
            $result = PublicationView::getSearch($q, 'label', 'asc')->select(DB::raw('count(*) as total, publisher_id as item_id, seller_name as label'))->groupBy('seller_name')->orderBy('label', 'asc')->published()->filter($activeFilters)->get();
            foreach ($result as $filter) {
                $item = new stdClass;
                $item->total = $filter->total;
                $item->item_id = $filter->item_id;
                $item->label = $filter->label;
                $item->type = 'seller';
                $availableFilters['seller'][] = $item;
            }
        }

        $data['availableFilters'] = $availableFilters;
        $data['activeFilters'] = $activeFilters;

        return View::make('search', $data);
    }


    public function getLogout(){
        Auth::logout();
        return Redirect::to('login');
    }
}