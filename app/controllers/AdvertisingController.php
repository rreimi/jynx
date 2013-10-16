<?php

class AdvertisingController extends BaseController {

    private $prefix = 'advertising';
    private $page_size = '10';
    private $listSort = array('id', 'name', 'status', 'order', 'image_url', 'external_url', 'full_name');
//    private $pub_img_dir = 'advertisement';

    public function __construct() {
        $this->beforeFilter('admin');
        $this->beforeFilter('referer:advertising', array('only' => array('getLista')));

//        $this->afterFilter(function()
//        {
//            self::invalidateAdvertisingCache(); $this->invalidateAdvertisingCache();
//        }, array('only' => 'postGuardar', 'getEliminar' , 'postImagenes', 'deleteImagenes'));

        View::share('bannerTopHomeSize', self::$bannerTopHomeSize);
    }

    public function getLista() {

        /* Get sort params */
//        $sort = (in_array(Input::get('sort'), $this->pubListSort) ? Input::get('sort') : 'id');
//        $order = (in_array(Input::get('order'), array('asc')) ? Input::get('order') : 'desc');


        //$q = Input::get('q');

        $state = self::retrieveListState();
        $advertisings = Advertising::orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $advertisings->where(function($query) use ($q)
            {
                $query->orWhere('name', 'LIKE', '%' . $q . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $q . '%')
//                    ->orWhere('from_date', 'LIKE', '%' . $q . '%')
//                    ->orWhere('to_date', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $advertisings->where('status', '=', $status);
        }

//        $advertisings->where('publisher_id', '=', Auth::user()->publisher->id);
        $advertisings = $advertisings->paginate($this->page_size);

        return View::make('advertising_list', array(
                'adv_statuses' => self::getAdvertisingStatuses(Lang::get('content.filter_status_placeholder')),
                'advertisings' => $advertisings,
                'state' => $state,
            ) //end array
        );
    }

    private function retrieveListState(){
        $state = Session::get('adv_list.state');
        $isPost = (Input::server("REQUEST_METHOD") == "POST");

        $state['active_filters'] = is_null($state['active_filters'])? 0 : $state['active_filters'];

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

        $q = (!is_null(Input::get('q')) ? Input::get('q') : null);

        if ((isset($q)) || !(isset($state['q']))) {
            $state['q'] = (isset($q))? $q : '';
        }

        $status = (!is_null(Input::get('filter_status')) ? Input::get('filter_status') : null);

        if ((isset($status)) || !(isset($state['filter_status']))) {
            $state['filter_status'] = (isset($status))? $status : '';
        }

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

        Session::put('adv_list.state', $state);
        return $state;
    }

    public function postLista() {
        return $this->getLista();
    }

    public function getCrear() {

        $adv = new Advertising();
        $adv->external_url = 'http://';

        return View::make('advertising_form',
                    array('adv_statuses' => self::getAdvertisingStatuses(Lang::get('content.select')),
                        'advertising' => $adv,
                        'referer' => URL::previous()
                    ));

    }

    public function getEditar($id) {

        if (is_null(Input::old('referer'))) {
            $referer = URL::previous();
        } else {
            $referer = Input::old('referer');
        }

        $adv = Advertising::find($id);

        return View::make('advertising_form',
            array(
                'adv_statuses' => self::getAdvertisingStatuses(),
                'advertising' => $adv,
                'id' => $id,
                'referer' => $referer,
            )
        );

    }

    public function getEliminar($id) {

        $action = 'lista';

        if (empty($id)) {
            return Response::view('errors.missing', array(), 404);
        }

        $adv = Advertising::find($id);

        if (empty($adv)){
            self::addFlashMessage(null, Lang::get('content.delete_advertising_invalid'), 'error');
            return Redirect::to('publicidad/'. $action);
        }

        $result = $adv->delete();

        // TODO: Activate
//        Queue::push('LoggerJob@log', array('method' => 'delete', 'operation' => 'Delete_advertising', 'entities' => array($adv),
//            'userAdminId' => Auth::user()->id));

        $this->invalidateAdvertisingCache();

        if ($result){
            self::addFlashMessage(null, Lang::get('content.delete_advertising_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.delete_advertising_error'), 'error');
        }

        return Redirect::to('publicidad/'. $action);

    }

    public function postGuardar() {

        //Get advertising data
        $advData = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'status' => Input::get('status'),
            'external_url' => Input::get('external_url'),
            'full_name' => Input::get('full_name'),
            'order' => Input::get('order'),
            'email' => Input::get('email'),
            'phone1' => Input::get('phone1'),
            'phone2' => Input::get('phone2')
        );

        //Set validation rules
        $rules = array(
            'name' => 'required',
            'status' => 'required',
            'external_url' => 'required | url',
            'full_name' => 'required',
            'order' => 'required | integer',
            'email' => 'required | email',
            'phone1' => array('required', 'regex:'. $this->phoneNumberRegex),
            'phone2' => array('regex:'. $this->phoneNumberRegex),
        );

//        $messages = array(
//            'title.required' => Lang::get('validation.required', array('attribute' => Lang::get('content.title'))),
//        );

        // Validate fields
        $v = Validator::make($advData, $rules);
        if ( $v->fails() )
        {
            $action = 'crear';

            if (!empty($advData['id'])) {
                $action = 'editar/' . $advData['id'];
            }

            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('publicidad/' . $action)
                //->with('publication', new Publication())
                ->withErrors($v)
                ->withInput();
        }

        //Save publicidad
        $isNew = true;
        $method = '';
        $operation = '';
        $previousData = null;

        if (empty($advData['id'])){
            $adv = new Advertising($advData);
            $method = 'add';
            $operation = 'Add_advertising';
        } else {
            $isNew = false;
            $adv = Advertising::find($advData['id']);
            $previousData = $adv->getOriginal();
            $adv->fill($advData);
            $method = 'edit';
            $operation = 'Edit_advertising';
        }

        $adv->save();

        // TODO: Activate
//        Queue::push('LoggerJob@log', array('method' => $method, 'operation' => $operation, 'entities' => array($adv),
//            'userAdminId' => Auth::user()->id, 'previousData' => array($previousData)));

        $this->invalidateAdvertisingCache();

        // Redirect to diferent places based on new or existing advertising
        if ($isNew) {

            //Redirect to publication images
            return Redirect::to('publicidad/editar/'. $adv->id .'#imagenes');

        } else {
            self::addFlashMessage(null, Lang::get('content.edit_advertising_success'), 'success');
            //Redirect to a referer if exists
            $referer = Session::get($this->prefix . '_referer');
            if (!empty($referer)){
                return Redirect::to($referer);
            }
            return Redirect::to('publicidad/lista');

        }
    }

    public function postImagenes($id) {

        $file = Input::file('file');
        $destinationPath = public_path() . '/uploads/adv/'.$id;
        //$filename = $file->getClientOriginalName();

        $size = getimagesize($file);

        $upload_success = false;
        $error = '';

        //Validate image size
        if ($size[0] < BaseController::$bannerTopHomeSize['width'] ) {
            $error = 'invalid_size';
        }

        if ($size[1] < BaseController::$bannerTopHomeSize['height']) {
            $error = 'invalid_size';
        }

        $baseName = str_random(15);

        $finalFileName = $baseName . '.jpg';
        $scaledFileName = $destinationPath . '/' . $baseName . '_' . BaseController::$bannerTopHomeSize['width'] . '.jpg';

        if (empty($error)){
            // Delete previous files
            $previousFiles = scandir($destinationPath);
            foreach ($previousFiles as $pFile){
                $filePath = $destinationPath ."/". $pFile;
                if ($pFile != '.' && $pFile != '..' && file_exists($filePath)){
                    unlink($filePath);
                }
            }

            //Generate scaled version
            ImageHelper::generateThumb($file->getPathName(), $scaledFileName,  BaseController::$bannerTopHomeSize['width'],  BaseController::$bannerTopHomeSize['height']);

            //Save uploaded file
            $upload_success = $file->move($destinationPath, $finalFileName);
        }

        $advertising = Advertising::find($id);
        //TODO posibilidad de agregar un alt

        if( $upload_success ) {
            $advertising->image_url = $finalFileName;
            $advertising->save();

            $this->invalidateAdvertisingCache();

            return Response::json($id, 200);
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
     */
    public function deleteImagenes($id) {

        // Check valid image advertising
        $adv = Advertising::find($id);

        if (is_null($adv)){
            return Response::json('error_img_not_found', 404);
        }

        //Build image path
        $filepath = public_path() . '/uploads/adv/'  . $id . '/' . $adv->image_url; //../public

        if (file_exists($filepath)){
            //Remove img from disk (img by diferent sizes)
            $result = unlink($filepath);

            if ($result === false){
                return Response::json('error_removing_file', 400);
            }
        }

        //remove thumb
        $filepath = str_replace('.', '_' . BaseController::$bannerTopHomeSize['width'] . '.', $filepath);

        if (file_exists($filepath)){
            //Remove img from disk (img by diferent sizes)
            $result = unlink($filepath);

            if ($result === false){
                return Response::json('error_removing_file', 400);
            }
        }

        //Remove img from db
        $adv->image_url = null;
        $removed = $adv->save();

        if ($removed != true) {
            return Response::json('error_removing_db', 400);
        }

        $this->invalidateAdvertisingCache();

        return Response::json('success', 200);

    }

    private static function getAdvertisingStatuses($blankCaption = '') {

        $options = array (
            'Draft' => Lang::get('content.status_Draft'),
            'Published' => Lang::get('content.status_Published'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private function invalidateAdvertisingCache() {
        //Invalidate Cache
        Cache::forget('currentAdvertising');
    }

}