<?php

class PublicationController extends BaseController {

    private $prefix = 'publication';
    private $page_size = '6';
    private $listSort = array('id', 'title', 'from_date', 'to_date', 'visits_number', 'created_at', 'status', 'rating_avg', 'seller_name');
    private $pub_img_dir = 'uploads';

    public function __construct() {
        //$this->beforeFilter('auth');
        $this->beforeFilter('referer:publication', array('only' => array('getLista', 'getDetalle')));
        if (!Auth::check()){
            $this->beforeFilter('referer:login_redirect', array('only' => array('getDetalle')));
        }
        View::share('categories', self::getCategories());
        View::share('services', self::getServices());
        View::share('detailSize', self::$detailSize);
        View::share('thumbSize', self::$thumbSize);

    }

	public function getDetalle($id = null) {

        if ($id == null){
            return Response::view('errors.missing', array(), 404);
        }

		/* Cargar la lista de categorias */
        $data['publication'] = Publication::with('images', 'publisher', 'publisher.contacts')->find($id);

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

        /* Increment visits counter */
        $data['publication']->increment('visits_number');

        // INIT - Create log of publication
        $pubVisit = new PublicationVisit();
        $pubVisit->publication_id = $id;
        $pubVisit->save();
        // END - Create log of publication

//        var_dump(DB::getQueryLog());
//        die();
        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* TODO Cargar la lista de los últimos productos vistos por el usuario actual */

        $publisher = User::find($data['publication']->publisher->user_id);
        $data['publisher_email']=$publisher->email;

        $data['lastvisited'] = array();
        /* Get cookie of last visited by the user */
        $cookieName = (Auth::check()) ? ('last_visited_'. Auth::user()->id) : 'last_visited';
        $cookieArray = Cookie::get($cookieName);
        if (isset($cookieArray)){
            $lastVisited = Publication::whereIn("id", $cookieArray)->get();

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

    public function getLista() {

        $user = Auth::user();

        // Si no es publisher lo boto
        if (!$user->isAdmin() && !$user->isPublisher()){
            return Redirect::to('/');
        }

        $state = self::retrieveListState();
        $publications = PublicationView::select(DB::raw('TRIM(GROUP_CONCAT(" ",category_name)) as categories, id, title, created_at, from_date, to_date, status, seller_name, visits_number, rating_avg, reports'))->orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $publications->where(function($query) use ($q)
            {
                $query->orWhere('title', 'LIKE', '%' . $q . '%')
                    ->orWhere('category_name', 'LIKE', '%' . $q . '%')
                    ->orWhere('from_date', 'LIKE', '%' . $q . '%')
                    ->orWhere('to_date', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        //Status filter
        if (!empty($state['filter_status'])){
            $publications->where('status', '=', $state['filter_status']);
        }

        //Category filter
        if (is_array($state['filter_categories'])) {
            $publications->whereIn('category_id', $state['filter_categories']);
        }

        //Publisher filter
        if (is_array($state['filter_publishers'])) {
            $publications->whereIn('publisher_id', $state['filter_publishers']);
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

        if ($user->isPublisher()){
            $publications->where('publisher_id', '=', $user->publisher->id);
        }

        $publications->groupBy('id');
        $publications = $publications->paginate($this->page_size);

        $publisherFilterValues=array();
        $categoryFilterValues=array();

        foreach (PublicationView::publishersWithPublications()->get() as $item) {
            $publisherFilterValues[$item->publisher_id] = $item->seller_name;
        }

        foreach (PublicationVIew::categoriesWithPublications()->get() as $item) {
            $categoryFilterValues[$item->category_id] = $item->category_name;
        }

        $view = 'publication_list';
        if ($user->isAdmin()){
            $view = 'backend_publication_list';
        }

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

    public function postLista() {
        return $this->getLista();
    }

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

        //Categories
        $state['filter_publishers'] = (isset($state['filter_publishers']) ? $state['filter_publishers'] : null);

        if ($isPost) {
            $state['filter_publishers'] = Input::get('filter_publishers');
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
     * Solo si es publisher
     *
     * @return mixed
     */
    public function getCrear() {

        // Get the current user publications list

        // Populate categories
        $pubCats = (is_array(Input::old('categories'))) ? Input::old('categories') : array();

        // Populate publication contacts
        $pubContacts = (is_array(Input::old('contacts')))? Input::old('contacts') : array();

        //Get publisher
        $publisher = Auth::user()->publisher;

        $pub = new Publication();
        $pub->from_date = date('d-m-Y',time());
        $pub->to_date = date('d-m-Y',time());

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
        $destinationPath = 'uploads/pub/'.$id;
        $filename = $file->getClientOriginalName();

        $publication = Publication::find($id);

        //TODO validar publicacion
        //TODO renombrar la imagen si existe
        //TODO posibilidad de agregar un alt
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


        if (empty($error)){
            /* Move uploaded file to final destination */
            $upload_success = $file->move($destinationPath, $filename);
        }

        //Deprecated, Image library from kevbaldwyn is used for resize image with responsive support
        /* Set full path for create resized versions */
        //$fullImagePath = $destinationPath . DIRECTORY_SEPARATOR . $filename;

        /* Create resized versions for lists and detail */
        //Image::make($fullImagePath)->resize(self::$thumbSize['width'], self::$thumbSize['height'])->save(str_replace(".", self::getThumbSizeSuffix() . ".", $fullImagePath));
        //Image::make($fullImagePath)->resize(self::$detailSizeSize['width'], self::$detailSizeSize['height'])->save(str_replace(".", self::getDetailSizeSuffix() . ".", $fullImagePath));

//        $error = 'Error';

        if ($upload_success) {
            $image = new PublicationImage(array('image_url' => $filename));
            $image = $publication->images()->save($image);
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

        //Provee publication ownership

        //Check valid publicationImage
        $pubImg = PublicationImage::where('publication_id', $id)->where('id', $imageId)->first();

        if (is_null($pubImg)){
            return Response::json('error_img_not_found', 404);
        }

        //Build image path
        $filepath = public_path() . '/' . $this->pub_img_dir . '/pub/'  . $id . '/' . $pubImg->image_url; //../public

        if (file_exists($filepath)){
            //Remove img from disk (img by diferent sizes)
            $result = unlink($filepath);

            if ($result === false){
                return Response::json('error_removing_file', 400);
            }
        }

        //Remove img from db
        $affectedRows = $pubImg->delete();

        if ($affectedRows != true) {
            return Response::json('error_removing_db', 400);
        }

        return Response::json('success', 200);

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

        //Get publisher
        $publisher = Auth::user()->publisher;

        //TODO verificar el publisher con el logueado

        $pub = Publication::with('categories', 'images', 'publisher', 'contacts', 'publisher.contacts')->find($id);

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

        return View::make('publication_form',
            array(
                'pub_statuses' => self::getPublicationStatuses(Lang::get('content.select')),
                'contacts' => $pub->publisher->contacts,
                'publication' => $pub,
                'publication_categories' => $pubCats,
                'publication_contacts' => $pubContacts,
                'referer' => $referer,
            )
        );

    }

    public function postGuardar() {

        //Get publication data
        $pubData = array(
            'id' => Input::get('id'),
            'title' => Input::get('title'),
            'short_description' => Input::get('short_description'),
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
            'contacts' => Input::get('contacts'),
            'remember' => Input::get('remember'),
        );

        //Set validation rules
        $rules = array(
            'title' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'status' => 'required',
            'from_date' => 'required|date_format:d-m-Y',
            'to_date' => 'required|date_format:d-m-Y',
            'latitude' => 'numeric|min:-90|max:90',
            'longitude' => 'numeric|min:-90|max:90',
            'categories' => 'required',
        );

        $messages = array(
            'category.required' => Lang::get('validation.publication_category_required'),
            //'from_date.date_format' => Lang::get('validation.publication_date_invalid'),
            //'to_date.date_format' => Lang::get('validation.publication_date_invalid'),
        );

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

        if (empty($pubData['id'])){
            $pub = new Publication($pubData);
            //detect user id
            $pub->publisher_id = Auth::user()->publisher->id;

        } else {
            $isNew = false;
            $pub = Publication::find($pubData['id']);

            // Si cambiaron los campos Titulo o Descripcion Corta, entonces se reinicia contador de publicaciones
            if (($pub->title != $pubData['title']) || ($pub->short_description != $pubData['short_description'])){
                $pub->visits_number = 0;
                PublicationVisit::where('publication_id', $pubData['id'])->delete();
            }

            $pub->fill($pubData);
        }

        $pub->from_date = date('Y-m-d',strtotime($pubData['from_date']));
        $pub->to_date = date('Y-m-d',strtotime($pubData['to_date']));

        $pub->save();

        // Save publication categories
        $categories = (array) $pubData['categories'];
        $contacts = (array) $pubData['contacts'];

        $pub->categories()->sync($categories);
        $pub->contacts()->sync($contacts);

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

        if ($result){
            self::addFlashMessage(null, Lang::get('content.delete_publication_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.delete_publication_error'), 'error');
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

        // Activate publications - Change status to Published for publications that have from_date today and status On_Hold
        Publication::where('from_date', $currentDate)
                    ->where('status', 'On_Hold')
                    ->update(array('status'=>'Published'));

        // Desactivate publications - Change status to Finished for publications that have to_date less than today
        Publication::where('to_date', '<', $currentDate)
                    ->where('status', 'Published')
                    ->update(array('status'=>'Finished'));

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

        $options = array (
            'Draft' => Lang::get('content.status_publication_Draft'),
            'Published' => Lang::get('content.status_publication_Published'),
            'On_Hold' => Lang::get('content.status_publication_On_Hold'),
            'Suspended' => Lang::get('content.status_publication_Suspended'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getThumbSizeSuffix(){
        return '_' . self::$thumbSize['width'] . 'x' . self::$thumbSize['height'];
    }

    private static function getDetailSizeSuffix(){
        return '_' . self::$detailSize['width'] . 'x' . self::$detailSize['height'];
    }
}