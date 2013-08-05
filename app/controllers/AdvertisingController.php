<?php

class AdvertisingController extends BaseController {

    private $prefix = 'advertising';
    private $page_size = '6';
    private $advListSort = array('id', 'name', 'status', 'created_at');
//    private $pub_img_dir = 'advertisement';

    public function __construct() {
        $this->beforeFilter('referer:advertising', array('only' => array('getLista')));
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
            $advertisings->orWhere(function($query) use ($q)
            {
                $query->orWhere('name', 'LIKE', '%' . $q . '%')
//                    ->orWhere('categories_name', 'LIKE', '%' . $q . '%')
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
                'adv_statuses' => self::getAdvertisingStatuses(Lang::get('content.filter_status')),
                'advertisings' => $advertisings,
                'state' => $state,
            ) //end array
        );
    }

    private function retrieveListState(){
        $state = Session::get('adv_list.state');

        $sort = (in_array(Input::get('sort'), $this->advListSort) ? Input::get('sort') : null);

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

        Session::put('adv_list.state', $state);
        return $state;
    }

    public function postLista() {
        return $this->getLista();
    }

    public function getCrear() {

        $adv = new Advertising();

        return View::make('advertising_form',
                    array('adv_statuses' => self::getAdvertisingStatuses(),
                        'advertising' => $adv,
                        'referer' => URL::previous(),
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
            'email' => Input::get('email'),
            'phone1' => Input::get('phone1'),
            'phone2' => Input::get('phone2')
        );

        //Set validation rules
        $rules = array(
            'name' => 'required',
            'status' => 'required',
            'external_url' => 'required',
            'full_name' => 'required'
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

        if (empty($advData['id'])){
            $adv = new Advertising($advData);

        } else {
            $isNew = false;
            $adv = Advertising::find($advData['id']);
            $adv->fill($advData);
        }

        $adv->save();

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
        $destinationPath = 'uploads/adv/'.$id;
        $filename = $file->getClientOriginalName();

        $advertising = Advertising::find($id);

        //TODO validar publicacion
        //TODO resize image
        //TODO renombrar la imagen si existe
        //TODO posibilidad de agregar un alt

        //$extension =$file->getClientOriginalExtension();
        $upload_success = Input::file('file')->move($destinationPath, $filename);

        $error = 'Error';

        if( $upload_success ) {
            $advertising->image_url = $filename;
            $advertising->save();

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
     * @param $imageId    the image id
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

        //Remove img from db
        $adv->image_url = null;
        $removed = $adv->save();

        if ($removed != true) {
            return Response::json('error_removing_db', 400);
        }

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

}