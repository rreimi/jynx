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

	public function getIndex() {

		/* Cargar la lista de categorias */
        $categories = self::getCategories();
        $data['categories'] = $categories;
        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */
        $data['mostvisited'] = Publication::orderBy('visits_number', 'desc')->take(15)->get();
        $data['recent'] = Publication::orderBy('created_at', 'desc')->take(15)->get();

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


        //$publications = Publication::orderBy('publisher_id')
//            ->join('category_publisher', function($join)
//            {
//                $join->on('category_publication.publisher_id', '=', 'publisher_id')->orOn(...);
//            })
//            ->paginate(3);

        //echo $publications->links();
      $queries = DB::getQueryLog();
      //var_dump($queries);
//      die();

        /* Append search query */
        $data['q'] = $q;

        /* Find publications */
        $data['publications'] = Publication::getSearch($q)->with('categories','images')->paginate($this->page_size);

        /* Load category list */
        $data['categories'] = self::getCategories();

        /* TODO: Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('search', $data);
    }

}