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
        //$this->beforeFilter('auth', array('only' => array('getList')));
        View::share('categories', self::getCategories());
        View::share('thumbSize', self::$thumbSize);

    }

	public function getIndex() {

		/* Cargar la lista de categorias */
        $categories = self::getCategories();
        $data['categories'] = $categories;
        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */
        $data['mostvisited'] = Publication::mostvisited()->get();
        $data['recent'] = Publication::orderBy('created_at', 'desc')->take(3)->get();

        //$data['mostvisited']->images();
        /* Cargar la lista de los últimos productos agregados */

        /* Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('dashboard', $data);
	}

    public function getCat($slug = '') {

        /* Load category by slug */
        $data['category'] = Category::where('slug', '=', $slug)->with('publications')->first();

        if ($data['category'] == null) {
            return Response::view('errors.missing', array(), 404);
        }

        /* Load paginated category publications */
        //$data['publications'] = $data['category']->publications()->with('publisher')->paginate($this->page_size);
        $data['publications'] = $data['category']->publications()->with('publisher','images')->paginate($this->page_size);

//        foreach ($data['publications'] as $item) {
//            echo $item->publisher;
//        }

      //$queries = DB::getQueryLog();
      //var_dump($queries);
//      die();


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

        /* Find publications */
        $data['publications'] = PublicationView::getSearch($q)->with('images')->paginate($this->page_size);

        /* Load category list */
        $data['categories'] = self::getCategories();

        /* TODO: Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* Cargar la lista de los últimos productos vistos por el usuario actual */

        $queries = DB::getQueryLog();
        //var_dump($queries);

        return View::make('search', $data);
    }


    public function getLogout(){
        Auth::logout();
        return Redirect::to('login');
    }
}