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
        View::share('categories', self::getCategories());
        View::share('services', self::getServices());
        View::share('thumbSize', self::$thumbSize);
        View::share('bannerTopHomeSize', self::$bannerTopHomeSize);

    }

	public function getIndex() {

        //Load category list -> see constructor
        $sliderSize = $this->sliderSize;
        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */
        $data['activeadvertisings'] = array();
        $data['mostvisited'] = array();
        $data['recent'] = array();
        $data['lastvisited'] = array();
        $activationFlag = Input::get('activacion');
        $data['activationFlag'] = (isset($activationFlag) && !empty($activationFlag)) ? $activationFlag : '' ;

        $data['activeadvertisings'] = Cache::rememberForever('currentAdvertising', function() {
            return Advertising::activehomeadvertisings()->get();
        });
        //$data['activeadvertisings'] = Advertising::activehomeadvertisings()->get();

        /* Cache de un día para los elementos mas visitados */
        $data['mostvisited'] = Cache::remember('homeMostVisited', 1440, function() use ($sliderSize) {
            return HomePublicationView::mostVisited($sliderSize)->get();
        });

        /* Cache de productos mas recientes, se resetea cuando hay productos nuevos */
        $data['recent'] = Cache::rememberForever('homeRecent', function() use ($sliderSize) {
            return HomePublicationView::recent($sliderSize)->get();
        });

        // Flag to show register popup in /registro url.
        $data['registro'] = Input::get('registro');

        /* Get cookie of last visited by the user */
        $cookieName = (Auth::check()) ? ('last_visited_'. Auth::user()->id) : 'last_visited';
        $cookieArray = Cookie::get($cookieName);
        if (isset($cookieArray)){
            $lastVisited = HomePublicationView::whereIn("id", $cookieArray)->get();
            $lastVisitedOrdered = array();
            foreach ($cookieArray as $item){
                foreach ($lastVisited as $key => $value){
                    if ($value->id == $item){
                        $lastVisitedOrdered[] = $value;
                        break;
                    }
                }
            }
            $data['lastvisited'] = $lastVisitedOrdered;

        }

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

        $data['category_tree'][] = $data['category']->id;
        $parent = $data['category']->parent;

        while ($parent != null ){
            $data['category_tree'][] = $parent->id;
            $parent = $parent->parent;
        }

        //$data['publications'] = PublicationView::getSearch($q)->groupBy('id')->published()->filter($activeFilters)->with('images')->paginate($this->page_size);
        $data['publications'] = PublicationView::where('category_id', $data['category']->id)->groupBy('id')->published()->filter($activeFilters)->with('images')->paginate($this->page_size);

        /* Calculate filters */
        $availableFilters = array();

        if (!isset($activeFilters['state'])){
            $result = PublicationView::where('category_id', $data['category']->id)->orderBy('label', 'asc')->select(DB::raw('count(DISTINCT(id)) as total, id, state_id as item_id, state as label'))->groupBy('state')->orderBy('label', 'asc')->published()->filter($activeFilters)->get();
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
            $result = PublicationView::where('category_id', $data['category']->id)->orderBy('label', 'asc')->select(DB::raw('count(DISTINCT(id)) as total, id, publisher_id as item_id, seller_name as label'))->groupBy('seller_name')->orderBy('label', 'asc')->published()->filter($activeFilters)->get();
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


        /* Cargar la lista de categorias */
//        $data['categories'] = self::getCategories();

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


        /* Calculate filters */
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
            $result = PublicationView::getSearch($q, 'label', 'asc')->select(DB::raw('count(DISTINCT(id)) as total, id, state_id as item_id, state as label'))->groupBy('state')->orderBy('label', 'asc')->published()->filter($activeFilters)->get();
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
            $result = PublicationView::getSearch($q, 'label', 'asc')->select(DB::raw('count(DISTINCT(id)) as total, id, publisher_id as item_id, seller_name as label'))->groupBy('seller_name')->orderBy('label', 'asc')->published()->filter($activeFilters)->get();
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

    public function getAcercaDe(){
        return View::make('about_us');
    }
}