<?php

class PublicationController extends BaseController {

    private $page_size = '6';
    private $row_size = '3';


    public function __construct() {
        //$this->beforeFilter('auth', array('only' => array('getList')));
    }

	public function getDetalle($id) {

		/* Cargar la lista de categorias */
        $data['categories'] = self::getCategories();
        $data['publication'] = Publication::with('images', 'publisher', 'publisher.contacts')->find($id);

        //$queries = DB::getQueryLog();
        //var_dump($queries);
        //die();
        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* TODO Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('publication', $data);
	}

    public function getList() {

        /* Get the current user publications list */
        $publications = PublicationView::paginate(3);

//        echo $publications;
//        die();

        return View::make('publication_list', array('publications' => $publications, 'categories' => self::getCategories()));

    }
}