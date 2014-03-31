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

        $data['activeadvertisings'] = Cache::remember(CacheHelper::$ADVERTISING_CURRENT, 86400, function() {
            return Advertising::activehomeadvertisings()->get();
        });

        /* Cache de un día para los elementos mas visitados */
        $data['mostvisited'] = Cache::remember(CacheHelper::$PUBLICATIONS_MOST_VISITED, 1800, function() use ($sliderSize) {
            return HomePublicationView::mostVisited($sliderSize)->get();
        });

        /* Cache de productos mas recientes, se resetea cuando hay productos nuevos */
        $data['recent'] = Cache::remember(CacheHelper::$PUBLICATIONS_MOST_RECENT, 1800, function() use ($sliderSize) {
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
        $data['category'] = Category::where('slug', '=', $slug)->first();
        $data['category'] = Category::getFromCache($data['category']->id);

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
            $queryFilters['state'] = $state->id;
        }

        if (Input::get('seller')) {
            $seller = Publisher::find(Input::get('seller'));
            $activeFilters['seller'] = new stdClass;
            $activeFilters['seller']->id  = $seller->id;
            $activeFilters['seller']->label = $seller->seller_name;
            $activeFilters['seller']->type = 'seller';
            $queryFilters['seller'] = $seller->id;
        }

        //Set category as main filter
        if (Input::get('category')) {
            $queryFilters['category'] = $data['category']->id;
        }

        $queryFilters['order_by'] = 'updated_at';
        $queryFilters['order_dir'] = 'desc';

        /* Contar el total */
        $totalQuery = PublicationView::getSearchQuery('', 'COUNT(*) as total', $queryFilters);
        $resultTotalQuery = DB::select($totalQuery);
        $totalItems = $resultTotalQuery[0]->total;

        /* Añadir filtros de paginacion al query  */

        $offset = (Input::get('page', 1)-1) * $this->page_size;
        $queryFilters['offset'] = ($offset < $totalItems)? $offset : 0;
        $queryFilters['page_size'] = $this->page_size;

        $searchQuery = PublicationView::getSearchQuery('', '*', $queryFilters);
        $items = DB::select($searchQuery);
        $data['publications'] = Paginator::make($items, $totalItems, $this->page_size);


        //Get parent information
        $data['category_tree'][] = $data['category']->id;
        $parent = Category::getFromCache($data['category']->category_id);

        while ($parent != null ){
            $data['category_tree'][] = $parent->category_id;
            $parent = Category::getFromCache($parent->category_id);
        }

        $subcategories = array();

        /* Subcategories */
        foreach ($data['category']->subcategories as $subcatId) {
            $subcategories[] = Category::getFromCache($subcatId);
        }

        $data['category']->subcategories = $subcategories;



        /* Calculate filters */
        $availableFilters = array();
        //Reset query limits
        $queryFilters['offset'] = null;
        $queryFilters['page_size'] = null;

        if (!isset($activeFilters['state'])){
            $queryFilters['group_by'] = null;
            $searchQuery = PublicationView::getSearchQuery('', 'id', $queryFilters);
            $queryState = "SELECT COUNT( pc.id ) AS total, c.state_id AS item_id, s.name AS label FROM publications_contacts pc JOIN contacts c ON c.id = pc.contact_id JOIN states s ON s.id = c.state_id WHERE pc.publication_id IN ($searchQuery) GROUP BY state_id ORDER BY label ASC";
            $result = DB::select($queryState);
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

            $queryFilters['order_by'] = 'label';
            $queryFilters['order_dir'] = 'asc';
            $queryFilters['group_by'] = 'publisher_id';
            $searchQuery = PublicationView::getSearchQuery('', 'count(DISTINCT(id)) as total, id, publisher_id as item_id, seller_name as label', $queryFilters);

            $result = DB::select($searchQuery);
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

        return View::make('category', $data);
    }

    /**
     * Search publications
     */
    public function getSearch(){

        $q = Input::get('q');

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
            $queryFilters['state'] = $state->id;
        }

        if (Input::get('seller')) {
            $seller = Publisher::find(Input::get('seller'));
            $activeFilters['seller'] = new stdClass;
            $activeFilters['seller']->id  = $seller->id;
            $activeFilters['seller']->label = $seller->seller_name;
            $activeFilters['seller']->type = 'seller';
            $queryFilters['seller'] = $seller->id;
        }

        if (Input::get('category')) {
            $category = Category::find(Input::get('category'));
            $activeFilters['category'] = new stdClass;
            $activeFilters['category']->id  = $category->id;
            $activeFilters['category']->label = $category->name;
            $activeFilters['category']->type = 'category';
            $queryFilters['category'] = $category->id;
        }

        $queryFilters['order_by'] = 'updated_at';
        $queryFilters['order_dir'] = 'desc';

        /* Contar el total */
        $totalQuery = PublicationView::getSearchQuery($q, 'COUNT(*) as total', $queryFilters);
        $resultTotalQuery = DB::select($totalQuery);
        $totalItems = $resultTotalQuery[0]->total;

        /* Añadir filtros de paginacion al query  */

        $offset = (Input::get('page', 1)-1) * $this->page_size;
        $queryFilters['offset'] = ($offset < $totalItems)? $offset : 0;
        $queryFilters['page_size'] = $this->page_size;

        $searchQuery = PublicationView::getSearchQuery($q, '*', $queryFilters);
        $items = DB::select($searchQuery);

        $data['publications'] = Paginator::make($items, $totalItems, $this->page_size);

        //echo $searchQuery;

        //die();

        //$data['publications'] = PublicationView::getSearch($q, 'updated_at', 'desc')->groupBy('id')->published()->filter($activeFilters)->paginate($this->page_size);

//        $groupBy = '', $orderBy = 'updated_at', $orderDir = 'desc'

        /* Calculate filters */
        $availableFilters = array();
        $queryFilters['offset'] = null;
        $queryFilters['page_size'] = null;

        if (!isset($activeFilters['category'])){
            $queryFilters['group_by'] = null;
            $searchQuery = PublicationView::getSearchQuery($q, 'id', $queryFilters);
            $queryCat = "SELECT COUNT( pc.category_id ) as total, pc.category_id as item_id, c.name as label FROM publications_categories pc JOIN categories AS c ON pc.category_id = c.id WHERE pc.publication_id IN ($searchQuery) GROUP BY pc.category_id ORDER BY label ASC";
            $result = DB::select($queryCat);
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
            $queryFilters['group_by'] = null;
            $searchQuery = PublicationView::getSearchQuery($q, 'id', $queryFilters);
            $queryState = "SELECT COUNT( pc.id ) AS total, c.state_id AS item_id, s.name AS label FROM publications_contacts pc JOIN contacts c ON c.id = pc.contact_id JOIN states s ON s.id = c.state_id WHERE pc.publication_id IN ($searchQuery) GROUP BY state_id ORDER BY label ASC";
            $result = DB::select($queryState);
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

            $queryFilters['order_by'] = 'label';
            $queryFilters['order_dir'] = 'asc';
            $queryFilters['group_by'] = 'publisher_id';
            $searchQuery = PublicationView::getSearchQuery($q, 'count(DISTINCT(id)) as total, id, publisher_id as item_id, seller_name as label', $queryFilters);

            $result = DB::select($searchQuery);
            foreach ($result as $filter) {
                $item = new stdClass;
                $item->total = $filter->total;
                $item->item_id = $filter->item_id;
                $item->label = $filter->label;
                $item->type = 'seller';
                $availableFilters['seller'][] = $item;
            }
        }

        //var_dump(DB::getQueryLog()); ;

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

    public function getAyuda(){
        return View::make('help');
    }
}