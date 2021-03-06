<?php

class PublicationController extends BaseController {

    private $prefix = 'publication';
    private $page_size = '10';
    private $listSort = array('id', 'title', 'from_date', 'to_date', 'visits_number', 'created_at', 'status', 'rating_avg', 'seller_name', 'reports', 'ratings');
    private $pub_img_dir = 'uploads';

    public function __construct() {
        $this->beforeFilter('auth', array('except' => array('getDetalle', 'getCambiarEstatusPorFechas', 'getVerificarVencimientoPublicacion')));
        $this->beforeFilter('referer:publication', array('only' => array('getLista', 'getDetalle')));
        if (!Auth::check()){
            $this->beforeFilter('referer:login_redirect', array('only' => array('getDetalle')));
        }

        View::share('bannerTopHomeSize', self::$bannerTopHomeSize);

        View::share('categories', self::getCategories());
        View::share('services', self::getServices());
        View::share('detailSize', self::$detailSize);
        View::share('thumbSize', self::$thumbSize);
    }

    /**
     * Load publication detail
     *
     * @param null $id
     * @return mixed
     */
    public function getDetalle($id = null) {

        if ($id == null){
            return Response::view('errors.missing', array(), 404);
        }

		/* Cargar la lista de categorias */
        $data['publication'] = Cache::remember(CacheHelper::$PUBLICATION . $id, 86400, function() use ($id) {
            return Publication::with('images', 'publisher', 'contacts', 'categories')->find($id);
        });

        //TODO Validar que la publicacion exista

        // INIT - Create cookie for last visited
        $cookieName = (Auth::check()) ? ('last_visited_'. Auth::user()->id) : 'last_visited';

        $cookieArray = Cookie::get($cookieName);

        // Si la cookie existe previamente
        if (isset($cookieArray)){

            // Verificar en que posicion existe la publicidad actual
            $existIndex = array_search($id, $cookieArray);

            // Si existe mover elementos desde esa posicion, si no existe desde el tamaño completo del arreglo
            $indexIter = ($existIndex === false) ? (sizeof($cookieArray)) : $existIndex ;

            // Se mantiene tamaño maximo del arreglo como 8 elementos
            if ($indexIter > ($this->sliderSize - 1)){
                $indexIter = ($this->sliderSize - 1);
            }

            // Se desplazan todas las posiciones
            for ($i=$indexIter; $i>0; $i--){
                $cookieArray[$i] = $cookieArray[$i-1];
            }

            // Se guarda el elemento actual como el mas reciente
            $cookieArray[0] = $id;

        // Si la cookie no existe se crea
        } else {
            $cookieArray = array();
            $cookieArray[] = $id;
        }

        $cookie = Cookie::forever($cookieName, $cookieArray);
        // END - Create cookie for last visited

        // Increment publication visits
        Queue::later(60, 'VisitsJob', array('publication_id' => $id));  //3 Query
        // END - Create log of publication

        // Load last view by current user
        /* $publisher = User::find($data['publication']->publisher->user_id);
        $data['publisher_email']=$publisher->email;
        */
        $data['publisher_email']= $data['publication']->publisher->email;
        $data['publisher_full_name']= $data['publication']->publisher->full_name;

        $data['lastvisited'] = array();

        // Get cookie of last visited by the user

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

        return Response::view('publication', $data)->withCookie($cookie);
	}


    /**
     * Get publication list for publishers and admins
     *
     * @return mixed
     */
    public function getLista() {

        $user = Auth::user();

        // Si no es publisher lo boto
        if (!$user->isAdmin() && !$user->isSubAdmin() && !$user->isPublisher()){
            return Redirect::to('/');
        }

        $state = self::retrieveListState();
        $publications = PublicationView::select(DB::raw('publications_view.id, title, publications_view.created_at, from_date, to_date, publications_view.status, publications_view.seller_name, visits_number, rating_avg, reports, ratings'))->orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $publications->where(function($query) use ($q)
            {
                $query->orWhere('title', 'LIKE', '%' . $q . '%')
                    ->orWhere('categories', 'LIKE', '%' . $q . '%')
                    ->orWhere('from_date', 'LIKE', '%' . $q . '%')
                    ->orWhere('to_date', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        //Status filter
        if (!empty($state['filter_status'])){
            $publications->where('publications_view.status', '=', $state['filter_status']);
        }

        //Category filter
        if (is_array($state['filter_categories'])) {
            $publications->leftJoin('publications_categories','publications_view.id','=','publications_categories.publication_id');
            $publications->whereIn('publications_categories.category_id', $state['filter_categories']);
        }

        //Publisher filter
        if (is_array($state['filter_publishers'])) {
            $publications->whereIn('publisher_id', $state['filter_publishers']);
        }

        //Publications with reports filter
        if (!empty($state['filter_publications_with_reports']) && $state['filter_publications_with_reports'] == true) {
            $publications->where('reports', '>', 0);
        }

        //From start date
        if (!empty($state['from_start_date'])) {
            //echo strtotime($state['from_start_date']);
            $publications->where('from_date', '>=', date("Y-m-d", strtotime($state['from_start_date'])));
        }

        //To start date
        if (!empty($state['to_start_date'])) {
            $publications->where('from_date', '<=', date("Y-m-d", strtotime($state['to_start_date'])));
        }

        //From end date
        if (!empty($state['from_end_date'])) {
            $publications->where('to_date', '>=', date("Y-m-d", strtotime($state['from_end_date'])));
        }

        //To end date
        if (!empty($state['to_end_date'])) {
            $publications->where('to_date', '<=', date("Y-m-d", strtotime($state['to_end_date'])));
        }

        // Filter by subAdmin group
        if (Auth::user()->isSubAdmin()){
            $publications->leftJoin('publishers','publishers.id','=','publications_view.publisher_id');
            $publications->leftJoin('users','users.id','=','publishers.user_id');
            $publications->where('users.group_id', Auth::user()->group_id);
        }

        if ($user->isPublisher()){
            $publications->where('publisher_id', '=', $user->publisher->id);
        }

        $publications->groupBy('publications_view.id');
        $publications = $publications->paginate($this->page_size);

        $publisherFilterValues=array();
        $categoryFilterValues=array();

        foreach (PublicationView::publishersWithPublications()->get() as $item) {
            $publisherFilterValues[$item->publisher_id] = $item->seller_name;
        }

        foreach (PublicationView::categoriesWithPublications()->get() as $item) {
            $categoryFilterValues[$item->category_id] = $item->category_name;
        }

        //$view = 'publication_list';
        //if ($user->isAdmin()){
            $view = 'backend_publication_list';
        //}

        return View::make($view, array(
            'pub_statuses' => self::getPublicationStatuses(Lang::get('content.filter_status_placeholder')),
            'pub_publishers' => $publisherFilterValues,
            'pub_categories' => $categoryFilterValues,
            'publications' => $publications,
            'state' => $state,
            'user' => $user
            ) //end array
        );
    }

    /**
     * Post request facade for getLista()
     *
     * @return mixed
     */
    public function postLista() {
        return $this->getLista();
    }

    /**
     * Build list state, to restore users searchs and filters
     *
     * @return mixed
     */
    private function retrieveListState(){
        $state = Session::get('pub_list.state');
        $isPost = (Input::server("REQUEST_METHOD") == "POST");

        $state['active_filters'] = is_null($state['active_filters'])? 0 : $state['active_filters'];

        /* Basic filters and sort */

        //Sort
        $sort = (in_array(Input::get('sort'), $this->listSort) ? Input::get('sort') : null);

        if ((isset($sort)) || !(isset($state['sort']))) {
            $state['sort'] = (isset($sort))? $sort : 'id';
        }

        //Order
        $order = (in_array(Input::get('order'), array('asc', 'desc')) ? Input::get('order') : null);

        if ((isset($order)) || !(isset($state['order']))) {
            $state['order'] = (isset($order))? $order : 'desc';
        }

        //Query
        $q = (!is_null(Input::get('q')) ? Input::get('q') : null);

        if ((isset($q)) || !(isset($state['q']))) {
            $state['q'] = (isset($q))? $q : '';
        }

        /* Begin custom filters */

        //Status
        $status = (!is_null(Input::get('filter_status')) ? Input::get('filter_status') : null);

        if ((isset($status)) || !(isset($state['filter_status']))) {
            $state['filter_status'] = (isset($status))? $status : '';
        }

        //Categories
        $state['filter_categories'] = (isset($state['filter_categories']) ? $state['filter_categories'] : null);

        if ($isPost) {
            $state['filter_categories'] = Input::get('filter_categories');
        }

        //Publishers
        $state['filter_publishers'] = (isset($state['filter_publishers']) ? $state['filter_publishers'] : null);

        if ($isPost) {
            $state['filter_publishers'] = Input::get('filter_publishers');
        }

        //Publications with reports
        $valueSuggestProducts = Input::get('filter_publications_with_reports');
        $pubWithReports = ((isset($valueSuggestProducts)) ? true : false);

        if ((isset($pubWithReports)) || !(isset($state['filter_publications_with_reports']))) {
            $state['filter_publications_with_reports'] = (isset($pubWithReports))? $pubWithReports : '';
        }

        //From start date
        $state['from_start_date'] = (isset($state['from_start_date']) ? $state['from_start_date'] : null);

        if ($isPost) {
            $state['from_start_date'] = Input::get('from_start_date');
        }

        //To start date
        $state['to_start_date'] = (isset($state['to_start_date']) ? $state['to_start_date'] : null);

        if ($isPost) {
            $state['to_start_date'] = Input::get('to_start_date');
        }

        //From end date
        $state['from_end_date'] = (isset($state['from_end_date']) ? $state['from_end_date'] : null);

        if ($isPost) {
            $state['from_end_date'] = Input::get('from_end_date');
        }

        //To end date
        $state['to_end_date'] = (isset($state['to_end_date']) ? $state['to_end_date']: null);

        if ($isPost) {
            $state['to_end_date'] = Input::get('to_end_date');
        }
        /* End custom filters */

        /* Basic filters not count */
        $ignoreFilters = array('active_filters', 'sort', 'order');
        if ($isPost) {
            $state['active_filters'] = 0;
            foreach ($state as $key => $item) {
                if (!in_array($key, $ignoreFilters)) {
                    if (isset($item) && !empty($item)){
                        $state['active_filters']++;
                    }
                }
            }
        }

        Session::put('pub_list.state', $state);

        return $state;
    }

    /**
     * Add new publication (only admins and publishers)
     *
     * @return mixed
     */
    public function getCrear() {

        // Get the current user publications list
        //Get publisher
        $publisher = Auth::user()->publisher;

        // Populate categories
        $pubCats = (is_array(Input::old('categories'))) ? Input::old('categories') : array();

        if (empty($pubCats)) {
            //Populate categories based on publisher categories
            $sectors = $publisher->categories;
            //Merge with business sectors
            foreach ($sectors as $sector) {
                $pubCats[] = $sector->id;
            }
        }

        // Populate publication contacts
        $pubContacts = (is_array(Input::old('contacts')))? Input::old('contacts') : array();


        $pub = new Publication();
        $pub->from_date = date('d-m-Y',time());
        $pub->to_date = date('d-m-Y',strtotime('+90 day'));

        return View::make('publication_form',
            array('pub_statuses' => self::getPublicationStatuses(Lang::get('content.select')),
                  'publication' => $pub,
                  'publication_categories' => $pubCats,
                  'publication_contacts' => $pubContacts,
                  'contacts' => $publisher->contacts,
                  'referer' => URL::previous(),
                )
            );
    }

    /**
     * @ajax
     *
     * Necesito comprobar que sea un publisher y que la publicacion pasada por id sea de el
     *
     * Process publication images form, link image to publication
     *
     * @param $id the publication id
     * @return mixed json response
     */
    public function postImagenes($id) {

        $file = Input::file('file');
        $destinationPath =  public_path() . '/uploads/pub/'.$id;

        if (!is_dir($destinationPath)){
            mkdir($destinationPath);
        }

        $publication = Publication::find($id);
        $size = getimagesize($file);

        $upload_success = false;
        $error = '';

        //Validate image size

        if ($size[0] < BaseController::$detailSize['width'] ) {
            $error = 'invalid_size';
        }

        if ($size[1] < BaseController::$detailSize['height']) {
            $error = 'invalid_size';
        }

        $baseName = str_random(15);

        $finalFileName = $baseName . '.jpg';
        $originalFileName = $destinationPath . '/' . $finalFileName;
        $detailFileName = $destinationPath . '/' . $baseName . '_' . BaseController::$detailSize['width'] . '.jpg';
        $thumbFileName = $destinationPath . '/' . $baseName . '_' . BaseController::$thumbSize['width'] . '.jpg';

        if (empty($error)){
            ImageHelper::generateThumb($file->getPathName(), $originalFileName, $size[0], $size[1]);
            ImageHelper::generateThumb($file->getPathName(), $detailFileName,  BaseController::$detailSize['width'],  BaseController::$detailSize['height']);
            ImageHelper::generateThumb($file->getPathName(), $thumbFileName, BaseController::$thumbSize['width'], BaseController::$thumbSize['height']);
            $upload_success = true;
        }

        if ($upload_success) {
            $image = new PublicationImage(array('image_url' => $finalFileName));
            $image = $publication->images()->save($image);

            //Si no hay imagen principal se establece
            if ($publication->publication_image_id == null){
                $publication->publication_image_id = $image->id;
                $publication->save();
            }

            if (Auth::user()->isAdmin()){
                // Log when is uploaded an image to publication by an admin
                Queue::push('LoggerJob@log', array('method' => 'add', 'operation' => 'Add_publication_image', 'entities' => array($image),
                    'userAdminId' => Auth::user()->id));
            }

            Event::fire('publication.change', array($publication));
            Cache::forget(CacheHelper::$PUBLICATION . $publication->id);

            return Response::json($image->id, 200);
        } else {
            return Response::json($error, 400);
        }
    }


    /**
     * @ajax
     *
     * Deleted image from publication
     *
     * @param $id         the publication id
     * @param $imageId    the image id
     */
    public function deleteImagenes($id, $imageId) {

        //TODO: Prove publication ownership

        //Check valid publicationImage
        $pubImg = PublicationImage::where('publication_id', $id)->where('id', $imageId)->first();

        if (is_null($pubImg)){
            return Response::json('error_img_not_found', 404);
        }

        //Build image path
        $filepath = public_path() . '/' . $this->pub_img_dir . '/pub/'  . $id . '/' . $pubImg->image_url; //../public

        /* Not delete images phisically from disk */
//        if (file_exists($filepath)){
            //Remove img from disk (img by diferent sizes)
            //$result = unlink($filepath);

//            if ($result === false){
//                return Response::json('error_removing_file', 400);
//            }
//        }

        //remove thumb
        //$thumb = str_replace('.', '_' . BaseController::$thumbSize['width'] . '.', $filepath);

//        if (file_exists($thumb)){
//            //Remove img from disk (img by diferent sizes)
//            $result = unlink($thumb);
//
//            if ($result === false){
//                return Response::json('error_removing_file', 400);
//            }
//        }

        //remove detail
//        $detail = str_replace('.', '_' . BaseController::$detailSize['width'] . '.', $filepath);
//
//        if (file_exists($detail)){
//            //Remove img from disk (img by diferent sizes)
//            $result = unlink($detail);
//
//            if ($result === false){
//                return Response::json('error_removing_file', 400);
//            }
//        }

        if (Auth::user()->isAdmin()){
            // Log when is deleted an image from publication by an admin
            Queue::push('LoggerJob@log', array('method' => 'delete', 'operation' => 'Delete_publication_image', 'entities' => array($pubImg),
                'userAdminId' => Auth::user()->id));
        }

        //Si es la imagen principal, desasociar y asociar la siguiente

        $publication = Publication::find($id);

        if ($publication->publication_image_id === $pubImg->id) {
            $publication->publication_image_id = null;
            $publication->save();
        }

        //Remove img from db
        $affectedRows = $pubImg->delete();

        //Set new main image if necessary
        if ($publication->publication_image_id == null) {
            $newMainImage = PublicationImage::where('publication_id', $id)->first();
            //if another main is found, set as new image
            if ($newMainImage != null) {
                $publication->publication_image_id = $newMainImage->id;
                $publication->save();
            }
        }

        if ($affectedRows != true) {
            return Response::json('error_removing_db', 400);
        }

        Event::fire('publication.change', array($publication));

        return Response::json('success', 200);

    }

    private function canEditPublication($ownerId) {

        if (Auth::user()->isPublisher()) {
            if (Auth::user()->id == $ownerId){
                return true;
            }
        }

        if (Auth::user()->isAdmin()) {
            return true;
        }

        if (Auth::user()->isSubAdmin()){

            //Load owner group

//            if (Auth::user()->group_id == $publisherGroupId){
//
//            }

            /* TODO validate user group against publisher group, disabled right now */
            return false;
        }
        //Is admin

        return false;
    }

    /**
     * Load publication for edit
     *
     * @param $id       the publication id
     * @return mixed
     */
    public function getEditar($id) {

        if (is_null(Input::old('referer'))) {
            $referer = URL::previous();
        } else {
            $referer = Input::old('referer');
        }

        $pub = Publication::with('categories', 'images', 'publisher', 'contacts', 'publisher.contacts')->find($id);
        $pub->publisher->id;
        if (!self::canEditPublication($pub->publisher->user_id)) {
            return Response::view('errors.forbidden', array(), 403);
        }

        // Populate categories
        $pubCats = array();

        foreach ($pub->categories as $cat) {
            $pubCats[] = $cat->id;
        }

        if (is_array(Input::old('categories'))){
            $pubCats = Input::old('categories');
        }

        //Populate contacts
        $pubContacts = array();

        foreach ($pub->contacts as $cat) {
            $pubContacts[] = $cat->id;
        }

        if (is_array(Input::old('contacts'))){
            $pubContacts = Input::old('contacts');
        }

        // Get contacts ordered, main contact always first
        $contacts = Contact::where('publisher_id', $pub->publisher->id)->orderBy('is_main', 'desc')->get();

        return View::make('publication_form',
            array(
                'pub_statuses' => self::getPublicationStatuses(Lang::get('content.select')),
                'contacts' => $contacts,
                'publication' => $pub,
                'publication_categories' => $pubCats,
                'publication_contacts' => $pubContacts,
                'referer' => $referer,
            )
        );

    }

    /**
     * Save publication
     *
     * @return mixed
     */
    public function postGuardar() {

        //Get publication data
        $pubData = array(
            'id' => Input::get('id'),
            'title' => Input::get('title'),
//            'short_description' => Input::get('short_description'),
            'long_description' => Input::get('long_description'),
            'status' => Input::get('status'),
            'from_date' => Input::get('from_date'),
            'to_date' => Input::get('to_date'),
            'latitude' => (Input::get('latitude')=='')?null:Input::get('latitude'),
            'longitude' => (Input::get('longitude')=='')?null:Input::get('longitude'),
            'visits_number' => Input::get('visits_number'),
            'created_at' => Input::get('created_at'),
            'publisher_id' => Input::get('publisher_id'),
            'categories' => Input::get('categories'),
//            'show_pub_as_contact' => Input::get('show_pub_as_contact'),
            'contacts' => Input::get('contacts'),
            'remember' => Input::get('remember'),
        );

        //Set validation rules
        $rules = array(
            'title' => 'required',
//            'short_description' => 'required',
            'long_description' => 'required|max:500',
            'status' => 'required',
            'from_date' => 'required|date_format:d-m-Y|publication_invalid_date_range:'. $pubData['to_date'] .'|publication_limit_date_range:'. $pubData['to_date'],
            'to_date' => 'required|date_format:d-m-Y',
            'latitude' => 'numeric|min:-90|max:90',
            'longitude' => 'numeric|min:-90|max:90',
            'categories' => 'required',
        );

        $messages = array(
            'category.required' => Lang::get('validation.publication_category_required'),
            //'from_date.date_format' => Lang::get('validation.publication_date_invalid'),
            //'to_date.date_format' => Lang::get('validation.publication_date_invalid'),
            'from_date.publication_invalid_date_range' => Lang::get('validation.publication_invalid_date_range'),
            'from_date.publication_limit_date_range' => Lang::get('validation.publication_limit_date_range'),
        );

        Validator::extend('publication_invalid_date_range', function($attribute, $value, $parameters){

            $fromTime = strtotime($value);
            $toTime = strtotime($parameters[0]);

            if ($toTime >= $fromTime) {
                return true;
            } else {
                return false;
            }

        });

        Validator::extend('publication_limit_date_range', function($attribute, $value, $parameters){

            $fromTime = strtotime($value);
            $toTime = strtotime($parameters[0]);
            $diff = abs($toTime - $fromTime);
            $diffDays = $diff/(60 * 60 * 24);
//            $years = floor($diff / (365*60*60*24));
//            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//            $days = floor(($diff + $years * 365*60*60*24 + $months*30*60*60*24)/ (60*60*24));

//            echo 'years: ' + $years;
//            echo 'months: ' + $months;

//            if ($toTime >= $fromTime && ($months < 3 || ($months == 3 && $days == 0))) {
            if ($toTime >= $fromTime && $diffDays <= 90) {
                return true;
            } else {
                return false;
            }

        });

        // Validate fields
        $v = Validator::make($pubData, $rules, $messages);
        if ( $v->fails() )
        {
            $action = 'crear';

            if (!empty($pubData['id'])) {
                $action = 'editar/' . $pubData['id'];
            }

            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('publicacion/' . $action)
                //->with('publication', new Publication())
                ->withErrors($v)
                ->withInput();
        }

        //Save publication
        $isNew = true;
        $method = '';
        $operation = '';
        $previousData = null;

        if (empty($pubData['id'])){
            $pub = new Publication($pubData);
            //detect user id
            $pub->publisher_id = Auth::user()->publisher->id;
            $method = 'add';
            $operation = 'Add_publication';

        } else {
            $isNew = false;
            $pub = Publication::find($pubData['id']);

            // Si cambiaron los campos Titulo o Descripcion Corta, entonces se reinicia contador de publicaciones
            if (($pub->title != $pubData['title'])){// || ($pub->short_description != $pubData['short_description'])){
                $pub->visits_number = 0;
                PublicationVisit::where('publication_id', $pubData['id'])->delete();
            }

            $previousData = $pub->getOriginal();
            $pub->fill($pubData);
            $method = 'edit';
            $operation = 'Edit_publication';
        }

        $pub->from_date = date('Y-m-d',strtotime($pubData['from_date']));
        $pub->to_date = date('Y-m-d',strtotime($pubData['to_date']));

        if ($pub->remember == null){
            $pub->remember = 0;
        }

        $pub->save();
        Cache::forget(CacheHelper::$PUBLICATION . $pub->id);

        // Save publication categories
        $categories = (array) $pubData['categories'];
        $contacts = (array) $pubData['contacts'];

        $pub->categories()->sync($categories);
        $pub->contacts()->sync($contacts);

        if (Auth::user()->isAdmin()){
            // Log when is created or edited a publication by an admin
            Queue::push('LoggerJob@log', array('method' => $method, 'operation' => $operation, 'entities' => array($pub),
                'userAdminId' => Auth::user()->id, 'previousData' => array($previousData)));
        }

        Event::fire('publication.save', array($pub));

        // Redirect to diferent places based on new or existing publication
        if ($isNew) {
            //Session::flash('flash_global_message', Lang::get('content.add_publication_success'));
            //Redirect to publication images
            return Redirect::to('publicacion/editar/'.$pub->id . '#imagenes');

        } else {
            $this->addFlashMessage(Lang::choice('content.edit_publication',1), Lang::get('content.edit_publication_success'));
            //Session::flash('flash_global_message', Lang::get('content.edit_publication_success'));
            //Redirect to a referer if exists
            $referer = Session::get($this->prefix . '_referer');
            if (!empty($referer)){
                return Redirect::to($referer);
            }
            return Redirect::to('publicacion/lista');

        }
    }

    public function getEliminar($id) {

        $action = 'lista';

        if (empty($id)) {
            return Response::view('errors.missing', array(), 404);
        }

        $pub = Publication::find($id);

        if (empty($pub)){
            self::addFlashMessage(null, Lang::get('content.delete_publication_invalid'), 'error');
            return Redirect::to('publicacion/'. $action);
        }

        $result = $pub->delete();

        Event::fire('publication.delete', array($pub));

        // Log when is deleted a publication by an admin
        if (Auth::user()->isAdmin()){
            Queue::push('LoggerJob@log', array('method' => 'delete', 'operation' => 'Delete_publication', 'entities' => array($pub),
                'userAdminId' => Auth::user()->id));
        }

        if ($result){
            self::addFlashMessage(null, Lang::get('content.delete_publication_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.delete_publication_error'), 'error');
        }

        $referer = URL::previous();
        if (!empty($referer)){
            return Redirect::to($referer);
        }
        return Redirect::to('publicacion/'. $action);

    }

    /** Cron job = Executed 01:00 am - publicacion/verificar-vencimiento-publicacion
     *  Send notification email to users which their publications are next to expire.
    **/
    public function getVerificarVencimientoPublicacion(){

        $pubs = Publication::nextToExpirePublication()->get();

        // Send notification email to users which publication is next to expire
        $data = array(
            'contentEmail' => 'user_notification_publication_next_expire',
        );

        foreach ($pubs as $pub){
            $data['sellerName'] = $pub->publisher->seller_name;
            $data['fromDate'] = date("d-m-Y", strtotime($pub->from_date));
            $data['toDate'] = date("d-m-Y", strtotime($pub->to_date));
            $data['myPubLink'] = URL::to('publicacion/lista');

            $receiver = array(
                'email' => $pub->publisher->user->email,
            );

            $subject = Lang::get('content.email_publication_next_expire', array('pubId' => $pub->id));

            self::sendMultipleMail('emails.layout_email', $data, $receiver, $subject);
        }

        // Notify the admin about the result of the operation
        $receiver = array(
            'email' => Config::get('emails/addresses.email_cron_report'),
            'name' => Config::get('emails/addresses.name_cron_report'),
        );

        $data = array(
            'contentEmail' => 'general_notification',
            'notification' => Lang::get('content.email_cron_admin_notification_pub_next_expire_content'),
        );

        $subject = Lang::get('content.email_cron_admin_notification_pub_next_expire_subject');

        self::sendMail('emails.layout_email', $data, $receiver, $subject);

        return;
    }

    /** Cron job = Executed 02:00 am - publicacion/cambiar-estatus-por-fechas
     *  Update publication's status according to date range specified in the publication.
     **/
    public function getCambiarEstatusPorFechas(){

        $currentDate = Date('Y-m-d', strtotime('now'));
        $currentDateTime = Date('Y-m-d h:i:s', strtotime('now'));

        // Activate publications - Change status to Published for publications that have from_date today and status On_Hold
        Publication::where('from_date', $currentDate)
                    ->where('status', 'On_Hold')
                    ->update(array('status'=>'Published', 'updated_at' => $currentDateTime));

        // Desactivate publications - Change status to Finished for publications that have to_date less than today
        Publication::where('to_date', '<', $currentDate)
                    ->where('status', 'Published')
                    ->update(array('status'=>'Finished', 'updated_at' => $currentDateTime));

        // Notify the admin about the result of the operation
        $receiver = array(
            'email' => Config::get('emails/addresses.email_cron_report'),
            'name' => Config::get('emails/addresses.name_cron_report'),
        );

        $data = array(
            'contentEmail' => 'general_notification',
            'notification' => Lang::get('content.email_cron_admin_notification_pub_change_status_date_content'),
        );

        $subject = Lang::get('content.email_cron_admin_notification_pub_change_status_date_subject');

        self::sendMail('emails.layout_email', $data, $receiver, $subject);

        return;

    }

    private static function getPublicationStatuses($blankCaption = '') {
        return StatusHelper::getStatuses(StatusHelper::$TYPE_PUBLICATION, $blankCaption);
    }

    private static function getThumbSizeSuffix(){
        return '_' . self::$thumbSize['width'] . 'x' . self::$thumbSize['height'];
    }

    private static function getDetailSizeSuffix(){
        return '_' . self::$detailSize['width'] . 'x' . self::$detailSize['height'];
    }


}
