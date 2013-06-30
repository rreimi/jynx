<?php

class HomeController extends BaseController {

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
        $data['categories'] = Category::all();

        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('category', $data);
	}


    private function getCategoryTree() {

    }


    public function getCategory($slug, $id = 4) {

        /* Cargar la lsita de categorias */

        var_dump($id);
       die($slug);
    }

}