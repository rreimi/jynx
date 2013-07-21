<?php

class PublicationController extends BaseController {

    private $prefix = 'publication';
    private $page_size = '6';
    private $pubListSort = array('id', 'title', 'from_date', 'to_date', 'visits_number', 'created_at');
    private $pub_img_dir = 'uploads';

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('referer:publication', array('only' => array('getLista', 'getDetalle')));
        View::share('categories', self::getCategories());
        View::share('detailSize', self::$detailSize);

    }

	public function getDetalle($id = null) {

        if ($id == null){
            return Response::view('errors.missing', array(), 404);
        }

		/* Cargar la lista de categorias */
        //$data['categories'] = self::getCategories();
        $data['publication'] = Publication::with('images', 'publisher', 'publisher.contacts')->find($id);
        /* Increment visits counter */
        $data['publication']->increment('visits_number');
        //TODO Validar que la publicacion exista
        //TODO Create cookie for last visited

        //$cookie = Cookie::forever('last_visited', $id);
        //Cookie::put('key', 'value');

//        var_dump(DB::getQueryLog());
//        die();
        /* Cargar la publicidad del banner */

        /* Cargar la lista de productos con mayor número de visitas */

        /* Cargar la lista de los últimos productos agregados */

        /* TODO Cargar la lista de los últimos productos vistos por el usuario actual */

        return View::make('publication', $data);
	}

    public function getLista() {

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

        $publications->where('publisher_id', '=', Auth::user()->publisher->id);
        $publications = $publications->paginate($this->page_size);

        return View::make('publication_list', array(
            'pub_statuses' => self::getPublicationStatuses(Lang::get('content.filter_status')),
            'publications' => $publications,
            'categories' => self::getCategories(),
            'state' => $state,
            ) //end array
        );
    }

    public function postLista() {
        return $this->getLista();
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

    /**
     * Solo si es publisher
     *
     * @return mixed
     */
    public function getCrear() {

        /* Get the current user publications list */
//        echo $publications;
//        die();

        $pubCats = array();

        if (is_array(Input::old('categories'))){
            $pubCats = array_merge($pubCats, Input::old('categories'));
        }

        $pub = new Publication();
        $pub->from_date = date('d-m-Y',time());
        $pub->to_date = date('d-m-Y',time());

        return View::make('publication_form',
            array('pub_statuses' => self::getPublicationStatuses(),
                  'categories' => self::getCategories(),
                  'publication' => $pub,
                  'publication_categories' => $pubCats,
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

//        $data = exif_imagetype($file);
//        echo "<PRE>";
//        print_r($data);
//        echo "</PRE>";
//
//        //var_dump($file);
//        die();

        /* Move uploaded file to final destination */
        $upload_success = $file->move($destinationPath, $filename);




        //Deprecated, Image library from kevbaldwyn is used for resize image with responsive support
        /* Set full path for create resized versions */
        //$fullImagePath = $destinationPath . DIRECTORY_SEPARATOR . $filename;

        /* Create resized versions for lists and detail */
        //Image::make($fullImagePath)->resize(self::$thumbSize['width'], self::$thumbSize['height'])->save(str_replace(".", self::getThumbSizeSuffix() . ".", $fullImagePath));
        //Image::make($fullImagePath)->resize(self::$detailSizeSize['width'], self::$detailSizeSize['height'])->save(str_replace(".", self::getDetailSizeSuffix() . ".", $fullImagePath));

        $error = 'Error';

        if( $upload_success ) {
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

        /* Get the current user publications list */
//        echo $publications;
//        die();

        //TODO verificar el publisher con el logueado

        $pub = Publication::with('categories', 'images', 'publisher', 'publisher.contacts')->find($id);

        $pubCats = array();

        foreach ($pub->categories as $cat) {
            $pubCats[] = $cat->id;
        }

        if (is_array(Input::old('categories'))){
            $pubCats = Input::old('categories');
        }

        return View::make('publication_form',
            array(
                'pub_statuses' => self::getPublicationStatuses(),
                'categories' => self::getCategories(),
                'publication' => $pub,
                'publication_categories' => $pubCats,
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
            'visits_number' => Input::get('visits_number'),
            'created_at' => Input::get('created_at'),
            'publisher_id' => Input::get('publisher_id'),
            'categories' => Input::get('categories'),
        );

        //Set validation rules
        $rules = array(
            'title' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'status' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'categories' => 'required',
        );

        $messages = array(
            'category.required' => Lang::get('validation.publication_category_required'),
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
            $pub->fill($pubData);
        }

        $pub->from_date = date('Y-m-d',strtotime($pubData['from_date']));
        $pub->to_date = date('Y-m-d',strtotime($pubData['to_date']));

        $pub->save();

        // Save publication categories
        $categories = $pubData['categories'];
        $pub->categories()->sync($categories);

        // Redirect to diferent places based on new or existing publication
        if ($isNew) {

            //Session::flash('flash_global_message', Lang::get('content.add_publication_success'));
            //Redirect to publication images
            return Redirect::to('publicacion/editar/'.$pub->id . '#imagenes');

        } else {

            Session::flash('flash_global_message', Lang::get('content.edit_publication_success'));
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

    private static function getThumbSizeSuffix(){
        return '_' . self::$thumbSize['width'] . 'x' . self::$thumbSize['height'];
    }

    private static function getDetailSizeSuffix(){
        return '_' . self::$detailSize['width'] . 'x' . self::$detailSize['height'];
    }
}