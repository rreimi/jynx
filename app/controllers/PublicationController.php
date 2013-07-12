<?php

class PublicationController extends BaseController {

    private $page_size = '6';
    private $row_size = '3';
    private $pubListSort = array('id', 'title', 'from_date', 'to_date', 'visits_number', 'created_at');
    private $currentListUrl;

    public function __construct() {
        //$this->beforeFilter('auth', array('only' => array('getList')));
    }

	public function getDetalle($id = null) {

        if ($id == null){
            return Response::view('errors.missing', array(), 404);
        }

		/* Cargar la lista de categorias */
        $data['categories'] = self::getCategories();
        $data['publication'] = Publication::with('images', 'publisher', 'publisher.contacts')->find($id);

        //var_dump(DB::getQueryLog());
        //die();
        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* TODO Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('publication', $data);
	}

    public function getList() {

        /* Get sort params */
        //$sort = (in_array(Input::get('sort'), $this->pubListSort) ? Input::get('sort') : 'id');
        //$order = (in_array(Input::get('order'), array('asc')) ? Input::get('order') : 'desc');


        //$q = Input::get('q');

        $state = self::retrieveListState();
        $publications = PublicationView::orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $publications->orWhere(function($query) use ($q)
            {
                $query->orWhere('title', 'LIKE', '%' . $q . '%')
                    ->orWhere('categories_name', 'LIKE', '%' . $q . '%')
                    ->orWhere('from_date', 'LIKE', '%' . $q . '%')
                    ->orWhere('to_date', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $publications->where('status', '=', $status);
        }

        $publications = $publications->paginate(3);

        return View::make('publication_list', array(
            'pub_statuses' => self::getPublicationStatuses(Lang::get('content.filter_status')),
            'publications' => $publications,
            'categories' => self::getCategories(),
            'state' => $state,
            ) //end array
        );
    }

    public function postList() {
        return $this->getList();
    }

    private function retrieveListState(){
        $state = Session::get('pub_list.state');

        $sort = (in_array(Input::get('sort'), $this->pubListSort) ? Input::get('sort') : null);

        if ((isset($sort)) || !(isset($state['sort']))) {
            $state['sort'] = (isset($sort))? $sort : 'id';
        }

        $order = (in_array(Input::get('order'), array('asc', 'desc')) ? Input::get('order') : null);

        if ((isset($order)) || !(isset($state['order']))) {
            $state['order'] = (isset($order))? $order : 'desc';
        }

        $q = (!is_null(Input::get('q')) ? Input::get('q') : null);

        if ((isset($q)) || !(isset($state['q']))) {
            $state['q'] = (isset($q))? $q : '';
        }

        $status = (!is_null(Input::get('filter_status')) ? Input::get('filter_status') : null);

        if ((isset($status)) || !(isset($state['filter_status']))) {
            $state['filter_status'] = (isset($status))? $status : '';
        }

        Session::put('pub_list.state', $state);
        return $state;
    }

    public function getCrear() {

        /* Get the current user publications list */
//        echo $publications;
//        die();
        return View::make('publication_form', array('pub_statuses' => self::getPublicationStatuses(), 'categories' => self::getCategories()));

    }

    public function postCrear() {
        die('crear asd');
        /* Get the current user publications list */
//        echo $publications;
//        die();

        return View::make('publication_form', array('categories' => self::getCategories()));

    }

    private static function getPublicationStatuses($blankCaption = '') {

        $options = array (
            'Draft' => Lang::get('content.status_draft'),
            'Published' => Lang::get('content.status_published'),
            'On_Hold' => Lang::get('content.status_on_Hold'),
            'Suspended' => Lang::get('content.status_suspended'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }
}